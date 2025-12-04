<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Affiche le catalogue public des produits (HTML)
     */
    public function catalog(Request $request)
    {
        $query = Produit::with('category', 'user');

        // Recherche par nom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $produits = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();

        return view('public.catalog', compact('produits', 'categories'));
    }

    /**
     * Affiche la liste des produits (page publique)
     */
    public function index(Request $request)
    {
        $query = Produit::query();

        // Recherche par nom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $produits = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'success' => true,
            'data' => $produits,
            'message' => 'Produits récupérés avec succès'
        ]);
    }

    /**
     * Affiche les détails d'un produit
     */
    public function show($id)
    {
        $produit = Produit::with('category')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $produit,
            'message' => 'Produit récupéré avec succès'
        ]);
    }

    /**
     * Recherche de produits
     */
    public function searchProduits(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2|max:100',
        ]);

        $produits = Produit::where(function($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $produits,
            'message' => 'Produits trouvés avec succès'
        ]);
    }

    /**
     * Récupère les statistiques publiques
     */
    public function stats()
    {
        $stats = [
            'total_produits' => Produit::count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistiques récupérées avec succès'
        ]);
    }
}
