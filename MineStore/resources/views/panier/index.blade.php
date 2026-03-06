@extends('layouts.app')

@section('title', 'Panier')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panier.css') }}">
@endpush

@section('content')
    <div class="panier-banner">
        <img src="{{ asset('images/banierP.png') }}" alt="Panier" class="panier-banner-image">
        <div class="panier-banner-title">
            <h1 class="panier-section-title">
                Panier
            </h1>
        </div>
    </div>

    @if(session('success') || session('error'))
        <div class="panier-alert-container">
            @if(session('success'))
                <div class="panier-alert panier-alert-success" id="panier-alert">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="panier-alert-close" onclick="document.getElementById('panier-alert').style.display='none'">×</button>
                </div>
            @elseif(session('error'))
                <div class="panier-alert panier-alert-error" id="panier-alert">
                    <span>{{ session('error') }}</span>
                    <button type="button" class="panier-alert-close" onclick="document.getElementById('panier-alert').style.display='none'">×</button>
                </div>
            @endif
        </div>
    @endif

    <div class="panier-page">
        <div class="panier-grid">
            <section class="panier-section">
                <div class="panier-section-header">
                    <h2 class="panier-section-subtitle">Votre panier</h2>
                </div>

                @if(!$panier || $lignes->isEmpty())
                    <p class="panier-text-muted" style="margin-top: 1rem;">
                        Votre panier est vide.
                    </p>
                @else
                    <div class="panier-table-wrapper">
                        <table class="panier-table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix TTC (unité)</th>
                                    <th>Prix HT (unité)</th>
                                    <th>Total TTC</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lignes as $ligne)
                                    @php
                                        $ligneTotal = $ligne->prix_snapshot * $ligne->quantite;
                                        $ligneHT = $ligne->prix_snapshot / 1.2;
                                    @endphp
                                    <tr>
                                        <td class="panier-produit-cell">
                                            <div class="panier-produit">
                                                <div class="panier-produit-image-wrapper">
                                                    <img src="{{ $ligne->image ? asset($ligne->image) : asset('images/placeholder-product.png') }}"
                                                         alt="{{ $ligne->nom }}"
                                                         class="panier-produit-image">
                                                </div>
                                                <div class="panier-produit-info">
                                                    <div class="panier-produit-nom">{{ $ligne->nom }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <form method="POST"
                                                  action="{{ route('panier.updateLine', $ligne->id_ligne_panier) }}"
                                                  class="panier-quantite-form"
                                                  data-ligne-id="{{ $ligne->id_ligne_panier }}">
                                                @csrf
                                                <input type="number"
                                                       name="quantite"
                                                       min="0"
                                                       value="{{ $ligne->quantite }}"
                                                       class="panier-quantite-input panier-quantite-input-live">
                                            </form>
                                        </td>
                                        <td class="panier-cell-prix-ttc">{{ number_format($ligne->prix_snapshot, 2, ',', ' ') }} €</td>
                                        <td class="panier-cell-prix-ht">{{ number_format($ligneHT, 2, ',', ' ') }} €</td>
                                        <td class="panier-cell-total-ttc">{{ number_format($ligneTotal, 2, ',', ' ') }} €</td>
                                        <td>
                                            <form method="POST" action="{{ route('panier.removeLine', $ligne->id_ligne_panier) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="panier-remove-button">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="panier-summary">
                        <div class="panier-summary-item">
                            <span>Total HT :</span>
                            <span>{{ number_format($totalHT, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="panier-summary-item">
                            <span>Total TTC :</span>
                            <span>{{ number_format($totalTTC, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="panier-summary-actions">
                            <form method="POST" action="{{ route('panier.checkout') }}">
                                @csrf
                                <button type="submit" class="panier-button panier-button-primary">
                                    Payer
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>

    <script>
        setTimeout(function () {
            var alert = document.getElementById('panier-alert');
            if (alert) {
                alert.style.transition = 'opacity 0.4s';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.style.display = 'none';
                }, 1000);
            }
        }, 4000);

        document.addEventListener('DOMContentLoaded', function () {
            var quantiteInputs = document.querySelectorAll('.panier-quantite-input-live');

            function formatPrix(value) {
                if (value === null || typeof value === 'undefined') {
                    return '0,00 €';
                }

                var n = Number(value);
                if (isNaN(n)) {
                    return '0,00 €';
                }

                return n.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' €';
            }

            quantiteInputs.forEach(function (input) {
                input.addEventListener('change', function () {
                    var form = input.closest('.panier-quantite-form');
                    if (!form) {
                        return;
                    }

                    var ligneId = form.getAttribute('data-ligne-id');
                    var quantite = parseInt(input.value, 10);

                    if (isNaN(quantite) || quantite < 0) {
                        quantite = 0;
                        input.value = '0';
                    }

                    var tokenInput = form.querySelector('input[name="_token"]');
                    var token = tokenInput ? tokenInput.value : '';

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({ quantite: quantite })
                    })
                        .then(function (response) {
                            if (!response.ok) {
                                throw new Error('Erreur réseau');
                            }
                            return response.json();
                        })
                        .then(function (data) {
                            if (!data || !data.success) {
                                return;
                            }

                            var row = form.closest('tr');

                            if (data.ligneSupprimee) {
                                if (row && row.parentNode) {
                                    row.parentNode.removeChild(row);
                                }
                            } else if (row) {
                                var cellPrixTTC = row.querySelector('.panier-cell-prix-ttc');
                                var cellPrixHT = row.querySelector('.panier-cell-prix-ht');
                                var cellTotalTTC = row.querySelector('.panier-cell-total-ttc');

                                if (cellPrixTTC && typeof data.prixUnitaireTTC !== 'undefined') {
                                    cellPrixTTC.textContent = formatPrix(data.prixUnitaireTTC);
                                }

                                if (cellPrixHT && typeof data.prixUnitaireHT !== 'undefined') {
                                    cellPrixHT.textContent = formatPrix(data.prixUnitaireHT);
                                }

                                if (cellTotalTTC && typeof data.ligneTotalTTC !== 'undefined') {
                                    cellTotalTTC.textContent = formatPrix(data.ligneTotalTTC);
                                }
                            }

                            var totalHTElement = document.querySelector('.panier-summary-item span:nth-child(2)');
                            var totalTTCElement = document.querySelector('.panier-summary-item:nth-child(2) span:nth-child(2)');

                            if (totalHTElement && typeof data.totalHT !== 'undefined') {
                                totalHTElement.textContent = formatPrix(data.totalHT);
                            }

                            if (totalTTCElement && typeof data.totalTTC !== 'undefined') {
                                totalTTCElement.textContent = formatPrix(data.totalTTC);
                            }
                        })
                        .catch(function (error) {
                            console.error(error);
                        });
                });
            });
        });
    </script>
@endsection
