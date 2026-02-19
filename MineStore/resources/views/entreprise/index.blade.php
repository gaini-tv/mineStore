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
                </div>
                <p class="entreprise-text-muted" style="margin-top: 0.75rem;">
                    Gérez les membres de votre entreprise et attribuez-leur des rôles adaptés&nbsp;:
                    propriétaire, manager, responsable produit, responsable stock ou rédacteur.
                    Chaque rôle aura prochainement des droits spécifiques sur les produits et le contenu.
                </p>

                @if($estProprietaire)
                    <div style="margin-top: 1.5rem;">
                        <button type="button" class="entreprise-button entreprise-button-primary" onclick="document.getElementById('entreprise-member-modal').style.display='flex'">
                            Ajouter un membre
                        </button>
                    </div>
                @else
                    <p class="entreprise-text-muted" style="margin-top: 1.5rem;">
                        Seul le propriétaire peut gérer l’équipe.
                    </p>
                @endif
            </section>
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
@endsection
