<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = session('auth_user', []);

        return view('profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = session('auth_user', []);

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $profile = session('auth_user', []);

        $profile['email'] = $data['email'];
        $profile['phone'] = $data['phone'];

        session(['auth_user' => $profile]);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Kontak berhasil diperbarui.');
    }
}
