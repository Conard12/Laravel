<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\DemandeApprouvee;
use App\Mail\DemandeRejetee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    // Affiche la liste des entrepreneurs en attente
    public function index()
    {
        $demandes = User::where('role', 'entrepreneur')
            ->where('statut', 'en_attente')
            ->get();
        return view('admin.demandes', compact('demandes'));
    }

    // Approuve ou rejette une demande
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:approuve,rejete',
            'motif' => 'nullable|string|max:500', // Motif optionnel pour le rejet
        ]);

        $user = User::findOrFail($id);
        $ancienStatut = $user->statut;
        $user->statut = $request->statut;
        // Si on approuve, on garde le rôle entrepreneur (le statut change)
        // Le rôle reste 'entrepreneur', seul le statut change
        $user->save();

        // Envoyer un email de notification
        try {
            if ($request->statut === 'approuve') {
                Mail::to($user->email)->send(new DemandeApprouvee($user));
                $message = 'Demande approuvée et email de notification envoyé.';
            } else {
                Mail::to($user->email)->send(new DemandeRejetee($user, $request->motif));
                $message = 'Demande rejetée et email de notification envoyé.';
            }
        } catch (\Exception $e) {
            // Si l'envoi d'email échoue, on continue mais on log l'erreur
            \Log::error('Erreur lors de l\'envoi de l\'email de notification: ' . $e->getMessage());
            $message = 'Statut mis à jour. Erreur lors de l\'envoi de l\'email de notification.';
        }

        return redirect()->route('admin.demandes')->with('success', $message);
    }
}
