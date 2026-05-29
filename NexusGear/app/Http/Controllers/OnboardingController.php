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

        $data = $request->validate([
            'language' => ['required', 'in:es,en,pt,ja'],
            'address' => ['nullable', 'required_with:number,city,zip_code', 'string', 'max:255'],
            'number' => ['nullable', 'required_with:address,city,zip_code', 'string', 'max:20'],
            'city' => ['nullable', 'required_with:address,number,zip_code', 'string', 'max:100'],
            'zip_code' => ['nullable', 'required_with:address,number,city', 'string', 'max:10'],
        ]);

        $user->update([
            'language' => $data['language'],
        ]);

        if ($request->filled('address')) {
            $user->direcciones()->create([
                'calle' => $data['address'],
                'numero' => $data['number'],
                'ciudad' => $data['city'],
                'codigo_postal' => $data['zip_code'],
                'es_predeterminada' => ! $user->direcciones()->exists(),
            ]);
        }

        return redirect()->route('home')->with('success', __('messages.onboarding_completed'));
    }
}
