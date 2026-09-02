<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $storageUsed = $user->documents()->sum('file_size');
        $storageLimit = 5 * 1024 * 1024 * 1024; // 5 GB in bytes
        $storagePercent = $storageLimit > 0 ? round(($storageUsed / $storageLimit) * 100) : 0;

        $documents = $user->documents()->selectRaw('file_type, SUM(file_size) as total')->groupBy('file_type')->get();

        return view('settings.index', compact('user', 'storageUsed', 'storageLimit', 'storagePercent', 'documents'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->name = trim($data['first_name'] . ' ' . $data['last_name']);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if any
            if ($user->hasAvatar()) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        }

        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->password = $data['password'];
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
