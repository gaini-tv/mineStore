@extends('layouts.app')

@section('title', 'Entreprise')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/entreprise.css') }}">
<link rel="stylesheet" href="{{ asset('css/modal-form.css') }}">
@endpush

@section('content')
    <div class="entreprise-banner">
        <img src="{{ asset('images/banierP.png') }}" alt="Entreprise" class="entreprise-banner-image">
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <h1 class="entreprise-section-title">
                Entreprise
                <br>
                <span class="entreprise-section-subtitle">{{ $entreprise->nom }}</span>
            </h1>
        </div>
    </div>

    @if(session('success') || session('error'))
        <div class="entreprise-alert-container">
            @if(session('success'))
                <div class="entreprise-alert entreprise-alert-success" id="entreprise-alert">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="entreprise-alert-close" onclick="document.getElementById('entreprise-alert').style.display='none'">×</button>
                </div>
            @elseif(session('error'))
                <div class="entreprise-alert entreprise-alert-error" id="entreprise-alert">
                    <span>{{ session('error') }}</span>
                    <button type="button" class="entreprise-alert-close" onclick="document.getElementById('entreprise-alert').style.display='none'">×</button>
                </div>
            @endif
        </div>
    @endif

    <div class="entreprise-page">
        <div class="entreprise-grid">
            <section class="entreprise-section">
                <div class="entreprise-section-header">
                    <h2 class="entreprise-section-title" style="font-size: 1.5rem;">Statistiques</h2>
                </div>
                <div class="entreprise-metrics">
                    <div class="entreprise-metric-card">
                        <div class="entreprise-metric-label">Produits en ligne</div>
                        <div class="entreprise-metric-value">{{ $produitsEnLigne }}</div>
                    </div>
                    <div class="entreprise-metric-card">
                        <div class="entreprise-metric-label">Produits vendus</div>
                        <div class="entreprise-metric-value">{{ $totalProduitsVendus }}</div>
                    </div>
                    <div class="entreprise-metric-card">
                        <div class="entreprise-metric-label">Meilleure vente</div>
                        <div class="entreprise-metric-value">
                            {{ $meilleureVente ? $meilleureVente->nom : 'Aucune vente' }}
                        </div>
                    </div>
                    <div class="entreprise-metric-card">
                        <div class="entreprise-metric-label">Nombre d’articles</div>
                        <div class="entreprise-metric-value">{{ $nombreArticles }}</div>
                    </div>
                </div>
                <div class="entreprise-metrics" style="margin-top: 1.5rem;">
                    @foreach($membresParRole as $role => $count)
                        <div class="entreprise-metric-card">
                            <div class="entreprise-metric-label">{{ ucfirst(str_replace('_', ' ', $role)) }}</div>
                            <div class="entreprise-metric-value">{{ $count }}</div>
                        </div>
                    @endforeach
                </div>

                @php
                    $user = auth()->user();
                    $peutVoirBenefices = $user && in_array($user->role, ['owner', 'manager']);
                    $estProprietaire = $user && $entreprise->user_id === $user->id && $user->role === 'owner';
                    $statutEntreprise = $entreprise->statut;
                @endphp

                @if($peutVoirBenefices)
                    <div style="margin-top: 1.5rem;">
                        <div class="entreprise-metric-label">Bénéfices des ventes</div>
                        <div class="entreprise-metric-value">{{ number_format($benefices, 2, ',', ' ') }} €</div>
                    </div>
                @endif

                @if($estProprietaire)
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        @if($statutEntreprise === 'active')
                            <button type="button" class="entreprise-button entreprise-button-danger" onclick="document.getElementById('entreprise-delete-modal').style.display='flex'">
                                Supprimer l’entreprise
                            </button>
                        @elseif($statutEntreprise === 'deletion_pending_email')
                            <button type="button" class="entreprise-button entreprise-button-danger" style="opacity: 0.6; cursor: default;" disabled>
                                En attente de confirmation par email du propriétaire
                            </button>
                        @elseif($statutEntreprise === 'deletion_requested')
                            <button type="button" class="entreprise-button entreprise-button-danger" style="opacity: 0.6; cursor: default;" disabled>
                                En attente de l’administration
                            </button>
                        @endif
                    </div>
                @endif
            </section>

            <section class="entreprise-section">
                <div class="entreprise-section-header">
                    <h2 class="entreprise-section-title" style="font-size: 1.5rem;">Gestion de l’équipe</h2>
                    @if($estProprietaire)
                        <div style="display:flex; gap:0.75rem;">
                            <button type="button" class="entreprise-button entreprise-button-primary" onclick="document.getElementById('entreprise-member-modal').style.display='flex'">
                                Ajouter un membre
                            </button>
                        </div>
                    @endif
                </div>
                <p class="entreprise-text-muted" style="margin-top: 0.75rem;">
                    Gérez les membres de votre entreprise, leurs rôles et leur appartenance à l’équipe.
                </p>

                @if(($membres ?? collect())->isEmpty())
                    <p class="entreprise-text-muted" style="margin-top: 0.75rem;">Aucun membre pour le moment.</p>
                @else
                    <div class="entreprise-search-container">
                        <input
                            type="text"
                            id="entreprise-team-search"
                            class="entreprise-search-input"
                            placeholder="Rechercher un membre (nom ou email)..."
                        >
                    </div>
                    <div class="entreprise-table-wrapper" style="margin-top: 1rem;">
                        <table class="entreprise-table" id="entreprise-team-table">
                            <colgroup>
                                <col style="width:112px;"><col><col><col><col><col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Prénom &amp; Nom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Téléphone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($membres as $membre)
                                    @php
                                        $avatarFile = $membre->avatar ?? 'base.png';
                                        $avatarUrl = asset('images/avatar/' . $avatarFile);
                                    @endphp
                                    <tr class="entreprise-table-row" data-member-id="{{ $membre->id }}" data-member-role="{{ $membre->role }}">
                                        <td>
                                            <img src="{{ $avatarUrl }}" alt="Avatar" class="entreprise-avatar">
                                        </td>
                                        <td>{{ trim(($membre->prenom ?? '') . ' ' . ($membre->nom ?? '')) ?: 'Utilisateur' }}</td>
                                        <td>{{ $membre->email }}</td>
                                        <td class="entreprise-member-role-cell">
                                            <span class="entreprise-member-role-label">{{ $membre->role }}</span>
                                        </td>
                                        <td>{{ $entreprise->telephone ?? 'Non renseigné' }}</td>
                                        <td>
                                            @if($estProprietaire && $membre->id !== $user->id)
                                                <div class="entreprise-table-actions">
                                                    <button type="button" class="entreprise-icon-button entreprise-icon-button-view open-role-select" title="Changer le rôle" data-member-id="{{ $membre->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M12 20h9"/>
                                                            <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4z"/>
                                                        </svg>
                                                    </button>
                                                    <form method="POST" action="{{ route('entreprise.members.remove', $membre) }}" onsubmit="return confirm('Retirer ce membre de l’entreprise ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="entreprise-icon-button entreprise-icon-button-print" title="Retirer de l’entreprise">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M3 6h18"/>
                                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                                <path d="M10 11v6"/>
                                                                <path d="M14 11v6"/>
                                                                <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="entreprise-text-muted">Lecture seule</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
            
            @if(isset($canManageProducts) && $canManageProducts)
            <section class="entreprise-section">
                <div class="entreprise-section-header">
                    <h2 class="entreprise-section-title" style="font-size: 1.5rem;">Produits</h2>
                    <div style="display:flex; gap:0.75rem;">
                        <button type="button" class="entreprise-button entreprise-button-primary" id="open-add-product-modal">
                            Ajouter un produit
                        </button>
                    </div>
                </div>
                @if(($produits ?? collect())->isEmpty())
                    <p class="entreprise-text-muted" style="margin-top: 0.75rem;">Aucun produit pour le moment.</p>
                @else
                    <div class="entreprise-search-container">
                        <input
                            type="text"
                            id="entreprise-products-search"
                            class="entreprise-search-input"
                            placeholder="Rechercher un produit ou une référence..."
                        >
                    </div>
                    <div class="entreprise-table-wrapper" style="margin-top: 1rem;">
                        <table class="entreprise-table" id="entreprise-products-table">
                            <colgroup>
                                <col><col><col><col><col><col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Référence</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produits as $p)
                                    @php
                                        $stockValue = $p->stock;
                                        $lowThreshold = $p->stock_low_threshold ?? 100;
                                        $infinite = $p->infinite_stock ?? false;
                                        $ruptureMk = $p->rupture_marketing ?? false;
                                        if ($ruptureMk) {
                                            $statutLabel = 'Rupture';
                                            $statutColor = '#b91c1c';
                                        } elseif ($infinite) {
                                            $statutLabel = 'En stock';
                                            $statutColor = '#5baa47';
                                        } elseif ($stockValue <= 0) {
                                            $statutLabel = 'Rupture';
                                            $statutColor = '#b91c1c';
                                        } elseif ($stockValue < $lowThreshold) {
                                            $statutLabel = 'Stock faible';
                                            $statutColor = '#ff9800';
                                        } else {
                                            $statutLabel = 'En stock';
                                            $statutColor = '#5baa47';
                                        }
                                    @endphp
                                    <tr class="profil-table-row"
                                        data-id="{{ $p->id_produit }}"
                                        data-nom="{{ $p->nom }}"
                                        data-reference="{{ $p->reference }}"
                                        data-prix="{{ $p->prix }}"
                                        data-stock="{{ $p->stock }}"
                                        data-stock-low-threshold="{{ $p->stock_low_threshold ?? 100 }}"
                                        data-infinite="{{ $p->infinite_stock ? '1' : '0' }}"
                                        data-rupture-marketing="{{ $p->rupture_marketing ? '1' : '0' }}"
                                        data-categorie-id="{{ $p->categories->first()->id_categorie ?? '' }}"
                                        data-pegi="{{ $p->pegi ?? '' }}"
                                    >
                                        <td>{{ $p->nom }}</td>
                                        <td>{{ $p->reference }}</td>
                                        <td>{{ number_format($p->prix, 2, ',', ' ') }} €</td>
                                        <td>{{ $p->infinite_stock ? '∞' : $p->stock }}</td>
                                        <td><span style="color: {{ $statutColor }}">{{ $statutLabel }}</span></td>
                                        <td>
                                            <div class="entreprise-table-actions">
                                                <button type="button" class="entreprise-icon-button entreprise-icon-button-view open-edit-product" title="Modifier" data-id="{{ $p->id_produit }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M12 20h9"/>
                                                        <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4z"/>
                                                    </svg>
                                                </button>
                                                <button type="button" class="entreprise-icon-button entreprise-icon-button-print open-delete-product" title="Supprimer" data-id="{{ $p->id_produit }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 6h18"/>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                        <path d="M10 11v6"/>
                                                        <path d="M14 11v6"/>
                                                        <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
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
            </section>
            @endif

            @if(isset($canManageStocks) && $canManageStocks)
            <section class="entreprise-section">
                <div class="entreprise-section-header">
                    <h2 class="entreprise-section-title" style="font-size: 1.5rem;">Stocks</h2>
                </div>
                @if(($produits ?? collect())->isEmpty())
                    <p class="entreprise-text-muted" style="margin-top: 0.75rem;">Aucun produit pour le moment.</p>
                @else
                    <div class="entreprise-search-container">
                        <input
                            type="text"
                            id="entreprise-stocks-search"
                            class="entreprise-search-input"
                            placeholder="Rechercher un produit ou une référence..."
                        >
                    </div>
                    <div class="entreprise-table-wrapper" style="margin-top: 1rem;">
                        <table class="entreprise-table" id="entreprise-stocks-table">
                            <colgroup>
                                <col><col><col><col><col><col><col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Référence</th>
                                    <th>Statut</th>
                                    <th>Stock</th>
                                    <th>Seuil</th>
                                    <th>Infini</th>
                                    <th>Rupture (marketing)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produits as $p)
                                    @php
                                        $stockValue = $p->stock;
                                        $lowThreshold = $p->stock_low_threshold ?? 100;
                                        $infinite = $p->infinite_stock ?? false;
                                        $ruptureMk = $p->rupture_marketing ?? false;
                                        if ($ruptureMk) {
                                            $statutLabel = 'Rupture';
                                            $statutColor = '#b91c1c';
                                        } elseif ($infinite) {
                                            $statutLabel = 'En stock';
                                            $statutColor = '#5baa47';
                                        } elseif ($stockValue <= 0) {
                                            $statutLabel = 'Rupture';
                                            $statutColor = '#b91c1c';
                                        } elseif ($stockValue < $lowThreshold) {
                                            $statutLabel = 'Stock faible';
                                            $statutColor = '#ff9800';
                                        } else {
                                            $statutLabel = 'En stock';
                                            $statutColor = '#5baa47';
                                        }
                                    @endphp
                                    <tr class="entreprise-table-row" data-id="{{ $p->id_produit }}">
                                        <td>{{ $p->nom }}</td>
                                        <td>{{ $p->reference }}</td>
                                        <td><span style="color: {{ $statutColor }}">{{ $statutLabel }}</span></td>
                                        <td>
                                            <input type="number" class="entreprise-input" style="max-width: 100px;" min="0" value="{{ $p->stock }}" data-field="stock">
                                        </td>
                                        <td>
                                            <input type="number" class="entreprise-input" style="max-width: 80px;" min="1" value="{{ $p->stock_low_threshold ?? 100 }}" data-field="stock_low_threshold">
                                        </td>
                                        <td>
                                            <label class="entreprise-text-muted" style="display:flex; align-items:center; gap:0.5rem;">
                                                <input type="checkbox" {{ $p->infinite_stock ? 'checked' : '' }} data-field="infinite_stock">
                                                <span>Stock infini</span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="entreprise-text-muted" style="display:flex; align-items:center; gap:0.5rem;">
                                                <input type="checkbox" {{ $p->rupture_marketing ? 'checked' : '' }} data-field="rupture_marketing">
                                                <span>Rupture</span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
            @endif

            @php
                $peutVoirArticles = $user && in_array($user->role, ['owner', 'manager', 'editor']);
            @endphp
            @if($peutVoirArticles)
                <section class="entreprise-section">
                    <div class="entreprise-section-header">
                        <h2 class="entreprise-section-title" style="font-size: 1.5rem;">Articles</h2>
                        <div style="display:flex; gap:0.75rem;">
                            <button type="button" class="entreprise-button entreprise-button-primary" id="open-add-article-modal">
                                Ajouter un article
                            </button>
                        </div>
                    </div>
                    @if(($articles ?? collect())->isEmpty())
                        <p class="entreprise-text-muted" style="margin-top: 0.75rem;">Aucun article pour le moment.</p>
                    @else
                        <div class="entreprise-search-container">
                            <input
                                type="text"
                                id="entreprise-articles-search"
                                class="entreprise-search-input"
                                placeholder="Rechercher un article..."
                            >
                        </div>
                        <div class="entreprise-table-wrapper" style="margin-top: 1rem;">
                            <table class="entreprise-table" id="entreprise-articles-table">
                                <colgroup>
                                    <col><col><col><col>
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Produit lié</th>
                                        <th>Description</th>
                                        <th>Dernière mise à jour</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $a)
                                        <tr class="entreprise-table-row"
                                            data-id="{{ $a->id_article }}"
                                            data-nom="{{ $a->nom }}"
                                            data-description="{{ $a->description }}"
                                            data-updated-at="{{ optional($a->updated_at)->format('Y-m-d H:i:s') }}"
                                        >
                                            <td>{{ $a->nom }}</td>
                                            <td>{{ $a->produit_nom }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($a->description, 120) }}</td>
                                            <td>{{ optional($a->updated_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div class="entreprise-table-actions">
                                                    <button type="button" class="entreprise-icon-button entreprise-icon-button-view open-edit-article" title="Modifier" data-id="{{ $a->id_article }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M12 20h9"/>
                                                            <path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4z"/>
                                                        </svg>
                                                    </button>
                                                    <button type="button" class="entreprise-icon-button entreprise-icon-button-print open-delete-article" title="Supprimer" data-id="{{ $a->id_article }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M3 6h18"/>
                                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                            <path d="M10 11v6"/>
                                                            <path d="M14 11v6"/>
                                                            <path d="M9 6V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2"/>
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
                </section>

                <div id="entreprise-add-article-modal" class="modal-form-backdrop hidden">
                    <div class="modal-form-container">
                        <div class="modal-form-header">
                            <h2 class="modal-form-title">Publier un article</h2>
                            <button type="button" class="modal-form-close-button" id="close-add-article-modal">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <form method="POST" action="{{ route('articles.store') }}" class="space-y-4">
                            @csrf
                            <div style="padding: 20px;">
                                <label class="modal-form-label">Nom</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="nom" class="modal-form-input" style="border: none;">
                                </div>
                            </div>
                            <div style="padding: 20px;">
                                <label class="modal-form-label">Produit lié</label>
                                <div class="modal-form-field-wrapper">
                                    <select name="produit_id" class="modal-form-select" style="border: none;">
                                        @foreach(($produits ?? []) as $p)
                                            <option value="{{ $p->id_produit }}">{{ $p->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="padding: 20px;">
                                <label class="modal-form-label">Description</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="description" class="modal-form-textarea" rows="6" style="border: none;"></textarea>
                                </div>
                            </div>
                            <div class="modal-form-footer">
                                <button type="button" id="cancel-add-article-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                                    Annuler
                                </button>
                                <button type="submit" class="px-4 py-2" style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                                    Publier
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="entreprise-edit-article-modal" class="modal-form-backdrop hidden">
                    <div class="modal-form-container">
                        <div class="modal-form-header">
                            <h2 class="modal-form-title">Modifier l’article</h2>
                            <button type="button" class="modal-form-close-button" id="close-edit-article-modal">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <form id="entreprise-edit-article-form" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div style="padding: 20px;">
                                <label class="modal-form-label">Nom</label>
                                <div class="modal-form-field-wrapper">
                                    <input type="text" name="nom" class="modal-form-input" style="border: none;" id="edit_article_nom">
                                </div>
                            </div>
                            <div style="padding: 20px;">
                                <label class="modal-form-label">Description</label>
                                <div class="modal-form-field-wrapper">
                                    <textarea name="description" class="modal-form-textarea" rows="6" style="border: none;" id="edit_article_description"></textarea>
                                </div>
                            </div>
                            <div class="modal-form-footer">
                                <button type="button" id="cancel-edit-article-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                                    Annuler
                                </button>
                                <button type="submit" class="px-4 py-2" style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="entreprise-delete-article-modal" class="modal-form-backdrop hidden">
                    <div class="modal-form-container">
                        <div class="modal-form-header">
                            <h2 class="modal-form-title">Supprimer l’article</h2>
                            <button type="button" class="modal-form-close-button" id="close-delete-article-modal">
                                <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                            </button>
                        </div>
                        <div style="padding: 20px;">
                            <p style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                                Êtes-vous sûr de vouloir supprimer cet article ?
                            </p>
                        </div>
                        <form id="entreprise-delete-article-form" method="POST" class="space-y-4">
                            @csrf
                            @method('DELETE')
                            <div class="modal-form-footer">
                                <button type="button" id="cancel-delete-article-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                                    Annuler
                                </button>
                                <button type="submit" class="px-4 py-2" style="background-color: #b91c1c; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #7f1d1d; cursor: pointer;">
                                    Supprimer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(isset($estProprietaire) && $estProprietaire)
        <div id="entreprise-delete-modal" class="entreprise-modal-backdrop" style="display: none;">
            <div class="entreprise-modal-content">
                <h3 style="font-family: 'Minecrafter Alt', sans-serif; font-size: 1.25rem; margin-bottom: 1rem;">Confirmer la suppression</h3>
                @php
                    $email = $user->email ?? '';
                    $masked = $email;
                    if (strpos($email, '@') !== false) {
                        [$local, $domain] = explode('@', $email, 2);
                        $masked = str_repeat('*', max(4, strlen($local))) . '@' . $domain;
                    }
                @endphp
                <p class="entreprise-text-muted" style="margin-bottom: 1.5rem;">
                    Un email de confirmation sera envoyé à votre adresse {{ $masked }} (valide 15 minutes).
                    Après confirmation, la demande sera transmise à l’administration pour validation.
                </p>
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="entreprise-button" onclick="document.getElementById('entreprise-delete-modal').style.display='none'">
                        Annuler
                    </button>
                    <form method="POST" action="{{ route('entreprise.requestDeletion') }}">
                        @csrf
                        <button type="submit" class="entreprise-button entreprise-button-danger">
                            Confirmer la demande
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="entreprise-member-modal" class="modal-form-backdrop" style="display: none;">
            <div class="modal-form-container" style="max-width: 500px;">
                <h3 class="modal-form-title" style="font-size: 1.25rem; margin-bottom: 1rem;">Ajouter un membre</h3>
                <form method="POST" action="{{ route('entreprise.addMember') }}">
                    @csrf
                    <div style="margin-bottom: 0.75rem;">
                        <label class="entreprise-text-muted" style="display: block; margin-bottom: 0.25rem;">Email de l’utilisateur</label>
                        <input type="email" name="email" required class="entreprise-input">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label class="entreprise-text-muted" style="display: block; margin-bottom: 0.25rem;">Rôle dans l’entreprise</label>
                        <select name="role" class="entreprise-select" required>
                            <option value="manager">Manager</option>
                            <option value="product_manager">Responsable produit</option>
                            <option value="stock_manager">Responsable stock</option>
                            <option value="editor">Rédacteur</option>
                        </select>
                    </div>
                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                        <button type="button" class="entreprise-button" onclick="document.getElementById('entreprise-member-modal').style.display='none'">
                            Annuler
                        </button>
                        <button type="submit" class="entreprise-button entreprise-button-primary">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <script>
        setTimeout(function () {
            var alert = document.getElementById('entreprise-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.4s';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.style.display = 'none';
                }, 1000);
            }
        }, 4000);

        var entrepriseMemberModal = document.getElementById('entreprise-member-modal');
        if (entrepriseMemberModal) {
            entrepriseMemberModal.addEventListener('click', function(e) {
                if (e.target === entrepriseMemberModal) {
                    entrepriseMemberModal.style.display = 'none';
                }
            });
        }
    </script>
    @if(isset($canManageProducts) && $canManageProducts)
    <div id="entreprise-add-product-modal" class="modal-form-backdrop hidden">
        <div class="modal-form-container">
            <div class="modal-form-header">
                <h2 class="modal-form-title">Ajouter un produit</h2>
                <button type="button" class="modal-form-close-button" id="close-add-product-modal">
                    <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                </button>
            </div>
            <form method="POST" action="{{ route('produits.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                    <div>
                        <label class="modal-form-label">Nom</label>
                        <div class="modal-form-field-wrapper">
                            <input type="text" name="nom" class="modal-form-input" style="border: none;">
                        </div>
                    </div>
                    <div>
                        <label class="modal-form-label">Référence</label>
                        <div class="modal-form-field-wrapper">
                            <input type="text" name="reference" class="modal-form-input" style="border: none;">
                        </div>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <label class="modal-form-label">Description</label>
                    <div class="modal-form-field-wrapper">
                        <textarea name="description" class="modal-form-textarea" rows="4" style="border: none;"></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                    <div>
                        <label class="modal-form-label">Prix (€)</label>
                        <div class="modal-form-field-wrapper">
                            <input type="number" name="prix" step="0.01" min="0" class="modal-form-input" style="border: none;">
                        </div>
                    </div>
                    <div>
                        <label class="modal-form-label" style="margin-bottom: 0.25rem;">
                            <input type="checkbox" name="infinite_stock" value="1" class="h-4 w-4" style="margin-right: 0.5rem;">
                            Stock infini
                        </label>
                        <label class="modal-form-label">Stock</label>
                        <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                            <input type="number" name="stock" min="0" class="modal-form-input" style="border: none;">
                        </div>
                        <label class="modal-form-label">Seuil "stock faible"</label>
                        <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                            <input type="number" name="stock_low_threshold" min="1" value="100" class="modal-form-input" style="border: none;">
                        </div>
                        <label class="modal-form-label">PEGI (optionnel)</label>
                        <div class="modal-form-field-wrapper">
                            <select name="pegi" class="modal-form-select" style="border: none;">
                                <option value="">Aucun</option>
                                <option value="images/pegi3.png">PEGI 3</option>
                                <option value="images/pegi7.png">PEGI 7</option>
                                <option value="images/pegi12.png">PEGI 12</option>
                                <option value="images/pegi16.png">PEGI 16</option>
                                <option value="images/pegi18.png">PEGI 18</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                    <div>
                        <label class="modal-form-label">Catégorie</label>
                        <div class="modal-form-field-wrapper">
                            <select name="categorie_id" class="modal-form-select" style="border: none;">
                                @foreach(($categories ?? []) as $cat)
                                    <option value="{{ $cat->id_categorie }}" @if(mb_strtolower(trim($cat->nom)) === mb_strtolower('Non catégorisé')) selected @endif>{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="modal-form-label">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-white rounded border border-[#e3e3e0]" style="font-family: 'Minecrafter Alt', sans-serif;">
                    </div>
                </div>
                <div class="modal-form-footer">
                    <button type="button" id="cancel-add-product-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2" style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="entreprise-edit-product-modal" class="modal-form-backdrop hidden">
        <div class="modal-form-container">
            <div class="modal-form-header">
                <h2 class="modal-form-title">Modifier le produit</h2>
                <button type="button" class="modal-form-close-button" id="close-edit-product-modal">
                    <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                </button>
            </div>
            <form id="entreprise-edit-product-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                    <div>
                        <label class="modal-form-label">Nom</label>
                        <div class="modal-form-field-wrapper">
                            <input type="text" name="nom" class="modal-form-input" style="border: none;" id="edit_nom">
                        </div>
                    </div>
                    <div>
                        <label class="modal-form-label">Référence</label>
                        <div class="modal-form-field-wrapper">
                            <input type="text" name="reference" class="modal-form-input" style="border: none;" id="edit_reference">
                        </div>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <label class="modal-form-label">Description</label>
                    <div class="modal-form-field-wrapper">
                        <textarea name="description" class="modal-form-textarea" rows="4" style="border: none;" id="edit_description"></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4" style="padding: 20px;">
                    <div>
                        <label class="modal-form-label">Prix (€)</label>
                        <div class="modal-form-field-wrapper">
                            <input type="number" name="prix" step="0.01" min="0" class="modal-form-input" style="border: none;" id="edit_prix">
                        </div>
                        <label class="modal-form-label" style="margin-top: 1rem; margin-bottom: 0.25rem;">
                            <input type="checkbox" name="infinite_stock" value="1" class="h-4 w-4" style="margin-right: 0.5rem;" id="edit_infinite_stock">
                            Stock infini
                        </label>
                        <label class="modal-form-label">Stock</label>
                        <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                            <input type="number" name="stock" min="0" class="modal-form-input" style="border: none;" id="edit_stock">
                        </div>
                        <label class="modal-form-label">Seuil "stock faible"</label>
                        <div class="modal-form-field-wrapper" style="margin-bottom: 0.5rem;">
                            <input type="number" name="stock_low_threshold" min="1" class="modal-form-input" style="border: none;" id="edit_stock_low_threshold">
                        </div>
                        <label class="modal-form-label" style="margin-top: 0.5rem; margin-bottom: 0.25rem;">
                            <input type="checkbox" name="rupture_marketing" value="1" class="h-4 w-4" style="margin-right: 0.5rem;" id="edit_rupture_marketing">
                            Rupture (marketing)
                        </label>
                    </div>
                    <div>
                        <label class="modal-form-label">Catégorie</label>
                        <div class="modal-form-field-wrapper">
                            <select name="categorie_id" class="modal-form-select" style="border: none;" id="edit_categorie_id">
                                @foreach(($categories ?? []) as $cat)
                                    <option value="{{ $cat->id_categorie }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="modal-form-label" style="margin-top: 1rem;">PEGI (optionnel)</label>
                        <div class="modal-form-field-wrapper">
                            <select name="pegi" class="modal-form-select" style="border: none;" id="edit_pegi">
                                <option value="">Aucun</option>
                                <option value="images/pegi3.png">PEGI 3</option>
                                <option value="images/pegi7.png">PEGI 7</option>
                                <option value="images/pegi12.png">PEGI 12</option>
                                <option value="images/pegi16.png">PEGI 16</option>
                                <option value="images/pegi18.png">PEGI 18</option>
                            </select>
                        </div>
                        <label class="modal-form-label" style="margin-top: 1rem;">Image (laisser vide pour conserver l'actuelle)</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 bg-white rounded border border-[#e3e3e0]" style="font-family: 'Minecrafter Alt', sans-serif;">
                    </div>
                </div>
                <div class="modal-form-footer">
                    <button type="button" id="cancel-edit-product-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2" style="background-color: #5baa47; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #3f7c33; cursor: pointer;">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="entreprise-delete-product-modal" class="modal-form-backdrop hidden">
        <div class="modal-form-container">
            <div class="modal-form-header">
                <h2 class="modal-form-title">Supprimer le produit</h2>
                <button type="button" class="modal-form-close-button" id="close-delete-product-modal">
                    <img src="{{ asset('images/cross.png') }}" alt="Fermer" class="h-6 w-6">
                </button>
            </div>
            <div style="padding: 20px;">
                <p style="font-family: 'Minecrafter Alt', sans-serif; color: #1b1b18;">
                    Êtes-vous sûr de vouloir supprimer ce produit ?<br>
                    Cette action supprimera également toutes les données associées ainsi que l'image du produit.
                </p>
            </div>
            <form id="entreprise-delete-product-form" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')
                <div class="modal-form-footer">
                    <button type="button" id="cancel-delete-product-btn" class="px-4 py-2" style="background-color: #e5e7eb; color: #1f2933; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #9ca3af; cursor: pointer;">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2" style="background-color: #b91c1c; color: #ffffff; font-family: 'Minecrafter Alt', sans-serif; border: 2px solid #7f1d1d; cursor: pointer;">
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const addBtn = document.getElementById('open-add-product-modal');
            const addModal = document.getElementById('entreprise-add-product-modal');
            const closeAddBtn = document.getElementById('close-add-product-modal');
            const cancelAddBtn = document.getElementById('cancel-add-product-btn');
            const editModal = document.getElementById('entreprise-edit-product-modal');
            const deleteModal = document.getElementById('entreprise-delete-product-modal');
            const cancelEditBtn = document.getElementById('cancel-edit-product-btn');
            const closeEditBtn = document.getElementById('close-edit-product-modal');
            const cancelDeleteBtn = document.getElementById('cancel-delete-product-btn');
            const closeDeleteBtn = document.getElementById('close-delete-product-modal');

            if (addBtn && addModal) {
                addBtn.addEventListener('click', function() {
                    addModal.classList.remove('hidden');
                });
            }
            [closeAddBtn, cancelAddBtn].forEach(btn => {
                if (btn && addModal) {
                    btn.addEventListener('click', function() {
                        addModal.classList.add('hidden');
                    });
                }
            });

            function openEditForRow(row) {
                const id = row.getAttribute('data-id');
                const form = document.getElementById('entreprise-edit-product-form');
                form.setAttribute('action', '{{ url('/produits') }}/' + id);
                document.getElementById('edit_nom').value = row.getAttribute('data-nom') || '';
                document.getElementById('edit_reference').value = row.getAttribute('data-reference') || '';
                document.getElementById('edit_description').value = row.getAttribute('data-description') || '';
                document.getElementById('edit_prix').value = row.getAttribute('data-prix') || '';
                document.getElementById('edit_stock').value = row.getAttribute('data-stock') || 0;
                document.getElementById('edit_stock_low_threshold').value = row.getAttribute('data-stock-low-threshold') || 100;
                document.getElementById('edit_infinite_stock').checked = row.getAttribute('data-infinite') === '1';
                document.getElementById('edit_rupture_marketing').checked = row.getAttribute('data-rupture-marketing') === '1';
                const catId = row.getAttribute('data-categorie-id') || '';
                const selectCat = document.getElementById('edit_categorie_id');
                if (selectCat && catId) {
                    selectCat.value = catId;
                }
                const pegi = row.getAttribute('data-pegi') || '';
                const selectPegi = document.getElementById('edit_pegi');
                if (selectPegi) {
                    selectPegi.value = pegi;
                }
                editModal.classList.remove('hidden');
            }

            document.querySelectorAll('#entreprise-products-table .open-edit-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const row = this.closest('tr');
                    openEditForRow(row);
                });
            });
            [cancelEditBtn, closeEditBtn].forEach(btn => {
                if (btn && editModal) {
                    btn.addEventListener('click', function() {
                        editModal.classList.add('hidden');
                    });
                }
            });

            document.querySelectorAll('#entreprise-products-table .open-delete-product').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const form = document.getElementById('entreprise-delete-product-form');
                    form.setAttribute('action', '{{ url('/produits') }}/' + id);
                    deleteModal.classList.remove('hidden');
                });
            });
            [cancelDeleteBtn, closeDeleteBtn].forEach(btn => {
                if (btn && deleteModal) {
                    btn.addEventListener('click', function() {
                        deleteModal.classList.add('hidden');
                    });
                }
            });

            const stockTable = document.getElementById('entreprise-stocks-table');
            function sendStockUpdate(id, payload) {
                fetch('{{ url('/produits') }}/' + id + '/stock', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                }).then(r => r.json()).then(json => {
                    if (!json || !json.success) return;
                    const idNum = String(json.id);
                    const state = {
                        stock: parseInt(json.stock, 10) || 0,
                        threshold: parseInt(json.stock_low_threshold, 10) || 100,
                        infinite: !!json.infinite_stock,
                        rupture: !!json.rupture_marketing
                    };
                    refreshStatusUI(idNum, state);
                }).catch(() => {});
            }
            if (stockTable) {
                stockTable.querySelectorAll('tr[data-id]').forEach(row => {
                    const id = row.getAttribute('data-id');
                    row.querySelectorAll('input[data-field]').forEach(input => {
                        input.addEventListener('change', function() {
                            const field = this.getAttribute('data-field');
                            let value;
                            if (this.type === 'checkbox') {
                                value = this.checked ? 1 : 0;
                            } else {
                                value = parseInt(this.value, 10);
                                if (isNaN(value)) value = 0;
                            }
                            const payload = {};
                            payload[field] = value;
                            // Mise à jour du statut côté client immédiatement
                            const currentState = {
                                stock: parseInt(row.querySelector('input[data-field=\"stock\"]').value, 10) || 0,
                                threshold: parseInt(row.querySelector('input[data-field=\"stock_low_threshold\"]').value, 10) || 100,
                                infinite: !!row.querySelector('input[data-field=\"infinite_stock\"]').checked,
                                rupture: !!row.querySelector('input[data-field=\"rupture_marketing\"]').checked
                            };
                            // appliquer la modification courante au state avant envoi
                            if (field === 'stock') currentState.stock = value;
                            if (field === 'stock_low_threshold') currentState.threshold = value;
                            if (field === 'infinite_stock') currentState.infinite = !!value;
                            if (field === 'rupture_marketing') currentState.rupture = !!value;
                            refreshStatusUI(id, currentState);
                            sendStockUpdate(id, payload);
                        });
                    });
                });
            }

            function computeStatus(state) {
                let label = 'En stock';
                let color = '#5baa47';
                if (state.rupture) {
                    label = 'Rupture';
                    color = '#b91c1c';
                } else if (state.infinite) {
                    label = 'En stock';
                    color = '#5baa47';
                } else if (state.stock <= 0) {
                    label = 'Rupture';
                    color = '#b91c1c';
                } else if (state.stock < state.threshold) {
                    label = 'Stock faible';
                    color = '#ff9800';
                } else {
                    label = 'En stock';
                    color = '#5baa47';
                }
                return { label, color };
            }

            function refreshStatusUI(productId, state) {
                const status = computeStatus(state);
                const stockRow = stockTable ? stockTable.querySelector('tr[data-id=\"' + productId + '\"]') : null;
                if (stockRow) {
                    const statusCell = stockRow.querySelector('td:nth-child(3) span');
                    if (statusCell) {
                        statusCell.textContent = status.label;
                        statusCell.style.color = status.color;
                    }
                }
                const productsTable = document.getElementById('entreprise-products-table');
                if (productsTable) {
                    const prodRow = productsTable.querySelector('tr[data-id=\"' + productId + '\"]');
                    if (prodRow) {
                        const statutCell = prodRow.querySelector('td:nth-child(5) span');
                        if (statutCell) {
                            statutCell.textContent = status.label;
                            statutCell.style.color = status.color;
                        }
                        const stockCell = prodRow.querySelector('td:nth-child(4)');
                        if (stockCell) {
                            stockCell.textContent = state.infinite ? '∞' : String(state.stock);
                        }
                        prodRow.setAttribute('data-stock', String(state.stock));
                        prodRow.setAttribute('data-stock-low-threshold', String(state.threshold));
                        prodRow.setAttribute('data-infinite', state.infinite ? '1' : '0');
                        prodRow.setAttribute('data-rupture-marketing', state.rupture ? '1' : '0');
                    }
                }
            }

            function normalizeText(value) {
                return (value || '')
                    .toString()
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '');
            }

            function setupSearchAndSort(options) {
                const table = document.getElementById(options.tableId);
                if (!table) return;
                const tbody = table.querySelector('tbody');
                if (!tbody) return;
                const allRows = Array.from(tbody.querySelectorAll('tr'));
                const searchInput = document.getElementById(options.searchInputId);

                let currentSortIndex = null;
                let currentSortDir = 'asc';

                function getCellValue(row, colIndex, spec) {
                    if (spec.type === 'boolean') {
                        const input = row.querySelector(spec.selector);
                        return input && input.checked ? 1 : 0;
                    }
                    const cell = row.cells[colIndex];
                    if (!cell) return spec.type === 'number' ? 0 : '';
                    if (spec.type === 'number') {
                        const raw = cell.textContent || '';
                        const cleaned = raw.replace(/[^\d,.-]/g, '').replace(',', '.');
                        const num = parseFloat(cleaned);
                        return isNaN(num) ? 0 : num;
                    }
                    return normalizeText(cell.textContent || '');
                }

                function applySearchAndSort() {
                    const query = searchInput ? normalizeText(searchInput.value) : '';
                    let rows = allRows;

                    if (searchInput && query) {
                        rows = rows.filter(row => {
                            const cells = row.cells;
                            for (let i = 0; i < options.searchColumns.length; i++) {
                                const colIndex = options.searchColumns[i];
                                const cell = cells[colIndex];
                                if (!cell) continue;
                                const text = normalizeText(cell.textContent || '');
                                if (text.indexOf(query) !== -1) {
                                    return true;
                                }
                            }
                            return false;
                        });
                    }

                    if (currentSortIndex !== null && options.sortable[currentSortIndex]) {
                        const spec = options.sortable[currentSortIndex];
                        rows = rows.slice().sort((a, b) => {
                            const aVal = getCellValue(a, currentSortIndex, spec);
                            const bVal = getCellValue(b, currentSortIndex, spec);
                            if (aVal < bVal) return currentSortDir === 'asc' ? -1 : 1;
                            if (aVal > bVal) return currentSortDir === 'asc' ? 1 : -1;
                            return 0;
                        });
                    }

                    tbody.innerHTML = '';
                    rows.forEach(row => tbody.appendChild(row));
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        applySearchAndSort();
                    });
                }

                const headers = Array.from(table.querySelectorAll('thead th'));
                const headerIcons = new Map();
                headers.forEach((th, index) => {
                    const spec = options.sortable[index];
                    if (!spec) return;
                    th.style.cursor = 'pointer';
                    const labelText = th.textContent;
                    th.textContent = '';
                    const wrapper = document.createElement('span');
                    wrapper.className = 'entreprise-sortable-header';
                    const labelSpan = document.createElement('span');
                    labelSpan.textContent = labelText;
                    const iconSpan = document.createElement('span');
                    iconSpan.className = 'entreprise-sort-icon';
                    iconSpan.textContent = '';
                    wrapper.appendChild(labelSpan);
                    wrapper.appendChild(iconSpan);
                    th.appendChild(wrapper);
                    headerIcons.set(index, iconSpan);
                    th.addEventListener('click', function() {
                        if (currentSortIndex === index) {
                            currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
                        } else {
                            currentSortIndex = index;
                            currentSortDir = 'asc';
                        }
                        headerIcons.forEach((icon, i) => {
                            if (i === currentSortIndex) {
                                icon.textContent = currentSortDir === 'asc' ? '▲' : '▼';
                            } else {
                                icon.textContent = '';
                            }
                        });
                        applySearchAndSort();
                    });
                });

                applySearchAndSort();
            }

            setupSearchAndSort({
                tableId: 'entreprise-products-table',
                searchInputId: 'entreprise-products-search',
                searchColumns: [0, 1],
                sortable: {
                    0: { type: 'text' },
                    2: { type: 'number' },
                    3: { type: 'number' },
                    4: { type: 'text' }
                }
            });

            setupSearchAndSort({
                tableId: 'entreprise-stocks-table',
                searchInputId: 'entreprise-stocks-search',
                searchColumns: [0, 1],
                sortable: {
                    0: { type: 'text' },
                    2: { type: 'text' },
                    3: { type: 'number' },
                    5: { type: 'boolean', selector: 'input[data-field=\"infinite_stock\"]' },
                    6: { type: 'boolean', selector: 'input[data-field=\"rupture_marketing\"]' }
                }
            });

            setupSearchAndSort({
                tableId: 'entreprise-team-table',
                searchInputId: 'entreprise-team-search',
                searchColumns: [1, 2],
                sortable: {
                    1: { type: 'text' },
                    2: { type: 'text' },
                    3: { type: 'text' }
                }
            });

            // Inline changement de rôle (AJAX)
            document.querySelectorAll('#entreprise-team-table .open-role-select').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    if (!row) return;
                    const roleCell = row.querySelector('.entreprise-member-role-cell');
                    if (!roleCell) return;
                    // Empêcher d'ouvrir plusieurs sélecteurs
                    if (roleCell.querySelector('select')) return;

                    const currentRole = (row.getAttribute('data-member-role') || '').trim();
                    const memberId = row.getAttribute('data-member-id');

                    const select = document.createElement('select');
                    select.className = 'entreprise-select';
                    ['manager','product_manager','stock_manager','editor'].forEach(value => {
                        const opt = document.createElement('option');
                        opt.value = value;
                        opt.textContent = value === 'manager' ? 'Manager'
                            : value === 'product_manager' ? 'Responsable produit'
                            : value === 'stock_manager' ? 'Responsable stock'
                            : 'Rédacteur';
                        if (value === currentRole) opt.selected = true;
                        select.appendChild(opt);
                    });

                    const labelSpan = roleCell.querySelector('.entreprise-member-role-label');
                    if (labelSpan) labelSpan.style.display = 'none';
                    roleCell.appendChild(select);
                    select.focus();

                    const updateUrl = '{{ url('/entreprise/members') }}' + '/' + memberId + '/role';
                    select.addEventListener('change', function() {
                        const newRole = this.value;
                        fetch(updateUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ role: newRole })
                        }).then(() => {
                            // Mettre à jour l'UI
                            row.setAttribute('data-member-role', newRole);
                            if (labelSpan) {
                                labelSpan.textContent = newRole;
                                labelSpan.style.display = '';
                            }
                            select.remove();
                        }).catch(() => {
                            // Annuler en cas d'erreur
                            if (labelSpan) labelSpan.style.display = '';
                            select.remove();
                        });
                    });

                    // Fermer si perte de focus sans changement
                    select.addEventListener('blur', function() {
                        setTimeout(() => {
                            if (roleCell.contains(select)) {
                                if (labelSpan) labelSpan.style.display = '';
                                select.remove();
                            }
                        }, 150);
                    });
                });
            });

            // Articles: modales et actions
            const addArticleBtn = document.getElementById('open-add-article-modal');
            const addArticleModal = document.getElementById('entreprise-add-article-modal');
            const closeAddArticleBtn = document.getElementById('close-add-article-modal');
            const cancelAddArticleBtn = document.getElementById('cancel-add-article-btn');
            const editArticleModal = document.getElementById('entreprise-edit-article-modal');
            const deleteArticleModal = document.getElementById('entreprise-delete-article-modal');
            const closeEditArticleBtn = document.getElementById('close-edit-article-modal');
            const cancelEditArticleBtn = document.getElementById('cancel-edit-article-btn');
            const closeDeleteArticleBtn = document.getElementById('close-delete-article-modal');
            const cancelDeleteArticleBtn = document.getElementById('cancel-delete-article-btn');

            if (addArticleBtn && addArticleModal) {
                addArticleBtn.addEventListener('click', function() {
                    addArticleModal.classList.remove('hidden');
                });
            }
            [closeAddArticleBtn, cancelAddArticleBtn].forEach(btn => {
                if (btn && addArticleModal) {
                    btn.addEventListener('click', function() {
                        addArticleModal.classList.add('hidden');
                    });
                }
            });

            function openEditArticleForRow(row) {
                const id = row.getAttribute('data-id');
                const form = document.getElementById('entreprise-edit-article-form');
                form.setAttribute('action', '{{ url('/articles') }}/' + id);
                document.getElementById('edit_article_nom').value = row.getAttribute('data-nom') || '';
                document.getElementById('edit_article_description').value = row.getAttribute('data-description') || '';
                editArticleModal.classList.remove('hidden');
            }
            document.querySelectorAll('#entreprise-articles-table .open-edit-article').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    openEditArticleForRow(row);
                });
            });
            [closeEditArticleBtn, cancelEditArticleBtn].forEach(btn => {
                if (btn && editArticleModal) {
                    btn.addEventListener('click', function() {
                        editArticleModal.classList.add('hidden');
                    });
                }
            });

            document.querySelectorAll('#entreprise-articles-table .open-delete-article').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const form = document.getElementById('entreprise-delete-article-form');
                    form.setAttribute('action', '{{ url('/articles') }}/' + id);
                    deleteArticleModal.classList.remove('hidden');
                });
            });
            [closeDeleteArticleBtn, cancelDeleteArticleBtn].forEach(btn => {
                if (btn && deleteArticleModal) {
                    btn.addEventListener('click', function() {
                        deleteArticleModal.classList.add('hidden');
                    });
                }
            });

            setupSearchAndSort({
                tableId: 'entreprise-articles-table',
                searchInputId: 'entreprise-articles-search',
                searchColumns: [0, 1],
                sortable: {
                    0: { type: 'text' },
                    2: { type: 'text' }
                }
            });
        })();
    </script>
    @endpush
@endsection
