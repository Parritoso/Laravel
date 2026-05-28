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

    public function getTwoFactorQr()
    {
        $user = auth()->user();

        if (!$user->two_factor_secret) {
            return response()->json(['error' => '2FA no inicializado'], 400);
        }

        return response()->json([
            'svg' => $user->twoFactorQrCodeSvg(),
            'secret' => decrypt($user->two_factor_secret)
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'language' => 'required|in:es,en,pt,ja',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
        ]);

        // El onboarding recoge solo preferencias iniciales. El usuario puede completar
        // o corregir la dirección más adelante desde su perfil.
        $user->update([
            'language' => $request->language,
            'address' => $request->address,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
        ]);

        return redirect()->route('home')->with('success', __('messages.onboarding_completed'));
    }
}
