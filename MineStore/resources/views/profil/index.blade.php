@extends('layouts.app')

@section('title', 'Mon profil')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    <div class="profil-banner">
        <img src="{{ asset('images/banierP.png') }}" alt="Profil" class="profil-banner-image">
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <h1 class="profil-banner-title">
                Mon profil
            </h1>
        </div>
    </div>

    @if(session('success') || session('error'))
        <div class="container mx-auto px-4 py-4 profil-page-container">
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
        </div>
    @endif

    @auth
        <div class="container mx-auto px-4 py-8 profil-page-container profil-layout">
            {{-- Contenu pour utilisateur connecté --}}

            {{-- Modal de sélection d'avatar --}}
                <div id="avatar-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-4xl w-full max-h-[90vh] overflow-y-auto profil-avatar-modal-container">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-[#1b1b18] profil-avatar-title">Choisir un avatar</h2>
                            <button onclick="document.getElementById('avatar-modal').classList.add('hidden')" class="cursor-pointer">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <div class="profil-avatar-grid">
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
                                <div class="avatar-option cursor-pointer rounded-lg p-1 transition-all profil-avatar-option {{ $userAvatar == $avatarFile ? 'profil-avatar-option-selected' : '' }}"
                                     data-avatar="{{ $avatarFile }}">
                                    <div class="w-full h-full overflow-hidden rounded profil-avatar-option-inner">
                                        <img src="{{ asset('images/avatar/' . $avatarFile) }}" alt="Avatar {{ $index + 1 }}" class="w-full h-full object-contain">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-end mt-6">
                            <button type="button" id="avatar-save-btn" class="relative profil-btn-wrapper-sm">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <span class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 text-white font-bold text-base md:text-lg pointer-events-none profil-btn-text">
                                    Enregistrer
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

            {{-- Modal de modification du mot de passe --}}
                <div id="password-modal" class="hidden modal-form-backdrop">
                    <div class="modal-form-container profil-password-modal-container">
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
                                           class="modal-form-input profil-modal-input-borderless"
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
                                           class="modal-form-input profil-modal-input-borderless"
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
                                           class="modal-form-input profil-modal-input-borderless"
                                           placeholder="Confirmer le nouveau mot de passe">
                                </div>
                            </div>
                            
                            @error('current_password')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                            @error('password')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                            
                            <div class="flex flex-col items-center justify-center gap-4 mt-4">
                                <button type="button" 
                                        onclick="document.getElementById('password-modal').classList.add('hidden')" 
                                        class="px-6 py-2 bg-gray-200 text-[#1b1b18] rounded-lg font-bold hover:bg-gray-300 transition-colors profil-minecrafter-text">
                                    Annuler
                                </button>
                                <div class="relative profil-btn-wrapper-sm">
                                    <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                    <button type="submit"
                                            class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button">
                                        <span class="text-white font-bold text-base md:text-lg profil-btn-text">
                                            Modifier
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Section principale avec grille : Avatar/Nom en haut à gauche, Infos en dessous --}}
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
                                    <input type="text" name="nom" required class="modal-form-input profil-modal-input-borderless">
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Description</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="description" rows="3" class="modal-form-textarea profil-modal-input-borderless"></textarea>
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Email de contact</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="email" name="email_contact" required class="modal-form-input profil-modal-input-borderless">
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Téléphone</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="telephone" required class="modal-form-input profil-modal-input-borderless">
                                </div>
                            </div>
                            <div class="profil-field-group">
                                <label class="modal-form-label">Adresse</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="adresse" rows="2" required class="modal-form-textarea profil-modal-input-borderless"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end mt-4">
                                <div class="relative profil-btn-wrapper-sm">
                                    <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                    <button type="submit" class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button">
                                        <span class="text-white font-bold text-base md:text-lg profil-btn-text">Envoyer la demande</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="profil-grid">

                    <div class="profil-main-left profil-grid-avatar">
                    <div class="relative">
                        @php
                            $avatarFile = auth()->user()->avatar ?? 'base.png';
                            $currentAvatar = asset('images/avatar/' . $avatarFile);
                            $currentAvatar .= (auth()->user()->updated_at ? '?v=' . auth()->user()->updated_at->timestamp : '');
                        @endphp
                        <img src="{{ $currentAvatar }}"
                             alt="Avatar"
                             id="current-avatar"
                             class="w-20 h-20 rounded-full border-4 border-[#5baa47] cursor-pointer hover:opacity-80 transition-opacity profil-avatar-image"
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
                        $roleLabel = $roleLabels[$role] ?? $role;
                    @endphp
                    <div class="mt-4 flex items-center gap-3 profil-name-row">
                        <h1 class="text-3xl font-bold text-[#1b1b18] profil-minecrafter-text">
                            {{ auth()->user()->prenom }} {{ auth()->user()->nom }}
                        </h1>
                        <span
                            class="px-3 py-1 text-white text-sm rounded profil-role-badge profil-role-{{ $role }}">
                            {{ $roleLabel }}
                        </span>
                    </div>
                    </div>
                
                    <div class="profil-main-right profil-grid-infos">
                    <div class="bg-white rounded-lg shadow-md p-6">
                            <h2 class="text-2xl font-bold text-[#1b1b18] mb-4 profil-minecrafter-text">Mes informations</h2>
                            
                            <form method="POST" action="{{ route('profil.update') }}" id="profil-update-form" class="space-y-4 mb-5">
                                @csrf
                                <input type="hidden" name="avatar" id="selected-avatar" value="{{ auth()->user()->avatar ?? 'base.png' }}">
                                
                                <div class="profil-field-group">
                                    <label class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Prénom</label>
                                    <div class="p-2 profil-input-wrapper">
                                        <input type="text" 
                                               name="prenom" 
                                               value="{{ auth()->user()->prenom }}"
                                               required 
                                               class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control">
                                    </div>
                                </div>
                                
                                <div class="profil-field-group">
                                    <label class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Nom</label>
                                    <div class="p-2 profil-input-wrapper">
                                        <input type="text" 
                                               name="nom" 
                                               value="{{ auth()->user()->nom }}"
                                               required 
                                               class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control">
                                    </div>
                                </div>
                                
                                <div class="profil-field-group">
                                    <label class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Email</label>
                                    <div class="p-2 profil-input-wrapper">
                                        <input type="email" 
                                               name="email" 
                                               value="{{ auth()->user()->email }}"
                                               required 
                                               class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control">
                                    </div>
                                </div>
                                
                                @error('nom')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                @error('email')
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                                
                            </form>
                            <div class="profil-actions-row">
                                <button type="button" class="relative profil-btn-wrapper-sm profil-scale-110" onclick="document.getElementById('profil-update-form').submit();">
                                    <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                    <span class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 text-white font-bold text-base md:text-lg pointer-events-none profil-btn-text">
                                        Enregistrer
                                    </span>
                                </button>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <div class="relative profil-btn-wrapper-sm profil-scale-110">
                                        <img src="{{ asset('images/btnESP.png') }}" alt="" class="w-full h-auto block">
                                        <button type="submit"
                                                class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button">
                                            <span class="text-white font-bold text-base md:text-lg profil-btn-text">
                                                Déconnexion
                                            </span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            {{-- Lien pour modifier le mot de passe --}}
                            <div class="mt-5 text-center">
                                <a href="#" onclick="document.getElementById('password-modal').classList.remove('hidden'); return false;" 
                                   class="text-[#5baa47] hover:underline font-bold profil-link-text">
                                    Modifier mon mot de passe
                                </a>
                            </div>
                        </div>
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
                @if(($user && $user->role === 'user') || ($user && $user->role !== 'admin' && $userEntreprise))
                    <div class="bg-white rounded-lg shadow-md p-6 mt-8 profil-grid-entreprise">
                        @if($user && $user->role === 'user' && !$userEntreprise)
                            <h2 class="text-2xl font-bold text-[#1b1b18] mb-4 profil-minecrafter-text">Créer une entreprise</h2>
                            <p class="text-[#1b1b18] mb-4 profil-minecrafter-text">Créer une entreprise vous permet de vendre sur notre site vos propres produits.</p>
                            <div class="relative profil-btn-wrapper-md profil-scale-110">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                @if($pendingEntreprise)
                                    <div class="absolute inset-0 w-full h-full flex items-center justify-center profil-overlay-disabled">
                                        <span class="text-white font-bold text-base md:text-lg profil-btn-text">
                                            Demande en cours...
                                        </span>
                                    </div>
                                @else
                                    <button onclick="document.getElementById('entreprise-modal').classList.remove('hidden')" class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button">
                                        <span class="text-white font-bold text-base md:text-lg profil-btn-text">
                                            Faire une demande
                                        </span>
                                    </button>
                                @endif
                            </div>
                            @if($pendingEntreprise)
                                <p class="text-[#706f6c] mt-2 profil-helper-text">
                                    Votre demande est en attente de validation par l’administration.
                                </p>
                            @endif
                        @elseif($user && $user->role !== 'admin' && $userEntreprise)
                            <h2 class="text-2xl font-bold text-[#1b1b18] mb-2 profil-minecrafter-text">Mon entreprise</h2>
                            <p class="text-[#1b1b18] mb-4 profil-minecrafter-text">{{ $userEntreprise->nom }}</p>
                            <div class="relative profil-btn-wrapper-md">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <a href="{{ route('entreprise.index') }}" class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button profil-link-no-underline">
                                    <span class="text-white font-bold text-base md:text-lg profil-btn-text">Gérer l’entreprise</span>
                                </a>
                            </div>
                        @endif
                    </div>
                    
                @endif

                <div class="profil-orders-section">
                    <div class="bg-white rounded-lg shadow-md p-6 profil-orders-card">
                        <h2 class="text-2xl font-bold text-[#1b1b18] mb-4 profil-minecrafter-text">Mes commandes</h2>
                        @if(!empty($userCommandes) && count($userCommandes) > 0)
                            <div class="profil-table-wrapper">
                                <table class="profil-table" id="profil-orders-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Statut</th>
                                            <th>Total TTC</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userCommandes as $commande)
                                            <tr class="profil-table-row"
                                                data-commande-id="{{ $commande->id_commande }}">
                                                <td>{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}</td>
                                                <td>{{ ucfirst($commande->statut) }}</td>
                                                <td>{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                                                <td>
                                                    <div class="profil-table-actions">
                                                        <button
                                                            type="button"
                                                            class="profil-icon-button profil-icon-button-view"
                                                            onclick="event.stopPropagation(); openOrderModal({{ $commande->id_commande }});"
                                                            title="Voir la commande"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                                                <circle cx="12" cy="12" r="3"/>
                                                            </svg>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="profil-icon-button profil-icon-button-print"
                                                            onclick="event.stopPropagation(); printOrder({{ $commande->id_commande }});"
                                                            title="Imprimer"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M6 9V4h12v5"/>
                                                                <path d="M6 14H4a2 2 0 0 1-2-2v-1a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v1a2 2 0 0 1-2 2h-2"/>
                                                                <path d="M6 14h12v6H6z"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-[#706f6c] profil-no-order-text">Aucune commande pour le moment.</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($userCommandes) && count($userCommandes) > 0)
                    @foreach($userCommandes as $commande)
                        @php
                            $lignes = $userCommandesLignes[$commande->id_commande] ?? [];
                            $totalHT = 0;
                        @endphp
                        <div id="commande-modal-{{ $commande->id_commande }}" class="modal-form-backdrop hidden">
                            <div class="modal-form-container print-order-root">
                                <div class="modal-form-header">
                                    <h3 class="modal-form-title">
                                        Commande du {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y H:i') }}
                                    </h3>
                                    <button type="button"
                                            class="modal-form-close-button"
                                            data-close-commande-modal="{{ $commande->id_commande }}">
                                        ✕
                                    </button>
                                </div>
                                <div class="max-h-[70vh] overflow-y-auto">
                                    @if(count($lignes) > 0)
                                        <table class="min-w-full divide-y divide-gray-200 mb-4">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix TTC</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total TTC</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($lignes as $ligne)
                                                    @php
                                                        $ligneTotal = $ligne->prix_TTC * $ligne->quantité;
                                                        $totalHT += $ligne->prix_HT * $ligne->quantité;
                                                    @endphp
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ $ligne->nom }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ $ligne->quantité }}
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ number_format($ligne->prix_TTC, 2, ',', ' ') }} €
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ number_format($ligneTotal, 2, ',', ' ') }} €
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="text-sm text-gray-700">
                                                Total HT : <span class="font-semibold">{{ number_format($totalHT, 2, ',', ' ') }} €</span>
                                            </div>
                                            <div class="text-sm text-gray-900">
                                                Total TTC : <span class="font-semibold">{{ number_format($commande->total, 2, ',', ' ') }} €</span>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-[#706f6c] text-sm">Aucun détail de ligne pour cette commande.</p>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center border-t px-6 py-3">
                                    <button type="button"
                                            class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 text-sm hover:bg-gray-300"
                                            data-close-commande-modal="{{ $commande->id_commande }}">
                                        Fermer
                                    </button>
                                    <button type="button"
                                            class="px-4 py-2 rounded-md bg-[#1b1b18] text-white text-sm hover:opacity-90"
                                            onclick="printOrder({{ $commande->id_commande }});">
                                        Imprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            {{-- Formulaires pour utilisateur non connecté --}}
            <div class="container mx-auto px-4 py-8 profil-page-container">
                <div class="flex items-center justify-center min-h-[60vh]">
                    <div class="w-[500px]">
                    {{-- Formulaire de connexion --}}
                    <div id="login-form" class="bg-white rounded-lg p-[100px]">
                        <h2 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center profil-minecrafter-text">Connexion</h2>
                        
                        @if ($errors->has('email') && !$errors->has('name'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('login.post') }}" class="profil-auth-form">
                            @csrf
                            
                            <div class="mb-4 profil-field-group">
                                <label for="login-email" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Email</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="email" 
                                           id="login-email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Votre email">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="login-password" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Mot de passe</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="password" 
                                           id="login-password" 
                                           name="password" 
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Votre mot de passe">
                                </div>
                            </div>
                            
                            <div class="mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="mr-2">
                                    <span class="text-sm text-[#706f6c] profil-remember-text">Se souvenir de moi</span>
                                </label>
                            </div>
                            
                            <div class="relative mx-auto profil-btn-wrapper-sm">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button">
                                    <span class="text-white font-bold text-base md:text-lg profil-btn-text">
                                        Se connecter
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-[20px] text-[#706f6c] text-sm profil-helper-text">
                            Pas encore de compte ? 
                            <a href="#" id="show-register" class="text-[#5baa47] hover:underline font-bold">S'inscrire</a>
                        </p>
                    </div>
                    
                    {{-- Formulaire d'inscription --}}
                    <div id="register-form" class="bg-white rounded-lg p-[100px] hidden">
                        <h2 class="text-3xl font-bold text-[#1b1b18] mb-6 text-center profil-minecrafter-text">Inscription</h2>
                        
                        @if ($errors->has('nom') || ($errors->has('email') && $errors->has('nom')) || $errors->has('password'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('register.post') }}" class="profil-auth-form">
                            @csrf
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-firstname" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Prénom</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="text" 
                                           id="register-firstname" 
                                           name="prenom" 
                                           value="{{ old('prenom') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Votre prénom">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-name" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Nom</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="text" 
                                           id="register-name" 
                                           name="nom" 
                                           value="{{ old('nom') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Votre nom">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-email" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Email</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="email" 
                                           id="register-email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Votre email">
                                </div>
                            </div>

                            <div class="mb-4 profil-field-group">
                                <label for="register-birthdate" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Date de naissance</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="date"
                                           id="register-birthdate"
                                           name="date_naissance"
                                           value="{{ old('date_naissance') }}"
                                           required
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control">
                                </div>
                            </div>
                            
                            <div class="mb-4 profil-field-group">
                                <label for="register-password" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Mot de passe</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="password" 
                                           id="register-password" 
                                           name="password" 
                                           required 
                                           minlength="8"
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Votre mot de passe">
                                </div>
                                <p class="text-xs text-[#706f6c] mt-1 profil-helper-text">Minimum 8 caractères</p>
                            </div>
                            
                            <div class="mb-6 profil-field-group">
                                <label for="register-password-confirm" class="block text-sm font-medium text-[#1b1b18] mb-2 profil-minecrafter-text">Confirmer le mot de passe</label>
                                <div class="p-2 profil-input-wrapper">
                                    <input type="password" 
                                           id="register-password-confirm" 
                                           name="password_confirmation" 
                                           required 
                                           class="w-full px-3 py-2 border-0 bg-transparent text-white placeholder-[#706f6c] focus:outline-none profil-input-control"
                                           placeholder="Confirmez votre mot de passe">
                                </div>
                            </div>
                            
                            <div class="relative mx-auto profil-btn-wrapper-sm">
                                <img src="{{ asset('images/btn.png') }}" alt="" class="w-full h-auto block">
                                <button type="submit"
                                        class="absolute inset-0 w-full h-full flex items-center justify-center hover:opacity-90 transition-opacity duration-200 profil-btn-image-button">
                                    <span class="text-white font-bold text-base md:text-lg profil-btn-text">
                                        S'inscrire
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <p class="text-center mt-[20px] text-[#706f6c] text-sm profil-helper-text">
                            Déjà un compte ? 
                            <a href="#" id="show-login" class="text-[#5baa47] hover:underline font-bold">Se connecter</a>
                        </p>
                    </div>
                </div>
            </div>
        @endauth

    @push('scripts')
    <script>
        function openOrderModal(id) {
            var modal = document.getElementById('commande-modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeOrderModal(id) {
            var modal = document.getElementById('commande-modal-' + id);
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function printOrder(id) {
            var modal = document.getElementById('commande-modal-' + id);
            if (!modal) {
                return;
            }

            var content = modal.querySelector('.print-order-root');
            if (!content) {
                return;
            }

            var printWindow = window.open('', '_blank');
            if (!printWindow) {
                return;
            }

            printWindow.document.open();
            printWindow.document.write('<!DOCTYPE html><html><head><title>Commande</title>');
            printWindow.document.write('<link rel="stylesheet" href="{{ asset('css/profile.css') }}">');
            printWindow.document.write('<link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }

        document.addEventListener('DOMContentLoaded', function () {
            var closeButtons = document.querySelectorAll('[data-close-commande-modal]');
            closeButtons.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var id = btn.getAttribute('data-close-commande-modal');
                    closeOrderModal(id);
                });
            });
        });

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
