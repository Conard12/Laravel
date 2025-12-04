<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCommandeController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $dateFilter = $request->input('date');

        // Requête de base pour les commandes avec relations
        $commandes = Commande::with(['user'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('statut', $statusFilter);
            })
            ->when($dateFilter, function ($query, $dateFilter) {
                $query->whereDate('created_at', $dateFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistiques globales
        $stats = [
            'total_commandes' => Commande::count(),
            'total_ca' => Commande::sum('total_prix'),
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'commandes_confirmees' => Commande::where('statut', 'confirmee')->count(),
            'commandes_livrees' => Commande::where('statut', 'livree')->count(),
        ];

        // Liste des statuts pour le filtre
        $statuts = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'en_preparation' => 'En préparation',
            'livree' => 'Livrée',
            'annulee' => 'Annulée'
        ];

        return view('admin.commandes', compact(
            'commandes',
            'stats',
            'statuts',
            'search',
            'statusFilter',
            'dateFilter'
        ));
    }

    public function show(Commande $commande)
    {
        $commande->load(['user']);
        return view('admin.commandes.show', compact('commande'));
    }

    public function updateStatus(Request $request, Commande $commande)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,livree,annulee'
        ]);

        $commande->update([
            'statut' => $request->statut
        ]);

        return redirect()->back()->with('success', 'Statut de la commande mis à jour avec succès.');
    }

    public function export(Request $request)
    {
        // Export des commandes (format CSV ou Excel)
        $commandes = Commande::with(['user'])
            ->when($request->input('date'), function ($query, $date) {
                $query->whereDate('created_at', $date);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'commandes_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($commandes) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID', 'Client', 'Email', 
                'Total', 'Statut', 'Date commande'
            ]);

            // Données
            foreach ($commandes as $commande) {
                fputcsv($file, [
                    $commande->id,
                    $commande->user->name,
                    $commande->user->email,
                    $commande->total_prix,
                    $commande->statut,
                    $commande->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}