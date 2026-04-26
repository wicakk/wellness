<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role')->where('id', '!=', auth()->id());

        if ($request->filled('role'))   $query->whereHas('role', fn($q) => $q->where('name', $request->role));
        if ($request->filled('unit'))   $query->where('unit', $request->unit);
        if ($request->filled('search')) $query->where(fn($q) => $q->where('name', 'like', '%'.$request->search.'%')->orWhere('nip', 'like', '%'.$request->search.'%'));
        if ($request->filled('status')) $query->where('is_active', $request->status === 'aktif');

        $employees = $query->latest()->paginate(15)->withQueryString();
        $roles     = Role::all();
        $units     = User::distinct()->pluck('unit');

        return view('employees.index', compact('employees', 'roles', 'units'));
    }

    public function show(User $employee)
    {
        $employee->load(['role', 'screenings.questionnaire', 'cases.interventions']);
        $screeningHistory = $employee->screenings()->where('status', 'completed')->latest('completed_at')->take(10)->get();
        $activeCases      = $employee->cases()->whereIn('status', ['belum_ditindaklanjuti', 'diproses'])->with('interventions')->get();
        return view('employees.show', compact('employee', 'screeningHistory', 'activeCases'));
    }

    public function create()
    {
        $roles = Role::all();
        $units = User::distinct()->pluck('unit')->filter()->values();
        return view('employees.create', compact('roles', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Informasi Dasar
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'nip'                 => 'required|string|max:50|unique:users,nip',
            'role_id'             => 'required|exists:roles,id',
            'unit'                => 'required|string|max:100',
            'jabatan'             => 'required|string|max:100',
            'phone'               => 'nullable|string|max:20',

            // Data Personal
            'gender'              => 'required|in:L,P',
            'usia'                => 'required|integer|min:18|max:70',
            'pendidikan'          => 'required|in:SMA/SMK,D1,D2,D3,D4,S1,Profesi,S2,S3',
            'status_pernikahan'   => 'required|in:belum_menikah,menikah,cerai_hidup,cerai_mati',
            'lama_kerja_tahun'    => 'required|integer|min:0|max:50',
            'lama_kerja_bulan'    => 'required|integer|min:0|max:11',

            // Riwayat Kesehatan
            'has_health_issue'    => 'required|boolean',
            'health_issue_detail' => 'required_if:has_health_issue,1|nullable|string|max:1000',

            // Password
            'password'            => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            ...$request->only([
                'name', 'email', 'nip', 'role_id', 'unit', 'jabatan', 'phone',
                'gender', 'usia', 'pendidikan', 'status_pernikahan',
                'lama_kerja_tahun', 'lama_kerja_bulan',
                'has_health_issue', 'health_issue_detail',
            ]),
            'password' => Hash::make($request->password),
        ]);

        \App\Models\AuditLog::record('employee_created', 'User', $user->id);

        return redirect()->route('admin.employees.show', $user)->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(User $employee)
    {
        $roles = Role::all();
        $units = User::distinct()->pluck('unit')->filter()->values();
        return view('employees.edit', compact('employee', 'roles', 'units'));
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            // Informasi Dasar
            'name'                => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email,'.$employee->id,
            'nip'                 => 'required|string|max:50|unique:users,nip,'.$employee->id,
            'role_id'             => 'required|exists:roles,id',
            'unit'                => 'required|string|max:100',
            'jabatan'             => 'required|string|max:100',
            'phone'               => 'nullable|string|max:20',

            // Data Personal
            'gender'              => 'required|in:L,P',
            'usia'                => 'required|integer|min:18|max:70',
            'pendidikan'          => 'required|in:SMA/SMK,D1,D2,D3,D4,S1,Profesi,S2,S3',
            'status_pernikahan'   => 'required|in:belum_menikah,menikah,cerai_hidup,cerai_mati',
            'lama_kerja_tahun'    => 'required|integer|min:0|max:50',
            'lama_kerja_bulan'    => 'required|integer|min:0|max:11',

            // Riwayat Kesehatan
            'has_health_issue'    => 'required|boolean',
            'health_issue_detail' => 'required_if:has_health_issue,1|nullable|string|max:1000',
        ]);

        $old = $employee->only(['name', 'email', 'unit', 'role_id']);

        $employee->update($request->only([
            'name', 'email', 'nip', 'role_id', 'unit', 'jabatan', 'phone',
            'gender', 'usia', 'pendidikan', 'status_pernikahan',
            'lama_kerja_tahun', 'lama_kerja_bulan',
            'has_health_issue', 'health_issue_detail',
        ]));

        // Kosongkan detail kesehatan jika tidak ada masalah
        if (! $request->boolean('has_health_issue')) {
            $employee->update(['health_issue_detail' => null]);
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $employee->update(['password' => Hash::make($request->password)]);
        }

        \App\Models\AuditLog::record(
            'employee_updated', 'User', $employee->id,
            $old,
            $employee->only(['name', 'email', 'unit', 'role_id'])
        );

        return redirect()->route('admin.employees.show', $employee)->with('success', 'Data pegawai diperbarui.');
    }

    public function destroy(User $employee)
    {
        $employee->update(['is_active' => false]);
        $employee->delete();
        \App\Models\AuditLog::record('employee_deleted', 'User', $employee->id);
        return redirect()->route('admin.employees.index')->with('success', 'Pegawai berhasil dinonaktifkan.');
    }
}