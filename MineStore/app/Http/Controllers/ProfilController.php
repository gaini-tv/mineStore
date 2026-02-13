<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    /**
     * Affiche la page de profil
     */
    public function index()
    {
        return response()
            ->view('profil.index')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Met à jour le profil de l'utilisateur
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|string|max:255',
        ]);

        $avatar = isset($validated['avatar']) && $validated['avatar'] !== '' ? $validated['avatar'] : $user->avatar;

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'avatar' => $avatar,
        ]);

        // Recharger l'utilisateur depuis la BDD et mettre à jour la session
        $user->refresh();
        Auth::setUser($user);
        request()->session()->save();

        return redirect()
            ->route('profil.index', ['_t' => now()->timestamp])
            ->with('success', 'Profil mis à jour avec succès !')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Met à jour uniquement l'avatar (appel AJAX, pas de redirection)
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'avatar' => 'required|string|max:255',
        ]);

        $user->update(['avatar' => $validated['avatar']]);
        $user->refresh();
        Auth::setUser($user);
        request()->session()->save();

        $avatarUrl = asset('images/avatar/' . $user->avatar) . '?v=' . $user->updated_at->timestamp;

        return response()->json([
            'success' => true,
            'avatar' => $user->avatar,
            'avatar_url' => $avatarUrl,
        ]);
    }

    /**
     * Met à jour le mot de passe de l'utilisateur
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Vérifier le mot de passe actuel
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profil.index')->with('success', 'Mot de passe modifié avec succès !');
    }
}
