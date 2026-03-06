<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AccountConfirmation;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traite la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user && is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Veuillez confirmer votre adresse email avant de vous connecter.',
                ])->withInput($request->only('email'));
            }

            if ($user) {
                $user->last_login_at = now();
                $user->save();
            }

            return redirect()->intended(route('profil.index'))->with('success', 'Connexion réussie !');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis sont incorrects.',
        ])->withInput($request->only('email'));
    }

    /**
     * Affiche le formulaire d'inscription
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date|before_or_equal:' . now()->subYears(16)->toDateString(),
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'date_naissance' => $validated['date_naissance'],
            'date_inscription' => now(),
            'avatar' => 'base.png',
        ]);

        $user->verification_token = Str::random(60);
        $user->save();

        try {
            Mail::to($user->email)->send(new AccountConfirmation($user));
        } catch (\Throwable $e) {
        }

        return redirect()->route('profil.index')->with('success', 'Inscription réussie ! Vérifiez vos emails pour confirmer votre compte.');
    }

    public function verifyEmail(string $token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect()->route('profil.index')->with('success', 'Votre adresse email a été confirmée. Vous pouvez maintenant vous connecter.');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
