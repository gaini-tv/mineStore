@extends('layouts.app')

@section('title', 'Administration')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    <div class="admin-banner">
        <img src="{{ asset('images/banierP.png') }}" alt="Bannière administration" class="admin-banner-image">
        <h1 class="absolute inset-0 flex items-center justify-center admin-banner-title">
            Administration
        </h1>
    </div>

    <div class="admin-page">

        @if(session('success'))
            <div class="admin-alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="admin-alert-error">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="admin-grid">
            <div class="admin-section-block">
                <div class="admin-section-header">
                    <h2 class="admin-section-header-title">
                        Gestion des membres
                    </h2>
                </div>
                <section class="admin-section">
                    <form class="admin-users-section-form">
                        <div class="admin-search-bar-wrapper">
                            <label for="admin-user-search" class="admin-card-meta">Recherche (prénom, nom, email)</label>
                            <input
                                id="admin-user-search"
                                type="text"
                                placeholder="Rechercher un membre..."
                                class="admin-search-input"
                            >
                        </div>
                        <div class="admin-users-table-wrapper">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <span>Prénom &amp; Nom</span>
                                            <button type="button" class="admin-sort-icon" data-sort-column="name">
                                                ⇵
                                            </button>
                                        </th>
                                        <th>
                                            <span>Email</span>
                                            <button type="button" class="admin-sort-icon" data-sort-column="email">
                                                ⇵
                                            </button>
                                        </th>
                                        <th>
                                            <span>Rôle</span>
                                            <div class="admin-role-filter-wrapper">
                                                <select
                                                    class="admin-role-filter-select"
                                                    id="admin-role-filter"
                                                >
                                                    <option value="">Tous les rôles</option>
                                                    <option value="user">user</option>
                                                    <option value="admin">admin</option>
                                                    <option value="owner">owner</option>
                                                    <option value="manager">manager</option>
                                                    <option value="product_manager">product_manager</option>
                                                    <option value="stock_manager">stock_manager</option>
                                                    <option value="editor">editor</option>
                                                </select>
                                            </div>
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-users-tbody">
                                    @foreach($users as $user)
                                        @php
                                            $isAbsoluteAdmin = $user->email === 'minestore-Admin@gmail.com';
                                        @endphp
                                        <tr
                                            class="admin-user-row"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->prenom }} {{ $user->nom }}"
                                            data-user-email="{{ $user->email }}"
                                            data-user-role="{{ $user->role }}"
                                        >
                                            <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <div class="admin-user-actions">
                                                    <button
                                                        type="button"
                                                        class="admin-icon-button admin-icon-button-eye"
                                                        onclick="event.stopPropagation(); openUserModal({{ $user->id }});"
                                                        title="Voir le compte"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                                            <circle cx="12" cy="12" r="3"/>
                                                        </svg>
                                                    </button>
                                                    @unless($isAbsoluteAdmin)
                                                        <form
                                                            action="{{ route('admin.users.destroy', $user) }}"
                                                            method="POST"
                                                            class="admin-async-form"
                                                            data-admin-action="delete-user"
                                                            data-user-id="{{ $user->id }}"
                                                            onsubmit="event.stopPropagation(); return confirm('Supprimer ce compte utilisateur ?');"
                                                            onclick="event.stopPropagation();"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                type="submit"
                                                                class="admin-icon-button admin-icon-button-delete"
                                                                title="Supprimer le compte"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <polyline points="3 6 5 6 21 6"/>
                                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                                    <path d="M10 11v6"/>
                                                                    <path d="M14 11v6"/>
                                                                    <path d="M9 6V4h6v2"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endunless
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </section>
            </div>

            <div class="admin-section-block">
                <div class="admin-section-header">
                    <h2 class="admin-section-header-title">
                        Gestion des entreprises
                    </h2>
                </div>
                <section class="admin-section">
                    <div class="admin-card-list">
                        <div>
                            <h3 class="admin-subtitle">Entreprises créées</h3>
                            @if($entreprises->isEmpty())
                                <p class="admin-card-meta">Aucune entreprise enregistrée pour le moment.</p>
                            @endif
                            @if($entreprises->isNotEmpty())
                                <div class="admin-search-bar-wrapper" style="margin-top: 1rem;">
                                    <label for="admin-entreprises-search" class="admin-card-meta">Recherche (entreprise, propriétaire, email)</label>
                                    <input id="admin-entreprises-search" type="text" placeholder="Rechercher une entreprise..." class="admin-search-input">
                                </div>
                                <div class="admin-users-table-wrapper" style="max-height: 300px; overflow-y: auto; margin-top: 0.5rem;">
                                    <table class="admin-table" id="admin-entreprises-table">
                                        <thead>
                                            <tr>
                                                <th>Entreprise</th>
                                                <th>Propriétaire</th>
                                                <th>Date de création</th>
                                                <th>Membres</th>
                                                <th>Produits</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="admin-entreprises-tbody">
                                            @foreach($entreprises as $entreprise)
                                                @php
                                                    $stats = ($entreprisesStats[$entreprise->id_entreprise] ?? ['membres'=>0,'produits'=>0,'benefices'=>0,'articles'=>0]);
                                                    $owner = $entreprise->owner;
                                                @endphp
                                                <tr class="admin-entreprise-row"
                                                    data-entreprise-id="{{ $entreprise->id_entreprise }}"
                                                    data-entreprise-name="{{ $entreprise->nom }}"
                                                    data-owner-name="{{ $owner ? ($owner->prenom.' '.$owner->nom) : '' }}"
                                                    data-owner-email="{{ $owner ? $owner->email : '' }}"
                                                >
                                                    <td>{{ $entreprise->nom }}</td>
                                                    <td>
                                                        @if($owner)
                                                            {{ $owner->prenom }} {{ $owner->nom }} ({{ $owner->email }})
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>{{ optional($entreprise->created_at)->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $stats['membres'] }}</td>
                                                    <td>{{ $stats['produits'] }}</td>
                                                    <td>
                                                        <div class="admin-user-actions">
                                                            <button
                                                                type="button"
                                                                class="admin-icon-button admin-icon-button-eye"
                                                                onclick="document.getElementById('entreprise-active-modal-{{ $entreprise->id_entreprise }}').style.display='flex'"
                                                                title="Voir l’entreprise"
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                                                    <circle cx="12" cy="12" r="3"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="admin-subtitle">Demandes de création</h3>
                            @if($demandesCreation->isEmpty())
                                <p class="admin-card-meta">Aucune demande de création en attente.</p>
                            @endif
                            @if($demandesCreation->isNotEmpty())
                                <div class="admin-users-table-wrapper" style="max-height: 300px; overflow-y: auto; margin-top: 0.5rem;">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Entreprise</th>
                                                <th>Propriétaire</th>
                                                <th>Adresse</th>
                                                <th>Tentatives</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($demandesCreation as $demande)
                                                @php
                                                    $owner = $demande->owner;
                                                    $attempts = $owner ? ($pendingAttemptsByOwner[$owner->id] ?? 1) : 1;
                                                @endphp
                                                <tr>
                                                    <td>{{ $demande->nom }}</td>
                                                    <td>
                                                        @if($owner)
                                                            {{ $owner->prenom }} {{ $owner->nom }} ({{ $owner->email }})
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>{{ $demande->adresse }}</td>
                                                    <td>{{ $attempts }}</td>
                                                    <td>
                                                        <div class="admin-user-actions">
                                                            <form action="{{ route('admin.entreprises.approve', $demande) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="admin-icon-button admin-icon-button-eye" title="Accepter">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M20 6L9 17l-5-5"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.entreprises.refuse', $demande) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="admin-icon-button admin-icon-button-delete" title="Refuser">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M18 6L6 18"/>
                                                                        <path d="M6 6l12 12"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="admin-subtitle">Demandes de suppression</h3>
                            @if($demandesSuppression->isEmpty())
                                <p class="admin-card-meta">Aucune demande de suppression en attente.</p>
                            @endif
                            @if($demandesSuppression->isNotEmpty())
                                <div class="admin-users-table-wrapper" style="max-height: 300px; overflow-y: auto; margin-top: 0.5rem;">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Entreprise</th>
                                                <th>Propriétaire</th>
                                                <th>Membres</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($demandesSuppression as $demande)
                                                @php
                                                    $owner = $demande->owner;
                                                    $membresCount = $demande->users()->count();
                                                @endphp
                                                <tr>
                                                    <td>{{ $demande->nom }}</td>
                                                    <td>
                                                        @if($owner)
                                                            {{ $owner->prenom }} {{ $owner->nom }} ({{ $owner->email }})
                                                        @else
                                                            —
                                                        @endif
                                                    </td>
                                                    <td>{{ $membresCount }}</td>
                                                    <td>
                                                        <div class="admin-user-actions">
                                                            <form action="{{ route('admin.entreprises.approveDeletion', $demande) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="admin-icon-button admin-icon-button-delete" title="Accepter la suppression">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3 6 5 6 21 6"/>
                                                                        <path d="M19 6l-1 14H6L5 6"/>
                                                                        <path d="M10 11v6"/>
                                                                        <path d="M14 11v6"/>
                                                                        <path d="M9 6V4h6v2"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.entreprises.cancelDeletion', $demande) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="admin-icon-button admin-icon-button-eye" title="Refuser la suppression">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                                                        <circle cx="12" cy="12" r="3"/>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

            {{-- Modales de gestion des utilisateurs --}}
            @foreach($users as $user)
                @php
                    $isAbsoluteAdmin = $user->email === 'minestore-Admin@gmail.com';
                @endphp
                <div id="user-modal-{{ $user->id }}" class="hidden modal-form-backdrop">
                    <div class="modal-form-container admin-user-modal-container">
                        <div class="modal-form-header">
                            <h3 class="modal-form-title admin-user-modal-title">Compte utilisateur</h3>
                            <button type="button" class="modal-form-close-button" onclick="closeUserModal({{ $user->id }})">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4 admin-user-edit-form" data-user-id="{{ $user->id }}">
                            @csrf
                            <div class="modal-form-field-group">
                                <label class="modal-form-label">Prénom</label>
                                <div class="modal-form-field-wrapper">
                                    <input
                                        type="text"
                                        name="prenom"
                                        class="modal-form-input"
                                        value="{{ $user->prenom }}"
                                        data-original-value="{{ $user->prenom }}"
                                        @if($isAbsoluteAdmin) disabled @endif
                                    >
                                </div>
                            </div>
                            <div class="modal-form-field-group">
                                <label class="modal-form-label">Nom</label>
                                <div class="modal-form-field-wrapper">
                                    <input
                                        type="text"
                                        name="nom"
                                        class="modal-form-input"
                                        value="{{ $user->nom }}"
                                        data-original-value="{{ $user->nom }}"
                                        @if($isAbsoluteAdmin) disabled @endif
                                    >
                                </div>
                            </div>
                            <div class="modal-form-field-group">
                                <label class="modal-form-label">Email</label>
                                <div class="modal-form-field-wrapper">
                                    <input
                                        type="email"
                                        name="email"
                                        class="modal-form-input"
                                        value="{{ $user->email }}"
                                        data-original-value="{{ $user->email }}"
                                        @if($isAbsoluteAdmin) disabled @endif
                                    >
                                </div>
                            </div>
                            <div class="modal-form-field-group">
                                <label class="modal-form-label">Rôle (modifiable : user / admin)</label>
                                <div class="modal-form-field-wrapper">
                                    <select
                                        name="role"
                                        class="modal-form-select"
                                        data-original-value="{{ $user->role }}"
                                        @if($isAbsoluteAdmin) disabled @endif
                                    >
                                        <option value="user" @selected($user->role === 'user')>user</option>
                                        <option value="admin" @selected($user->role === 'admin')>admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-form-field-group">
                                <p class="admin-card-meta">
                                    Date de création du compte :
                                    {{ optional($user->created_at)->format('d/m/Y H:i') }}
                                </p>
                                <p class="admin-card-meta">
                                    Date de naissance :
                                    {{ optional($user->date_naissance)->format('d/m/Y') ?? 'Non renseignée' }}
                                </p>
                                <p class="admin-card-meta">
                                    Dernière connexion :
                                    {{ optional($user->last_login_at)->format('d/m/Y H:i') ?? 'Jamais connecté' }}
                                </p>
                                <p class="admin-card-meta">
                                    Compte actif :
                                    {{ $user->last_login_at ? 'Oui' : 'Non (jamais connecté)' }}
                                </p>
                            </div>
                            <div class="modal-form-footer">
                                <button
                                    type="button"
                                    class="admin-button"
                                    onclick="closeUserModal({{ $user->id }})"
                                >
                                    Fermer
                                </button>
                                @unless($isAbsoluteAdmin)
                                    <button
                                        type="submit"
                                        class="admin-button admin-button-primary admin-user-confirm-btn"
                                    >
                                        Confirmer les modifications
                                    </button>
                                @endunless
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="admin-section-block">
                <div class="admin-section-header">
                    <h2 class="admin-section-header-title">
                        Gestion des catégories
                    </h2>
                </div>
                <section class="admin-section">
                    <div class="admin-category-layout">
                        <div class="admin-category-table-wrapper">
                            <h3 class="admin-subtitle">Catégories existantes</h3>
                            @if($categories->isEmpty())
                                <p class="admin-card-meta">Aucune catégorie enregistrée.</p>
                            @else
                                <div class="admin-users-table-wrapper">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Description</th>
                                                <th>Produits associés</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categories as $categorie)
                                                @php
                                                    $isUncategorized = mb_strtolower(trim($categorie->nom)) === mb_strtolower('Non catégorisé');
                                                @endphp
                                                <tr
                                                    class="admin-category-row"
                                                    data-category-id="{{ $categorie->id_categorie }}"
                                                    data-category-name="{{ $categorie->nom }}"
                                                    data-category-description="{{ $categorie->description }}"
                                                >
                                                    <td>{{ $categorie->nom }}</td>
                                                    <td>{{ $categorie->description }}</td>
                                                    <td>{{ $categorie->produits_count ?? 0 }}</td>
                                                    <td>
                                                        <div class="admin-user-actions">
                                                            <button
                                                                type="button"
                                                                class="admin-icon-button admin-icon-button-eye"
                                                                onclick="openCategoryModal({{ $categorie->id_categorie }});"
                                                                title="Voir / modifier"
                                                                @if($isUncategorized) disabled @endif
                                                            >
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                                                                    <circle cx="12" cy="12" r="3"/>
                                                                </svg>
                                                            </button>
                                                            @unless($isUncategorized)
                                                                <form
                                                                    action="{{ route('admin.categories.destroy', $categorie) }}"
                                                                    method="POST"
                                                                    class="admin-async-form"
                                                                    onsubmit="return confirm('Supprimer cette catégorie ?');"
                                                                >
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button
                                                                        type="submit"
                                                                        class="admin-icon-button admin-icon-button-delete"
                                                                        title="Supprimer la catégorie"
                                                                    >
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                            <polyline points="3 6 5 6 21 6"/>
                                                                            <path d="M19 6l-1 14H6L5 6"/>
                                                                            <path d="M10 11v6"/>
                                                                            <path d="M14 11v6"/>
                                                                            <path d="M9 6V4h6v2"/>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endunless
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="admin-category-form-card">
                            <h3 class="admin-subtitle">Créer une nouvelle catégorie</h3>
                            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <input
                                    type="text"
                                    name="nom"
                                    placeholder="Nom de la catégorie"
                                    class="admin-input"
                                    required
                                >
                                <textarea
                                    name="description"
                                    placeholder="Description (optionnel)"
                                    class="admin-textarea"
                                    rows="3"
                                ></textarea>
                                <button type="submit" class="admin-button admin-button-primary">
                                    <img src="{{ asset('images/plus.png') }}" alt="" class="admin-button-icon">
                                    <span>Ajouter la catégorie</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
            </div>

            @foreach($categories as $categorie)
                @php
                    $isUncategorized = mb_strtolower(trim($categorie->nom)) === mb_strtolower('Non catégorisé');
                @endphp
                @unless($isUncategorized)
                    <div id="category-modal-{{ $categorie->id_categorie }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
                        <div class="admin-modal-content">
                            <h3 class="text-xl font-bold mb-3" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Modifier la catégorie</h3>
                            <form action="{{ route('admin.categories.update', $categorie) }}" method="POST" class="space-y-4 admin-category-edit-form">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="admin-card-meta">Nom</label>
                                    <input
                                        type="text"
                                        name="nom"
                                        value="{{ $categorie->nom }}"
                                        class="admin-input"
                                        data-original-value="{{ $categorie->nom }}"
                                        required
                                    >
                                </div>
                                <div>
                                    <label class="admin-card-meta">Description</label>
                                    <textarea
                                        name="description"
                                        class="admin-textarea"
                                        rows="3"
                                        data-original-value="{{ $categorie->description }}"
                                    >{{ $categorie->description }}</textarea>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="admin-button" onclick="closeCategoryModal({{ $categorie->id_categorie }});">
                                        Annuler
                                    </button>
                                    <button type="submit" class="admin-button admin-button-primary admin-category-confirm-btn">
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endunless
            @endforeach

            <div class="admin-section-block">
                <div class="admin-section-header">
                    <h2 class="admin-section-header-title">
                        Filtrage des mots interdits
                    </h2>
                </div>
                <section class="admin-section">
                    <form
                        id="admin-bannedwords-add-form"
                        action="{{ route('admin.banned-words.store') }}"
                        method="POST"
                        class="admin-bannedword-add-form"
                    >
                        @csrf
                        <input
                            type="text"
                            name="word"
                            class="admin-input admin-bannedword-input"
                            placeholder="Nouveau mot à bannir (ex : Putain)"
                            required
                        >
                        <button type="submit" class="admin-button admin-button-primary">
                            <img src="{{ asset('images/plus.png') }}" alt="" class="admin-button-icon">
                            <span>Ajouter</span>
                        </button>
                    </form>

                    <div class="admin-bannedwords-container">
                        <h3 class="admin-subtitle">Mots bannis actuels</h3>
                        <div id="admin-bannedwords-tags">
                            @if($bannedWords->isEmpty())
                                <p class="admin-card-meta">Aucun mot banni configuré pour le moment.</p>
                            @else
                                <div class="admin-bannedwords-tags">
                                    @foreach($bannedWords as $bannedWord)
                                        <form
                                            action="{{ route('admin.banned-words.destroy', $bannedWord) }}"
                                            method="POST"
                                            class="admin-bannedword-tag admin-bannedword-delete-form"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <span>{{ $bannedWord->word }}</span>
                                            <button type="submit" class="admin-bannedword-remove-btn" title="Supprimer">
                                                ×
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function getAdminToastContainer() {
            let container = document.getElementById('admin-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'admin-toast-container';
                container.className = 'admin-toast-container';
                document.body.appendChild(container);
            }
            return container;
        }

        function showAdminToast(message, type) {
            if (!message) {
                return;
            }

            const container = getAdminToastContainer();
            const toast = document.createElement('div');
            toast.className = 'admin-toast';

            if (type === 'error') {
                toast.className += ' admin-toast-error';
            } else {
                toast.className += ' admin-toast-success';
            }

            toast.textContent = message;
            container.appendChild(toast);

            setTimeout(function() {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    toast.remove();
                }, 200);
            }, 4000);
        }

        (function () {
            const successAlert = document.querySelector('.admin-alert-success');
            if (successAlert) {
                const text = successAlert.textContent.trim();
                if (text) {
                    showAdminToast(text, 'success');
                }
                successAlert.remove();
            }

            const errorAlert = document.querySelector('.admin-alert-error');
            if (errorAlert) {
                const items = errorAlert.querySelectorAll('li');
                if (items.length) {
                    items.forEach(function(item) {
                        const text = item.textContent.trim();
                        if (text) {
                            showAdminToast(text, 'error');
                        }
                    });
                } else {
                    const text = errorAlert.textContent.trim();
                    if (text) {
                        showAdminToast(text, 'error');
                    }
                }
                errorAlert.remove();
            }
        })();

        document.querySelectorAll('[id^="entreprise-active-modal-"]').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });

        function openUserModal(userId) {
            const modal = document.getElementById('user-modal-' + userId);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function resetUserModalForm(modal) {
            const form = modal.querySelector('.admin-user-edit-form');
            if (!form) {
                return;
            }

            form.querySelectorAll('input[name="prenom"], input[name="nom"], input[name="email"], select[name="role"]').forEach(function(field) {
                const original = field.dataset.originalValue;
                if (typeof original !== 'undefined') {
                    field.value = original;
                }
            });

            const confirmBtn = form.querySelector('.admin-user-confirm-btn');
            if (confirmBtn) {
                confirmBtn.style.display = 'none';
            }
        }

        function closeUserModal(userId) {
            const modal = document.getElementById('user-modal-' + userId);
            if (modal) {
                resetUserModalForm(modal);
                modal.classList.add('hidden');
            }
        }

        document.querySelectorAll('.admin-user-row').forEach(function(row) {
            row.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                openUserModal(userId);
            });
        });

        document.querySelectorAll('[id^="user-modal-"]').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    resetUserModalForm(modal);
                    modal.classList.add('hidden');
                }
            });
        });

        function openCategoryModal(categoryId) {
            const modal = document.getElementById('category-modal-' + categoryId);
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeCategoryModal(categoryId) {
            const modal = document.getElementById('category-modal-' + categoryId);
            if (modal) {
                const form = modal.querySelector('.admin-category-edit-form');
                if (form) {
                    form.querySelectorAll('input[name="nom"], textarea[name="description"]').forEach(function(field) {
                        const original = field.dataset.originalValue ?? '';
                        field.value = original;
                    });
                }
                modal.classList.add('hidden');
            }
        }

        document.querySelectorAll('[id^="category-modal-"]').forEach(function(modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        document.querySelectorAll('.admin-user-edit-form').forEach(function(form) {
            const confirmBtn = form.querySelector('.admin-user-confirm-btn');

            function checkChanges() {
                if (!confirmBtn) {
                    return;
                }

                let changed = false;
                form.querySelectorAll('input[name="prenom"], input[name="nom"], input[name="email"], select[name="role"]').forEach(function(field) {
                    const original = field.dataset.originalValue ?? '';
                    if (field.value !== original) {
                        changed = true;
                    }
                });
                if (changed) {
                    confirmBtn.style.display = 'inline-flex';
                } else {
                    confirmBtn.style.display = 'none';
                }
            }

            form.querySelectorAll('input, select').forEach(function(field) {
                field.addEventListener('input', checkChanges);
                field.addEventListener('change', checkChanges);
            });

            checkChanges();
        });

        (function () {
            const usersTbody = document.getElementById('admin-users-tbody');
            const searchInput = document.getElementById('admin-user-search');
            const roleFilter = document.getElementById('admin-role-filter');
            const sortButtons = document.querySelectorAll('.admin-sort-icon');
            const memberForm = document.querySelector('.admin-users-section-form');

            if (!usersTbody) {
                return;
            }

            if (memberForm) {
                memberForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                });
            }

            const rows = Array.from(usersTbody.querySelectorAll('.admin-user-row')).map(function(row) {
                const name = (row.getAttribute('data-user-name') || '').toLowerCase();
                const email = (row.getAttribute('data-user-email') || '').toLowerCase();
                const role = (row.getAttribute('data-user-role') || '').toLowerCase();

                return {
                    row: row,
                    name: name,
                    email: email,
                    role: role
                };
            });

            let currentSearch = '';
            let currentRole = '';
            let currentSortColumn = null;
            let currentSortDirection = 'asc';

            function applyFiltersAndSort() {
                let filtered = rows.filter(function(item) {
                    if (currentRole && item.role !== currentRole) {
                        return false;
                    }

                    if (currentSearch) {
                        return item.name.indexOf(currentSearch) !== -1 || item.email.indexOf(currentSearch) !== -1;
                    }

                    return true;
                });

                if (currentSortColumn) {
                    filtered.sort(function(a, b) {
                        let valueA;
                        let valueB;

                        if (currentSortColumn === 'name') {
                            valueA = a.name;
                            valueB = b.name;
                        } else if (currentSortColumn === 'email') {
                            valueA = a.email;
                            valueB = b.email;
                        } else {
                            valueA = '';
                            valueB = '';
                        }

                        if (valueA < valueB) {
                            return currentSortDirection === 'asc' ? -1 : 1;
                        }
                        if (valueA > valueB) {
                            return currentSortDirection === 'asc' ? 1 : -1;
                        }
                        return 0;
                    });
                }

                usersTbody.innerHTML = '';
                filtered.forEach(function(item) {
                    usersTbody.appendChild(item.row);
                });
            }

            if (searchInput) {
                let timeoutId;
                searchInput.addEventListener('input', function() {
                    const value = this.value || '';
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(function() {
                        currentSearch = value.trim().toLowerCase();
                        applyFiltersAndSort();
                    }, 200);
                });

                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            }

            if (roleFilter) {
                roleFilter.addEventListener('change', function() {
                    currentRole = (this.value || '').toLowerCase();
                    applyFiltersAndSort();
                });
            }

            if (sortButtons.length) {
                sortButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const column = this.getAttribute('data-sort-column');
                        if (!column) {
                            return;
                        }

                        if (currentSortColumn === column) {
                            currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
                        } else {
                            currentSortColumn = column;
                            currentSortDirection = 'asc';
                        }

                        sortButtons.forEach(function(btn) {
                            if (btn === button) {
                                btn.textContent = currentSortDirection === 'asc' ? '▲' : '▼';
                            } else {
                                btn.textContent = '⇵';
                            }
                        });

                        applyFiltersAndSort();
                    });
                });
            }

            function handleAsyncSuccess(action, form) {
                if (action === 'delete-user') {
                    const userId = form.getAttribute('data-user-id');
                    if (userId) {
                        const row = usersTbody.querySelector('.admin-user-row[data-user-id="' + userId + '"]');
                        if (row && row.parentElement) {
                            row.parentElement.removeChild(row);
                        }

                        const index = rows.findIndex(function(item) {
                            return item.row.getAttribute('data-user-id') === userId;
                        });
                        if (index !== -1) {
                            rows.splice(index, 1);
                        }
                    }

                    applyFiltersAndSort();
                    showAdminToast('Utilisateur supprimé avec succès.', 'success');
                    return;
                }

                if (action === 'entreprise-approve' || action === 'entreprise-refuse') {
                    const entrepriseId = form.getAttribute('data-entreprise-id');
                    if (entrepriseId) {
                        const modal = document.getElementById('entreprise-demand-modal-' + entrepriseId);
                        if (modal) {
                            modal.style.display = 'none';
                        }

                        const card = document.querySelector('.admin-entreprise-demand-card[data-entreprise-id="' + entrepriseId + '"]');
                        if (card && card.parentElement) {
                            card.parentElement.removeChild(card);
                        }
                    }

                    const message = action === 'entreprise-approve'
                        ? 'Entreprise approuvée avec succès.'
                        : 'Demande de création refusée.';

                    showAdminToast(message, 'success');
                    return;
                }

                if (action === 'entreprise-approve-deletion' || action === 'entreprise-cancel-deletion') {
                    const entrepriseId = form.getAttribute('data-entreprise-id');
                    if (entrepriseId) {
                        const modal = document.getElementById('entreprise-delete-modal-' + entrepriseId);
                        if (modal) {
                            modal.style.display = 'none';
                        }

                        if (action === 'entreprise-approve-deletion') {
                            const card = document.querySelector('.admin-entreprise-delete-card[data-entreprise-id="' + entrepriseId + '"]');
                            if (card && card.parentElement) {
                                card.parentElement.removeChild(card);
                            }
                        }
                    }

                    const message = action === 'entreprise-approve-deletion'
                        ? 'Entreprise supprimée.'
                        : 'Demande de suppression annulée.';

                    showAdminToast(message, 'success');
                    return;
                }

                showAdminToast('Action effectuée.', 'success');
            }

            function handleAsyncError() {
                showAdminToast('Une erreur est survenue.', 'error');
            }

            document.querySelectorAll('form.admin-async-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const action = form.getAttribute('data-admin-action') || '';
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    }).then(function(response) {
                        if (response.ok) {
                            handleAsyncSuccess(action, form);
                        } else {
                            handleAsyncError();
                        }
                    }).catch(function() {
                        handleAsyncError();
                    });
                });
            });
        })();

        (function () {
            const table = document.getElementById('admin-entreprises-table');
            const tbody = document.getElementById('admin-entreprises-tbody');
            const input = document.getElementById('admin-entreprises-search');
            if (!table || !tbody || !input) return;

            function normalize(s) {
                return (s || '').toString().toLowerCase();
            }

            function filter() {
                const q = normalize(input.value);
                const rows = tbody.querySelectorAll('tr.admin-entreprise-row');
                rows.forEach(function (row) {
                    const name = normalize(row.getAttribute('data-entreprise-name'));
                    const owner = normalize(row.getAttribute('data-owner-name'));
                    const email = normalize(row.getAttribute('data-owner-email'));
                    const match = !q || name.includes(q) || owner.includes(q) || email.includes(q);
                    row.style.display = match ? '' : 'none';
                });
            }

            input.addEventListener('input', filter);
        })();

        (function () {
            const addForm = document.getElementById('admin-bannedwords-add-form');
            const tagsContainer = document.getElementById('admin-bannedwords-tags');

            if (!addForm || !tagsContainer) {
                return;
            }

            const input = addForm.querySelector('input[name="word"]');
            const submitButtons = addForm.querySelectorAll('button[type="submit"]');
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');

            function setAddFormDisabled(disabled) {
                if (input) {
                    input.disabled = disabled;
                }
                submitButtons.forEach(function(btn) {
                    btn.disabled = disabled;
                    if (disabled) {
                        btn.style.opacity = '0.7';
                        btn.style.cursor = 'not-allowed';
                    } else {
                        btn.style.opacity = '';
                        btn.style.cursor = '';
                    }
                });
            }

            function buildTagsHtml(bannedWords) {
                if (!bannedWords || !bannedWords.length) {
                    return '<p class="admin-card-meta">Aucun mot banni configuré pour le moment.</p>';
                }

                const items = bannedWords.map(function(bannedWord) {
                    const word = String(bannedWord.word || '');
                    const safeWord = word.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    const url = '/admin/banned-words/' + bannedWord.id;

                    return '' +
                        '<form ' +
                            'action="' + url + '" ' +
                            'method="POST" ' +
                            'class="admin-bannedword-tag admin-bannedword-delete-form"' +
                        '>' +
                            '<span>' + safeWord + '</span>' +
                            '<button type="submit" class="admin-bannedword-remove-btn" title="Supprimer">' +
                                '×' +
                            '</button>' +
                        '</form>';
                }).join('');

                return '<div class="admin-bannedwords-tags">' + items + '</div>';
            }

            function attachDeleteHandlers() {
                const deleteForms = tagsContainer.querySelectorAll('.admin-bannedword-delete-form');

                deleteForms.forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        const action = form.getAttribute('action');
                        if (!action) {
                            return;
                        }

                        if (!window.confirm('Supprimer ce mot banni ?')) {
                            return;
                        }

                        const headers = {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        };

                        if (csrfMeta && csrfMeta.content) {
                            headers['X-CSRF-TOKEN'] = csrfMeta.content;
                        }

                        fetch(action, {
                            method: 'DELETE',
                            headers: headers
                        }).then(function(response) {
                            if (!response.ok) {
                                throw new Error('Request failed');
                            }
                            return response.json();
                        }).then(function(data) {
                            if (data && Array.isArray(data.bannedWords)) {
                                tagsContainer.innerHTML = buildTagsHtml(data.bannedWords);
                                attachDeleteHandlers();
                            }
                        }).catch(function() {
                            showAdminToast('Une erreur est survenue.', 'error');
                        });
                    });
                });
            }

            addForm.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!input || !input.value.trim()) {
                    return;
                }

                const action = addForm.getAttribute('action');
                if (!action) {
                    return;
                }

                const headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                };

                if (csrfMeta && csrfMeta.content) {
                    headers['X-CSRF-TOKEN'] = csrfMeta.content;
                }

                const formData = new FormData(addForm);

                setAddFormDisabled(true);

                fetch(action, {
                    method: addForm.method || 'POST',
                    headers: headers,
                    body: formData
                }).then(function(response) {
                    if (!response.ok) {
                        throw new Error('Request failed');
                    }
                    return response.json();
                }).then(function(data) {
                    if (data && Array.isArray(data.bannedWords)) {
                        input.value = '';
                        tagsContainer.innerHTML = buildTagsHtml(data.bannedWords);
                        attachDeleteHandlers();
                    }
                }).catch(function() {
                    showAdminToast('Une erreur est survenue.', 'error');
                }).finally(function() {
                    setAddFormDisabled(false);
                });
            });

            attachDeleteHandlers();
        })();
    </script>
    @endpush
@endsection
