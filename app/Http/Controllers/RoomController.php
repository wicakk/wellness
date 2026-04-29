<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
            )
            ->when($request->status !== null, fn($q) =>
                $q->where('is_active', $request->status === 'active')
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'nullable|string|max:20|unique:rooms,code',
            'description' => 'nullable|string|max:500',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateUniqueCode($validated['name']);
        }

        $validated['created_by'] = auth()->id();

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruangan \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function show(Room $room)
    {
        $room->load('creator');
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => "nullable|string|max:20|unique:rooms,code,{$room->id}",
            'description' => 'nullable|string|max:500',
        ]);

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateUniqueCode($validated['name'], $room->id);
        }

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruangan \"{$room->name}\" berhasil diperbarui.");
    }

    public function destroy(Room $room)
    {
        $name = $room->name;
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', "Ruangan \"{$name}\" berhasil dihapus.");
    }

    public function toggleActive(Room $room)
    {
        $room->update(['is_active' => !$room->is_active]);
        $status = $room->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Ruangan \"{$room->name}\" berhasil {$status}.");
    }

    private function generateUniqueCode(string $name, ?int $excludeId = null): string
    {
        $base = collect(explode(' ', $name))
            ->map(fn($w) => strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $w), 0, 3)))
            ->filter()
            ->join('-');

        $base = substr($base, 0, 20) ?: 'RMG';
        $code = $base;
        $i    = 2;

        while (
            Room::where('code', $code)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $suffix = '-' . $i++;
            $code   = substr($base, 0, 20 - strlen($suffix)) . $suffix;
        }

        return $code;
    }
}