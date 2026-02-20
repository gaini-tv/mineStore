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
                            @else
                                <ul class="admin-card-list">
                                    @foreach($entreprises as $entreprise)
                                        <li class="admin-card" onclick="document.getElementById('entreprise-active-modal-{{ $entreprise->id_entreprise }}').style.display='flex'" style="cursor: pointer;">
                                            <div class="admin-card-title">{{ $entreprise->nom }}</div>
                                            <div class="admin-card-meta">{{ $entreprise->email_contact }}</div>
                                            <div class="admin-card-meta">{{ $entreprise->adresse }}</div>
                                        </li>
                                        <div id="entreprise-active-modal-{{ $entreprise->id_entreprise }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
                                            <div class="admin-modal-content">
                                                <h3 class="text-xl font-bold mb-3" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Détails de l’entreprise</h3>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Nom: {{ $entreprise->nom }}</p>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Email: {{ $entreprise->email_contact }}</p>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Adresse: {{ $entreprise->adresse }}</p>
                                                @if($entreprise->description)
                                                    <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Description: {{ $entreprise->description }}</p>
                                                @endif
                                                @if($entreprise->owner)
                                                    <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Propriétaire: {{ $entreprise->owner->prenom }} {{ $entreprise->owner->nom }} ({{ $entreprise->owner->email }})</p>
                                                @endif
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Créée le: {{ optional($entreprise->created_at)->format('d/m/Y H:i') }}</p>
                                                <p class="mb-4" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Membres: {{ $entreprise->users()->count() }}</p>
                                                <div class="text-right">
                                                    <button type="button" class="admin-button" onclick="document.getElementById('entreprise-active-modal-{{ $entreprise->id_entreprise }}').style.display='none'">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div>
                            <h3 class="admin-subtitle">Demandes de création</h3>
                            @if($demandesCreation->isEmpty())
                                <p class="admin-card-meta">Aucune demande de création en attente.</p>
                            @else
                                <ul class="admin-card-list">
                                    @foreach($demandesCreation as $demande)
                                        <li
                                            class="admin-card admin-entreprise-demand-card"
                                            data-entreprise-id="{{ $demande->id_entreprise }}"
                                            onclick="document.getElementById('entreprise-demand-modal-{{ $demande->id_entreprise }}').style.display='flex'"
                                            style="cursor: pointer;"
                                        >
                                            <div class="admin-card-title">{{ $demande->nom }}</div>
                                            <div class="admin-card-meta">{{ $demande->email_contact }}</div>
                                            <div class="admin-card-meta">{{ $demande->adresse }}</div>
                                        </li>
                                        <div id="entreprise-demand-modal-{{ $demande->id_entreprise }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
                                            <div class="admin-modal-content">
                                                <h3 class="text-xl font-bold mb-3" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Demande de création</h3>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Nom: {{ $demande->nom }}</p>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Email: {{ $demande->email_contact }}</p>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Adresse: {{ $demande->adresse }}</p>
                                                @if($demande->description)
                                                    <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Description: {{ $demande->description }}</p>
                                                @endif
                                                @if($demande->owner)
                                                    <p class="mb-4" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Demandeur : {{ $demande->owner->prenom }} {{ $demande->owner->nom }} ({{ $demande->owner->email }})</p>
                                                @endif
                                                <div class="flex justify-end gap-2">
                                                    <form
                                                        action="{{ route('admin.entreprises.approve', $demande) }}"
                                                        method="POST"
                                                        class="admin-async-form"
                                                        data-admin-action="entreprise-approve"
                                                        data-entreprise-id="{{ $demande->id_entreprise }}"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="admin-button admin-button-primary">Accepter</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.entreprises.refuse', $demande) }}"
                                                        method="POST"
                                                        class="admin-async-form"
                                                        data-admin-action="entreprise-refuse"
                                                        data-entreprise-id="{{ $demande->id_entreprise }}"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="admin-button admin-button-danger">Refuser</button>
                                                    </form>
                                                    <button type="button" class="admin-button" onclick="document.getElementById('entreprise-demand-modal-{{ $demande->id_entreprise }}').style.display='none'">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div>
                            <h3 class="admin-subtitle">Demandes de suppression</h3>
                            @if($demandesSuppression->isEmpty())
                                <p class="admin-card-meta">Aucune demande de suppression en attente.</p>
                            @else
                                <ul class="admin-card-list">
                                    @foreach($demandesSuppression as $demande)
                                        <li
                                            class="admin-card admin-entreprise-delete-card"
                                            data-entreprise-id="{{ $demande->id_entreprise }}"
                                            onclick="document.getElementById('entreprise-delete-modal-{{ $demande->id_entreprise }}').style.display='flex'"
                                            style="cursor: pointer;"
                                        >
                                            <div class="admin-card-title">{{ $demande->nom }}</div>
                                            <div class="admin-card-meta">{{ $demande->email_contact }}</div>
                                            <div class="admin-card-meta">{{ $demande->adresse }}</div>
                                        </li>
                                        <div id="entreprise-delete-modal-{{ $demande->id_entreprise }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="display: none;">
                                            <div class="admin-modal-content">
                                                <h3 class="text-xl font-bold mb-3" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Demande de suppression</h3>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Nom: {{ $demande->nom }}</p>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Email: {{ $demande->email_contact }}</p>
                                                <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Adresse: {{ $demande->adresse }}</p>
                                                @if($demande->description)
                                                    <p class="mb-2" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Description: {{ $demande->description }}</p>
                                                @endif
                                                @if($demande->owner)
                                                    <p class="mb-4" style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">Propriétaire : {{ $demande->owner->prenom }} {{ $demande->owner->nom }} ({{ $demande->owner->email }})</p>
                                                @endif
                                                <div class="flex justify-end gap-2">
                                                    <form
                                                        action="{{ route('admin.entreprises.approveDeletion', $demande) }}"
                                                        method="POST"
                                                        class="admin-async-form"
                                                        data-admin-action="entreprise-approve-deletion"
                                                        data-entreprise-id="{{ $demande->id_entreprise }}"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="admin-button admin-button-danger">Confirmer la suppression</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.entreprises.cancelDeletion', $demande) }}"
                                                        method="POST"
                                                        class="admin-async-form"
                                                        data-admin-action="entreprise-cancel-deletion"
                                                        data-entreprise-id="{{ $demande->id_entreprise }}"
                                                    >
                                                        @csrf
                                                        <button type="submit" class="admin-button admin-button-primary">Annuler la demande</button>
                                                    </form>
                                                    <button type="button" class="admin-button" onclick="document.getElementById('entreprise-delete-modal-{{ $demande->id_entreprise }}').style.display='none'">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </ul>
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
                        <div class="admin-category-list">
                            <h3 class="admin-subtitle">Catégories existantes</h3>
                            @if($categories->isEmpty())
                                <p class="admin-card-meta">Aucune catégorie enregistrée.</p>
                            @else
                                <div class="admin-card-list">
                                    @foreach($categories as $categorie)
                                        @php
                                            $isUncategorized = mb_strtolower(trim($categorie->nom)) === mb_strtolower('Non catégorisé');
                                        @endphp
                                        <div class="admin-card">
                                            <form action="{{ route('admin.categories.update', $categorie) }}" method="POST" class="space-y-2">
                                                @csrf
                                                @method('PUT')
                                                <input
                                                    type="text"
                                                    name="nom"
                                                    value="{{ $categorie->nom }}"
                                                    class="admin-input"
                                                    @if($isUncategorized) disabled @else required @endif
                                                >
                                                <textarea
                                                    name="description"
                                                    class="admin-textarea"
                                                    rows="2"
                                                    @if($isUncategorized) disabled @endif
                                                >{{ $categorie->description }}</textarea>
                                                <div class="admin-category-card-actions">
                                                    @unless($isUncategorized)
                                                        <button type="submit" class="admin-button admin-button-primary">
                                                            Mettre à jour
                                                        </button>
                                                    @endunless
                                                </div>
                                            </form>
                                            @unless($isUncategorized)
                                                <form
                                                    action="{{ route('admin.categories.destroy', $categorie) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Supprimer cette catégorie ?');"
                                                    class="mt-2 text-right"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="admin-icon-button admin-icon-button-delete">
                                                        <img src="{{ asset('images/cross.png') }}" alt="Supprimer">
                                                    </button>
                                                </form>
                                            @endunless
                                        </div>
                                    @endforeach
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

        document.querySelectorAll('[id^="entreprise-active-modal-"], [id^="entreprise-demand-modal-"], [id^="entreprise-delete-modal-"]').forEach(function(modal) {
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
    </script>
    @endpush
@endsection
