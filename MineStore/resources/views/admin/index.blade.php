@extends('layouts.app')

@section('title', 'Administration')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
                    <div class="overflow-x-auto">
                        <table class="admin-table">
                            <thead>
                                <tr class="border-b border-[#e3e3e0]">
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->prenom }} {{ $user->nom }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="admin-form-inline">
                                                @csrf
                                                <select name="role" class="admin-select">
                                                    <option value="user" @selected($user->role === 'user')>user</option>
                                                    <option value="admin" @selected($user->role === 'admin')>admin</option>
                                                    <option value="owner" @selected($user->role === 'owner')>owner</option>
                                                    <option value="manager" @selected($user->role === 'manager')>manager</option>
                                                    <option value="product_manager" @selected($user->role === 'product_manager')>product_manager</option>
                                                    <option value="stock_manager" @selected($user->role === 'stock_manager')>stock_manager</option>
                                                    <option value="editor" @selected($user->role === 'editor')>editor</option>
                                                </select>
                                                <button type="submit" class="admin-button admin-button-primary">
                                                    Mettre à jour
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                                        <li class="admin-card" onclick="document.getElementById('entreprise-demand-modal-{{ $demande->id_entreprise }}').style.display='flex'" style="cursor: pointer;">
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
                                                    <form action="{{ route('admin.entreprises.approve', $demande) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="admin-button admin-button-primary">Accepter</button>
                                                    </form>
                                                    <form action="{{ route('admin.entreprises.refuse', $demande) }}" method="POST">
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
                                        <li class="admin-card" onclick="document.getElementById('entreprise-delete-modal-{{ $demande->id_entreprise }}').style.display='flex'" style="cursor: pointer;">
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
                                                    <form action="{{ route('admin.entreprises.approveDeletion', $demande) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="admin-button admin-button-danger">Confirmer la suppression</button>
                                                    </form>
                                                    <form action="{{ route('admin.entreprises.cancelDeletion', $demande) }}" method="POST">
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
                                        <div class="admin-card">
                                            <form action="{{ route('admin.categories.update', $categorie) }}" method="POST" class="space-y-2">
                                                @csrf
                                                @method('PUT')
                                                <input
                                                    type="text"
                                                    name="nom"
                                                    value="{{ $categorie->nom }}"
                                                    class="admin-input"
                                                    required
                                                >
                                                <textarea
                                                    name="description"
                                                    class="admin-textarea"
                                                    rows="2"
                                                >{{ $categorie->description }}</textarea>
                                                <div class="admin-category-card-actions">
                                                    <button type="submit" class="admin-button admin-button-primary">
                                                        Mettre à jour
                                                    </button>
                                                </div>
                                            </form>
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
@endsection
