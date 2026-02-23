<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanierController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $panier = $this->getActivePanierForUser($user->id);

        if (!$panier) {
            return view('panier.index', [
                'panier' => null,
                'lignes' => collect(),
                'totalTTC' => 0,
                'totalHT' => 0,
            ]);
        }

        $lignes = DB::table('ligne_paniers')
            ->join('produits', 'ligne_paniers.produit_id', '=', 'produits.id_produit')
            ->where('ligne_paniers.panier_id', $panier->id_panier)
            ->select(
                'ligne_paniers.id_ligne_panier',
                'ligne_paniers.quantite',
                'ligne_paniers.prix_snapshot',
                'produits.id_produit',
                'produits.nom',
                'produits.image'
            )
            ->get();

        $totalTTC = 0;
        $totalHT = 0;

        foreach ($lignes as $ligne) {
            $ligneTotal = $ligne->prix_snapshot * $ligne->quantite;
            $totalTTC += $ligneTotal;
            $totalHT += $ligneTotal / 1.2;
        }

        return view('panier.index', [
            'panier' => $panier,
            'lignes' => $lignes,
            'totalTTC' => $totalTTC,
            'totalHT' => $totalHT,
        ]);
    }

    public function add(Request $request, $id)
    {
        $user = Auth::user();

        $produit = Produit::where('id_produit', $id)
            ->where('actif', true)
            ->firstOrFail();

        $panier = $this->getOrCreateActivePanierForUser($user->id);

        $ligne = DB::table('ligne_paniers')
            ->where('panier_id', $panier->id_panier)
            ->where('produit_id', $produit->id_produit)
            ->first();

        if ($produit->rupture_marketing) {
            return redirect()->route('panier.index')->with('error', 'Ce produit est actuellement en rupture.');
        }

        if (!$produit->infinite_stock) {
            if ($produit->stock <= 0) {
                return redirect()->route('panier.index')->with('error', 'Ce produit n\'est plus en stock.');
            }
        }

        if ($ligne) {
            $nouvelleQuantite = $ligne->quantite + 1;

            if (!$produit->infinite_stock && $nouvelleQuantite > $produit->stock) {
                $nouvelleQuantite = $produit->stock;
            }

            DB::table('ligne_paniers')
                ->where('id_ligne_panier', $ligne->id_ligne_panier)
                ->update([
                    'quantite' => $nouvelleQuantite,
                    'updated_at' => now(),
                ]);
        } else {
            $quantiteInitiale = 1;

            if (!$produit->infinite_stock && $quantiteInitiale > $produit->stock) {
                $quantiteInitiale = $produit->stock;
            }

            if ($quantiteInitiale <= 0) {
                return redirect()->route('panier.index')->with('error', 'Ce produit n\'est plus en stock.');
            }

            DB::table('ligne_paniers')->insert([
                'quantite' => $quantiteInitiale,
                'prix_snapshot' => $produit->prix,
                'panier_id' => $panier->id_panier,
                'produit_id' => $produit->id_produit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('panier.index')->with('success', 'Produit ajouté au panier.');
    }

    public function updateLine(Request $request, $ligneId)
    {
        $request->validate([
            'quantite' => 'required|integer|min:0',
        ]);

        $userId = Auth::id();

        $ligne = DB::table('ligne_paniers')
            ->join('paniers', 'ligne_paniers.panier_id', '=', 'paniers.id_panier')
            ->join('asso_utilisateur_panier', 'paniers.id_panier', '=', 'asso_utilisateur_panier.panier_id')
            ->join('produits', 'ligne_paniers.produit_id', '=', 'produits.id_produit')
            ->where('ligne_paniers.id_ligne_panier', $ligneId)
            ->where('asso_utilisateur_panier.user_id', $userId)
            ->select(
                'ligne_paniers.id_ligne_panier',
                'ligne_paniers.quantite',
                'ligne_paniers.prix_snapshot',
                'ligne_paniers.produit_id',
                'produits.stock',
                'produits.infinite_stock',
                'produits.rupture_marketing'
            )
            ->first();

        if (!$ligne) {
            abort(403);
        }

        $quantite = (int) $request->quantite;

        if ($ligne->rupture_marketing) {
            $quantite = 0;
        } elseif (!$ligne->infinite_stock) {
            if ($ligne->stock <= 0) {
                $quantite = 0;
            } elseif ($quantite > $ligne->stock) {
                $quantite = $ligne->stock;
            }
        }

        if ($quantite <= 0) {
            DB::table('ligne_paniers')
                ->where('id_ligne_panier', $ligneId)
                ->delete();
        } else {
            DB::table('ligne_paniers')
                ->where('id_ligne_panier', $ligneId)
                ->update([
                    'quantite' => $quantite,
                    'updated_at' => now(),
                ]);
        }

        if ($request->wantsJson()) {
            $panier = $this->getActivePanierForUser($userId);

            if (!$panier) {
                return response()->json([
                    'success' => true,
                    'ligneSupprimee' => true,
                    'totalHT' => 0,
                    'totalTTC' => 0,
                ]);
            }

            $lignes = DB::table('ligne_paniers')
                ->join('produits', 'ligne_paniers.produit_id', '=', 'produits.id_produit')
                ->where('ligne_paniers.panier_id', $panier->id_panier)
                ->select(
                    'ligne_paniers.id_ligne_panier',
                    'ligne_paniers.quantite',
                    'ligne_paniers.prix_snapshot',
                    'produits.stock',
                    'produits.infinite_stock'
                )
                ->get();

            $totalTTC = 0;
            $totalHT = 0;

            foreach ($lignes as $l) {
                $ligneTotalCalc = $l->prix_snapshot * $l->quantite;
                $totalTTC += $ligneTotalCalc;
                $totalHT += $ligneTotalCalc / 1.2;
            }

            $ligneTotal = null;
            $ligneHT = null;
            $prixUnitaire = null;
            $ligneSupprimee = $quantite <= 0;

            if (!$ligneSupprimee) {
                $l = $lignes->firstWhere('id_ligne_panier', $ligneId);

                if ($l) {
                    $prixUnitaire = $l->prix_snapshot;
                    $ligneTotal = $l->prix_snapshot * $l->quantite;
                    $ligneHT = $l->prix_snapshot / 1.2;
                }
            }

            return response()->json([
                'success' => true,
                'ligneSupprimee' => $ligneSupprimee,
                'prixUnitaireTTC' => $prixUnitaire,
                'prixUnitaireHT' => $ligneHT,
                'ligneTotalTTC' => $ligneTotal,
                'totalHT' => $totalHT,
                'totalTTC' => $totalTTC,
            ]);
        }

        return redirect()->route('panier.index')->with('success', 'Panier mis à jour.');
    }

    public function removeLine($ligneId)
    {
        $userId = Auth::id();

        $ligne = DB::table('ligne_paniers')
            ->join('paniers', 'ligne_paniers.panier_id', '=', 'paniers.id_panier')
            ->join('asso_utilisateur_panier', 'paniers.id_panier', '=', 'asso_utilisateur_panier.panier_id')
            ->where('ligne_paniers.id_ligne_panier', $ligneId)
            ->where('asso_utilisateur_panier.user_id', $userId)
            ->select('ligne_paniers.*')
            ->first();

        if (!$ligne) {
            abort(403);
        }

        DB::table('ligne_paniers')
            ->where('id_ligne_panier', $ligneId)
            ->delete();

        return redirect()->route('panier.index')->with('success', 'Produit retiré du panier.');
    }

    public function checkout()
    {
        $user = Auth::user();

        $panier = $this->getActivePanierForUser($user->id);

        if (!$panier) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
        }

        $lignes = DB::table('ligne_paniers')
            ->join('produits', 'ligne_paniers.produit_id', '=', 'produits.id_produit')
            ->where('ligne_paniers.panier_id', $panier->id_panier)
            ->select(
                'ligne_paniers.id_ligne_panier',
                'ligne_paniers.quantite',
                'ligne_paniers.prix_snapshot',
                'produits.id_produit',
                'produits.stock',
                'produits.infinite_stock',
                'produits.rupture_marketing'
            )
            ->get();

        if ($lignes->isEmpty()) {
            return redirect()->route('panier.index')->with('error', 'Votre panier est vide.');
        }

        $totalTTC = 0;
        $totalHT = 0;

        foreach ($lignes as $ligne) {
            if ($ligne->rupture_marketing) {
                return redirect()->route('panier.index')->with('error', 'Un produit du panier est actuellement en rupture.');
            }

            if (!$ligne->infinite_stock && $ligne->quantite > $ligne->stock) {
                return redirect()->route('panier.index')->with('error', 'Stock insuffisant pour au moins un produit du panier.');
            }

            $ligneTotal = $ligne->prix_snapshot * $ligne->quantite;
            $totalTTC += $ligneTotal;
            $totalHT += $ligneTotal / 1.2;
        }

        $commandeId = DB::table('commandes')->insertGetId([
            'date_commande' => now(),
            'statut' => 'payée',
            'total' => $totalTTC,
            'adresse_livraison' => 'Non renseignée',
            'adresse_facturation' => 'Non renseignée',
            'paiement_id' => null,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($lignes as $ligne) {
            DB::table('ligne_commandes')->insert([
                'quantité' => $ligne->quantite,
                'prix_TTC' => $ligne->prix_snapshot,
                'prix_HT' => $ligne->prix_snapshot / 1.2,
                'commande_id' => $commandeId,
                'produit_id' => $ligne->id_produit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!$ligne->infinite_stock) {
                DB::table('produits')
                    ->where('id_produit', $ligne->id_produit)
                    ->decrement('stock', $ligne->quantite);
            }
        }

        DB::table('paniers')
            ->where('id_panier', $panier->id_panier)
            ->update([
                'statut' => 'payé',
                'updated_at' => now(),
            ]);

        return redirect()->route('panier.success');
    }

    public function success()
    {
        return view('panier.success');
    }

    private function getActivePanierForUser(int $userId)
    {
        return DB::table('paniers')
            ->join('asso_utilisateur_panier', 'paniers.id_panier', '=', 'asso_utilisateur_panier.panier_id')
            ->where('asso_utilisateur_panier.user_id', $userId)
            ->where('paniers.statut', 'en_cours')
            ->select('paniers.*')
            ->first();
    }

    private function getOrCreateActivePanierForUser(int $userId)
    {
        $panier = $this->getActivePanierForUser($userId);

        if ($panier) {
            return $panier;
        }

        $panierId = DB::table('paniers')->insertGetId([
            'date_creation' => now(),
            'statut' => 'en_cours',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('asso_utilisateur_panier')->insert([
            'user_id' => $userId,
            'panier_id' => $panierId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('paniers')->where('id_panier', $panierId)->first();
    }
}
