@extends('layouts.app')

@section('title', 'Mon profil')

@push('styles')
<style>
    .profil-field-group { margin-top: 10px; margin-bottom: 10px; }
    .profil-alert-container {
        max-width: 40rem;
        margin: 0 auto 1.5rem auto;
        padding: 0 1rem;
    }
    .profil-alert {
        position: relative;
        padding: 0.75rem 2.5rem 0.75rem 1rem;
        border-radius: 0.375rem;
        font-family: 'Minecrafter Alt', sans-serif;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .profil-alert-success {
        background-color: #dcfce7;
        border: 1px solid #22c55e;
        color: #166534;
    }
    .profil-alert-error {
        background-color: #fee2e2;
        border: 1px solid #ef4444;
        color: #991b1b;
    }
    .profil-alert-close {
        position: absolute;
        top: 0.5rem;
        right: 0.75rem;
        background: transparent;
        border: none;
        font-size: 1.25rem;
        line-height: 1;
        cursor: pointer;
        color: inherit;
    }
</style>
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8" style="padding-top: 200px; margin-top: 20px; margin-bottom: 20px;">
        @if(session('success') || session('error'))
            <div class="profil-alert-container">
                @if(session('success'))
                    <div class="profil-alert profil-alert-success" id="profil-alert">
                        <span>{{ session('success') }}</span>
                        <button type="button" class="profil-alert-close" onclick="document.getElementById('profil-alert').style.display='none'">×</button>
                    </div>
                @elseif(session('error'))
                    <div class="profil-alert profil-alert-error" id="profil-alert">
                        <span>{{ session('error') }}</span>
                        <button type="button" class="profil-alert-close" onclick="document.getElementById('profil-alert').style.display='none'">×</button>
                    </div>
                @endif
            </div>
        @endif

        @auth
            {{-- Contenu pour utilisateur connecté --}}
            <div class="max-w-4xl mx-auto">
                {{-- Modal de sélection d'avatar --}}
                <div id="avatar-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto" style="padding: 20px;">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-[#1b1b18]" style="font-family: 'Minecrafter Alt', sans-serif;">Choisir un avatar</h2>
                            <button onclick="document.getElementById('avatar-modal').classList.add('hidden')" class="cursor-pointer">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px;">
                            @php
                                $avatarFiles = [
                                    'base.png',
                                    'Plan de travail 1 copie 2.png',
                                    'Plan de travail 1 copie 3.png',
                                    'Plan de travail 1 copie 4.png',
                                    'Plan de travail 1 copie 5.png',
                                    'Plan de travail 1 copie 6-1.png',
                                    'Plan de travail 1 copie 6-2.png',
                                    'Plan de travail 1 copie 6-3.png',
                                    'Plan de travail 1 copie 6-4.png',
                                    'Plan de travail 1 copie 6-5.png',
                                    'Plan de travail 1 copie 6.png',
                                    'Plan de travail 1 copie 7-1.png',
                                    'Plan de travail 1 copie 7-2.png',
                                    'Plan de travail 1 copie 7-3.png',
                                    'Plan de travail 1 copie 7-4.png',
                                    'Plan de travail 1 copie 7-5.png',
                                    'Plan de travail 1 copie 7.png',
                                    'Plan de travail 1 copie 8-1.png',
                                    'Plan de travail 1 copie 8-2.png',
                                    'Plan de travail 1 copie 8-3.png',
                                    'Plan de travail 1 copie 8-4.png',
                                    'Plan de travail 1 copie 8-5.png',
                                    'Plan de travail 1 copie 8.png',
                                    'Plan de travail 1 copie 9-1.png',
                                    'Plan de travail 1 copie 9-2.png',
                                    'Plan de travail 1 copie 9-3.png',
                                    'Plan de travail 1 copie 9-4.png',
                                    'Plan de travail 1 copie 9.png',
                                    'Plan de travail 1 copie.png',
                                    'Plan de travail 1.png',
                                ];
                                $userAvatar = auth()->user()->avatar ?? 'base.png';
                            @endphp
                            @foreach($avatarFiles as $index => $avatarFile)
                                <div class="avatar-option cursor-pointer rounded-lg p-1 transition-all {{ $userAvatar == $avatarFile ? 'border-2 border-[#5baa47]' : '' }}"
                                     data-avatar="{{ $avatarFile }}"
                                     style="width: 100%; aspect-ratio: 1/1; {{ $userAvatar == $avatarFile ? 'border: 2px solid #5baa47;' : 'border: none;' }}">
                                    <div class="w-full h-full overflow-hidden rounded" style="width: 100%; height: 100%;">
                                        <img src="{{ asset('images/avatar/' . $avatarFile) }}" alt="Avatar {{ $index + 1 }}" class="w-full h-full object-contain">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-end mt-6">
                            <button type="button" id="avatar-save-btn" class="relative" style="display: inline-block; width: 200px;">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <span class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 text-white font-bold text-base md:text-lg pointer-events-none" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                    Enregistrer
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Modal de modification du mot de passe --}}
                <div id="password-modal" class="hidden modal-form-backdrop">
                    <div class="modal-form-container" style="max-width: 600px;">
                        <div class="modal-form-header">
                            <h2 class="modal-form-title">Modifier mon mot de passe</h2>
                            <button onclick="document.getElementById('password-modal').classList.add('hidden')" class="modal-form-close-button">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <form method="POST" action="{{ route('profil.updatePassword') }}" class="space-y-4">
                            @csrf
                            
                            <div class="profil-field-group">
                                <label class="modal-form-label">Mot de passe actuel</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="password" 
                                           name="current_password" 
                                           required 
                                           class="modal-form-input"
                                           style="border: none;"
                                           placeholder="Votre mot de passe actuel">
                                </div>
                            </div>
                            
                            <div class="profil-field-group">
                                <label class="modal-form-label">Nouveau mot de passe</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="password" 
                                           name="password" 
                                           required 
                                           minlength="8"
                                           class="modal-form-input"
                                           style="border: none;"
                                           placeholder="Nouveau mot de passe">
                                </div>
                            </div>
                            
                            <div class="profil-field-group">
                                <label class="modal-form-label">Confirmer le nouveau mot de passe</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           required 
                                           minlength="8"
                                           class="modal-form-input"
                                           style="border: none;"
                                           placeholder="Confirmer le nouveau mot de passe">
                                </div>
                            </div>
                            
                            @error('current_password')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                            @error('password')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                            
                            <div class="flex justify-end gap-4 mt-4" style="display: flex; align-items: center; justify-content: center; align-content: center; flex-wrap: nowrap; flex-direction: column;">
                                <button type="button" 
                                        onclick="document.getElementById('password-modal').classList.add('hidden')" 
                                        class="px-6 py-2 bg-gray-200 text-[#1b1b18] rounded-lg font-bold hover:bg-gray-300 transition-colors" 
                                        style="font-family: 'Minecrafter Alt', sans-serif;">
                                    Annuler
                                </button>
                                <div class="relative" style="display: inline-block; width: 200px;">
                                    <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                    <button type="submit"
                                            class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                            style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                        <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                            Modifier
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Section principale avec flex : Avatar/Nom à gauche, Informations à droite --}}
                <div id="entreprise-modal" class="hidden modal-form-backdrop">
                    <div class="modal-form-container">
                        <div class="modal-form-header">
                            <h2 class="modal-form-title">Demande de création d’entreprise</h2>
                            <button onclick="document.getElementById('entreprise-modal').classList.add('hidden')" class="modal-form-close-button">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <form method="POST" action="{{ route('entreprises.store') }}" class="space-y-4">
                            @csrf
                            <div class="profil-field-group">
                                <label class="modal-form-label">Nom de l’entreprise</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="nom" required class="modal-form-input" style="border: none;">
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Description</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="description" rows="3" class="modal-form-textarea" style="border: none;"></textarea>
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Email de contact</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="email" name="email_contact" required class="modal-form-input" style="border: none;">
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Téléphone</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="telephone" required class="modal-form-input" style="border: none;">
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Adresse</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="adresse" rows="2" required class="modal-form-textarea" style="border: none;"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end mt-4">
                                <div class="relative" style="display: inline-block; width: 200px;">
                                    <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                    <button type="submit" class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200" style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                        <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Envoyer la demande</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="width: 100%; display: flex; flex-direction: row; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center; margin-bottom: 24px;">
                    {{-- Section gauche : Avatar et nom --}}
                    <div style="width: 50%; display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; align-items: center;">
                        <div class="relative">
                            @php
                                $avatarFile = auth()->user()->avatar ?? 'base.png';
                                $currentAvatar = asset('images/avatar/' . $avatarFile);
                                $currentAvatar .= (auth()->user()->updated_at ? '?v=' . auth()->user()->updated_at->timestamp : '');
                            @endphp
                            <img src="{{ $currentAvatar }}" 
                                 alt="Avatar" 
                                 id="current-avatar"
                                 class="w-20 h-20 rounded-full border-4 border-[#5baa47] cursor-pointer hover:opacity-80 transition-opacity"
                                 style="object-fit: cover;"
                                 onclick="document.getElementById('avatar-modal').classList.remove('hidden')">
                            <button onclick="document.getElementById('avatar-modal').classList.remove('hidden')" 
                                    class="absolute bottom-0 right-0 bg-[#5baa47] text-white rounded-full p-1 border-2 border-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                        </div>
                        @php
                            $role = auth()->user()->role ?? 'user';
                            $roleLabels = [
                                'user' => 'Utilisateur',
                                'admin' => 'Administrateur',
                                'owner' => 'Propriétaire',
                                'manager' => 'Directeur',
                                'product_manager' => 'Responsable Produit',
                                'stock_manager' => 'Responsable Stock',
                                'editor' => 'Rédacteur',
                            ];
                            $roleColors = [
                                'user' => '#5baa47',
                                'admin' => '#e63946',
                                'owner' => '#457b9d',
                                'manager' => '#1d3557',
                                'product_manager' => '#a8dadc',
                                'stock_manager' => '#ffb703',
                                'editor' => '#8d99ae',
                            ];
                            $roleLabel = $roleLabels[$role] ?? $role;
                            $roleColor = $roleColors[$role] ?? '#5baa47';
                        @endphp
                        <div class="mt-4 flex items-center gap-3">
                            <h1 class="text-3xl font-bold text-[#1b1b18]" style="font-family: 'Minecrafter Alt', sans-serif;">
                                {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                            </h1>
                            <span
                                class="px-3 py-1 text-white text-sm rounded"
                                style="background-color: {{ $roleColor }}; font-family: 'Minecrafter Alt', sans-serif;">
                                {{ $roleLabel }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Section droite : Mes informations --}}
                    <div style="width: 50%;">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-2xl font-bold text-[#1b1b18] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">Mes informations</h2>
                            
                            <form method="POST" action="{{ route('profil.update') }}" class="space-y-4" style="margin-bottom: 20px;">
                                @csrf
                                <input type="hidden" name="avatar" id="selected-avatar" value="{{ auth()->user()->avatar ?? 'base.png' }}">
                                
                                <div class="profil-field-group">
                                    <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Prénom</label>
                                    <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                        <input type="text" 
                                               name="prenom" 
                                               value="{{ auth()->user()->prenom }}"
                                               required 
                                               class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                               style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                                    </div>
                                </div>
                                
                                <div class="profil-field-group">
                                    <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Nom</label>
                                    <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                        <input type="text" 
                                               name="nom" 
                                               value="{{ auth()->user()->nom }}"
                                               required 
                                               class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                               style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                                    </div>
                                </div>
                                
                                <div class="profil-field-group">
                                    <label class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Email</label>
                                    <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                        <input type="email" 
                                               name="email" 
                                               value="{{ auth()->user()->email }}"
                                               required 
                                               class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                               style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none; color: white;">
                                    </div>
                                </div>
                                
                                @error('nom')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                @error('email')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                
                                <div style="display: flex; gap: 20px; justify-content: center; align-items: center;">
                                    <div class="relative" style="display: inline-block; width: 200px;">
                                        <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                        <button type="submit"
                                                class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                                style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                            <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                                Enregistrer
                                            </span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <div class="relative" style="display: inline-block; width: 200px;">
                                            <img src="{{ asset('images/btnESP.png') }}" alt="" class="w-full h-auto block">
                                            <button type="submit"
                                                    class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                                    style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                                <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                                    Déconnexion
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </form>
                            
                            {{-- Lien pour modifier le mot de passe --}}
                            <div style="margin-top: 20px; text-align: center;">
                                <a href="#" onclick="document.getElementById('password-modal').classList.remove('hidden'); return false;" 
                                   class="text-[#5baa47] hover:underline font-bold" 
                                   style="font-family: 'Minecrafter Alt', sans-serif; cursor: pointer;">
                                    Modifier mon mot de passe
                                </a>
                            </div>
                        </div>
                        @php
                            $user = auth()->user();
                            $userEntreprise = $user?->entreprise;
                            $pendingEntreprise = null;
                            if ($user && !$userEntreprise) {
                                $pendingEntreprise = \App\Models\Entreprise::where('user_id', $user->id)->where('statut', 'pending')->first();
                            }
                        @endphp
                        @if($user && $user->role === 'user')
                            @if(!$userEntreprise)
                                <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                                    <h2 class="text-2xl font-bold text-[#1b1b18] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">Créer une entreprise</h2>
                                    <p class="text-[#1b1b18] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">Créer une entreprise vous permet de vendre sur notre site vos propres produits.</p>
                                    <div class="relative" style="display: inline-block; width: 280px;">
                                        <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                        @if($pendingEntreprise)
                                            <div class="absolute inset-0 w-full h-full flex items-center justify-center" style="pointer-events: none; opacity: 0.6;">
                                                <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                                                    Demande en cours...
                                                </span>
                                            </div>
                                        @else
                                            <button onclick="document.getElementById('entreprise-modal').classList.remove('hidden')" class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200" style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                                <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                                                    Faire une demande
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                    @if($pendingEntreprise)
                                        <p class="text-[#706f6c] mt-2" style="font-family: 'Minecrafter Alt', sans-serif;">
                                            Votre demande est en attente de validation par l’administration.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        @elseif($user && $user->role !== 'admin' && $userEntreprise)
                            <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                                <h2 class="text-2xl font-bold text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Mon entreprise</h2>
                                <p class="text-[#1b1b18] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">{{ $userEntreprise->nom }}</p>
                                <div class="relative" style="display: inline-block; width: 280px;">
                                    <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                    <a href="{{ route('entreprise.index') }}" class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200" style="background: transparent; border: none; cursor: pointer; padding: 0; text-decoration: none;">
                                        <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">Gérer l’entreprise</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Section Mes commandes séparée --}}
                <div style="width: 100%; margin-top: 24px;">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-[#1b1b18] mb-4" style="font-family: 'Minecrafter Alt', sans-serif;">Mes commandes</h2>
                        <div class="text-center py-8">
                            <p class="text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">Aucune commande pour le moment.</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Formulaires pour utilisateur non connecté --}}
            <div class="flex items-center justify-center min-h-[60vh]">
                <div class="w-[500px]">
                    {{-- Formulaire de connexion --}}
                    <div id="login-form" class="bg-white rounded-lg p-[100px]">
                        <h2 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center" style="margin-bottom: 20px; font-family: 'Minecrafter Alt', sans-serif;">Connexion</h2>
                        
                        @if ($errors->has('email') && !$errors->has('name'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login.post') }}" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; row-gap: 20px;">
                            @csrf
                            
                            <div class="mb-4 profil-field-group">
                                <label for="login-email" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Email</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="email" 
                                           id="login-email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre email">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="login-password" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Mot de passe</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="password" 
                                           id="login-password" 
                                           name="password" 
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre mot de passe">
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="mr-2">
                                    <span class="text-sm text-[#706f6c]" style="font-family: 'Minecrafter Alt', sans-serif;">Se souvenir de moi</span>
                                </label>
                            </div>
                            
                            <div class="relative mx-auto" style="display: inline-block; width: 200px;">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                        style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                    <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                        Se connecter
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-[20px] text-[#706f6c] text-sm" style="margin-top: 20px; font-family: 'Minecrafter Alt', sans-serif;">
                            Pas encore de compte ? 
                            <a href="#" id="show-register" class="text-[#5baa47] hover:underline font-bold">S'inscrire</a>
                        </p>
                    </div>
                    
                    {{-- Formulaire d'inscription --}}
                    <div id="register-form" class="bg-white rounded-lg p-[100px] hidden">
                        <h2 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center" style="margin-bottom: 20px; font-family: 'Minecrafter Alt', sans-serif;">Inscription</h2>
                        
                        @if ($errors->has('nom') || ($errors->has('email') && $errors->has('nom')) || $errors->has('password'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('register.post') }}" style="display: flex; flex-direction: column; flex-wrap: nowrap; align-content: center; justify-content: center; row-gap: 20px;">
                            @csrf
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-firstname" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Prénom</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="text" 
                                           id="register-firstname" 
                                           name="prenom" 
                                           value="{{ old('prenom') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre prénom">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-name" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Nom</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="text" 
                                           id="register-name" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre nom">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-email" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Email</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="email" 
                                           id="register-email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre email">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-password" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Mot de passe</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="password" 
                                           id="register-password" 
                                           name="password" 
                                           required 
                                           minlength="8"
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Votre mot de passe">
                                </div>
                                <p class="text-xs text-[#706f6c] mt-1" style="font-family: 'Minecrafter Alt', sans-serif;">Minimum 8 caractères</p>
                            </div>
                            
                            <div class="mb-6 profil-field-group">
                                <label for="register-password-confirm" class="block text-sm font-medium text-[#1b1b18] mb-2" style="font-family: 'Minecrafter Alt', sans-serif;">Confirmer le mot de passe</label>
                                <div class="p-2" style="background-image: url('{{ asset('images/searchbar.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat; border: none; border-radius: 0;">
                                    <input type="password" 
                                           id="register-password-confirm" 
                                           name="password_confirmation" 
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none"
                                           style="font-family: 'Minecrafter Alt', sans-serif; border-radius: 0; border: none;"
                                           placeholder="Confirmez votre mot de passe">
                                </div>
                            </div>
                            
                            <div class="relative mx-auto" style="display: inline-block; width: 200px;">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200"
                                        style="background: transparent; border: none; cursor: pointer; padding: 0;">
                                    <span class="text-white font-bold text-base md:text-lg" style="font-family: 'Minecrafter Alt', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
                                        S'inscrire
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-[20px] text-[#706f6c] text-sm" style="margin-top: 20px; font-family: 'Minecrafter Alt', sans-serif;">
                            Déjà un compte ? 
                            <a href="#" id="show-login" class="text-[#5baa47] hover:underline font-bold">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        @endauth
    </div>
    
    @push('scripts')
    <script>
        // Éviter que le navigateur réaffiche l’ancienne page en cache après enregistrement (avatar, etc.)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Basculer entre connexion et inscription
        document.getElementById('show-register')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        });
        
        document.getElementById('show-login')?.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-form').classList.remove('hidden');
        });
        
        // Afficher le formulaire d'inscription si des erreurs d'inscription sont présentes
        @if ($errors->has('name') || ($errors->has('email') && $errors->has('name')) || $errors->has('password'))
            document.getElementById('login-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        @endif
        
        // Gestion de la sélection d'avatar
        @auth
        let selectedAvatar = '{{ auth()->user()->avatar ?? "base.png" }}';
        const avatarOptions = document.querySelectorAll('.avatar-option');
        const selectedAvatarInput = document.getElementById('selected-avatar');
        const currentAvatarImg = document.getElementById('current-avatar');
        
        // Marquer l'avatar actuel comme sélectionné
        if (selectedAvatar) {
            avatarOptions.forEach(option => {
                if (option.getAttribute('data-avatar') === selectedAvatar) {
                    option.classList.add('border-[#5baa47]');
                    option.style.border = '2px solid #5baa47';
                } else {
                    option.style.border = 'none';
                }
            });
        } else {
            // Aucun avatar sélectionné, retirer tous les borders
            avatarOptions.forEach(option => {
                option.style.border = 'none';
            });
        }
        
        avatarOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Retirer la sélection précédente
                avatarOptions.forEach(opt => {
                    opt.classList.remove('border-[#5baa47]');
                    opt.style.border = 'none';
                });
                // Sélectionner le nouvel avatar (sans soumettre)
                this.classList.add('border-[#5baa47]');
                this.style.border = '2px solid #5baa47';
                selectedAvatar = this.getAttribute('data-avatar');
                selectedAvatarInput.value = selectedAvatar;
                // Mettre à jour l'aperçu dans la modal uniquement
                if (currentAvatarImg) {
                    currentAvatarImg.src = '{{ asset("images/avatar") }}/' + selectedAvatar;
                }
            });
        });

        // Bouton Enregistrer dans le popup avatar : envoi AJAX (pas de redirection = pas de bug de cache)
        document.getElementById('avatar-save-btn')?.addEventListener('click', function() {
            const btn = this;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) return;
            btn.disabled = true;
            fetch('{{ route("profil.updateAvatar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ avatar: selectedAvatar, _token: token })
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success && data.avatar_url) {
                    selectedAvatarInput.value = data.avatar;
                    if (currentAvatarImg) currentAvatarImg.src = data.avatar_url;
                    var navAvatar = document.getElementById('navbar-avatar');
                    if (navAvatar) navAvatar.src = data.avatar_url;
                    document.getElementById('avatar-modal').classList.add('hidden');
                }
            })
            .catch(function() { })
            .finally(function() { btn.disabled = false; });
        });
        
        // Fermer la modal en cliquant sur le fond
        document.getElementById('avatar-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Fermer la modal de mot de passe en cliquant sur le fond
        document.getElementById('password-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        // Fermer la modal d’entreprise en cliquant sur le fond
        document.getElementById('entreprise-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        @endauth

        setTimeout(function () {
            var alert = document.getElementById('profil-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.4s';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.style.display = 'none';
                }, 400);
            }
        }, 4000);
    </script>
    @endpush
@endsection
