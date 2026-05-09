<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load('direcciones');
        return view('auth.profile.show', compact('user'));
    }
    public function edit()
    {
        return view('auth.profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'language' => 'required|in:es,en,pt,ja',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
        ]);

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Perfil actualizado con éxito.');
    }

    public function editPassword(){
        return view('auth.profile.update-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'  => ['required', 'current_password'],
            'password'          => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Re-autenticar para que la sesión no quede inválida con el nuevo hash
        Auth::login($request->user());

        return redirect()->route('profile.password.edit')->with('status', 'password-updated');
    }
}