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
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'nip'      => 'nullable|string|unique:users,nip',
            'role_id'  => 'required|exists:roles,id',
            'unit'     => 'required|string|max:100',
            'jabatan'  => 'nullable|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'gender'   => 'nullable|in:L,P',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            ...$request->only(['name', 'email', 'nip', 'role_id', 'unit', 'jabatan', 'phone', 'gender']),
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
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,'.$employee->id,
            'nip'     => 'nullable|string|unique:users,nip,'.$employee->id,
            'role_id' => 'required|exists:roles,id',
            'unit'    => 'required|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'gender'  => 'nullable|in:L,P',
        ]);

        $old = $employee->only(['name', 'email', 'unit', 'role_id']);
        $employee->update($request->only(['name', 'email', 'nip', 'role_id', 'unit', 'jabatan', 'phone', 'gender']));

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $employee->update(['password' => Hash::make($request->password)]);
        }

        \App\Models\AuditLog::record('employee_updated', 'User', $employee->id, $old, $employee->only(['name', 'email', 'unit', 'role_id']));

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
