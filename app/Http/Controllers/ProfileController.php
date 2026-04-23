<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'gender'  => 'nullable|in:L,P',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only(['name', 'phone', 'jabatan', 'gender', 'address']));

        if ($request->hasFile('avatar')) {
            $request->validate(['avatar' => 'image|max:2048']);
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required',
                'password'         => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }

            $user->update(['password' => Hash::make($request->password)]);
        }

        \App\Models\AuditLog::record('profile_updated', 'User', $user->id);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
