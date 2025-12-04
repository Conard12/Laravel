<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails de la Commande #') }}{{ $commande->id }}
            </h2>
            <div class="flex space-x-2">
                        <a href="{{ route('commandes.edit', $commande) }}" 
           class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold rounded transition-colors duration-200"
           style="background-color: #6366f1 !important; color: white !important;">
            Modifier
        </a>
        <a href="{{ route('commandes.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded transition-colors duration-200"
           style="background-color: #6b7280 !important; color: white !important;">
            Retour
        </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informations générales -->
                        </div>
                        
                        <div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Statut actuel</label>
                                @php
                                    $statutColors = [
                                        'en_attente' => 'bg-yellow-100 text-yellow-800',
                                        'confirmee' => 'bg-blue-100 text-blue-800',
                                        'en_preparation' => 'bg-orange-100 text-orange-800',
                                        'livree' => 'bg-green-100 text-green-800',
                                        'annulee' => 'bg-red-100 text-red-800'
                                    ];
                                    $statutLabels = [
                                        'en_attente' => 'En attente',
                                        'confirmee' => 'Confirmée',
                                        'en_preparation' => 'En préparation',
                                        'livree' => 'Livrée',
                                        'annulee' => 'Annulée'
                                    ];
                                @endphp
                                <span class="mt-1 px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statutColors[$commande->statut] }}">
                                    {{ $statutLabels[$commande->statut] }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Total</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ number_format($commande->total_prix, 2) }} €</p>
                            </div>
                            
                            @if($commande->notes)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $commande->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changement de statut -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Changer le statut</h3>
                    
                    <form action="{{ route('commandes.update-statut', $commande) }}" method="POST" class="flex items-end space-x-4">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex-1 max-w-xs">
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Nouveau statut</label>
                            <select name="statut" id="statut" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="en_attente" {{ $commande->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmee" {{ $commande->statut == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                <option value="en_preparation" {{ $commande->statut == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                <option value="livree" {{ $commande->statut == 'livree' ? 'selected' : '' }}>Livrée</option>
                                <option value="annulee" {{ $commande->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded transition-colors duration-200"
                                style="background-color: #3b82f6 !important; color: white !important;">
                            Mettre à jour
                        </button>
                    </form>
                </div>
            </div>

            <!-- Produits de la commande -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Produits commandés</h3>
                    @if($produits->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                        <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach($produits as $produit)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 border-b border-gray-200">{{ $produit->nom }}</td>
                                            <td class="px-6 py-4 border-b border-gray-200">{{ number_format($produit->prix, 2) }} €</td>
                                            <td class="px-6 py-4 border-b border-gray-200">{{ $produit->quantite_commande }}</td>
                                            <td class="px-6 py-4 border-b border-gray-200">{{ number_format($produit->sous_total, 2) }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4 text-right font-bold">
                                Total : {{ number_format($total, 2) }} €
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500">Aucun produit trouvé dans cette commande</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 