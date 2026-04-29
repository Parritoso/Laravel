<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function index()
    {
        return view('auth.onboarding');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validamos los datos
        $request->validate([
            'language' => 'required|in:es,en,pt,ja',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
        ]);

        // Guardamos en la base de datos (incluyendo el nuevo campo language)
        $user->update([
            'language' => $request->language,
            'address' => $request->address, // Asegúrate de tener estos campos en tu migración o usarlos según tu lógica
            'city' => $request->city,
            'zip_code' => $request->zip_code,
        ]);

        // Una vez completado, al Home
        return redirect()->route('home')->with('success', '¡Perfil configurado correctamente!');
    }
}
