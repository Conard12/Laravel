<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Commandes') }}
            </h2>
            <a href="{{ route('commandes.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded transition-colors duration-200"
               style="background-color: #3b82f6 !important; color: white !important;">
                Nouvelle Commande
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Filtres -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('commandes.index') }}" class="flex gap-4">
                            <div class="flex-1">
                                <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                                <select name="statut" id="statut" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="en_preparation" {{ request('statut') == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                    <option value="livree" {{ request('statut') == 'livree' ? 'selected' : '' }}>Livrée</option>
                                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded transition-colors duration-200"
                                        style="background-color: #6b7280 !important; color: white !important;">
                                    Filtrer
                                </button>
                                <a href="{{ route('commandes.index') }}" 
                                   class="ml-2 inline-flex items-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded transition-colors duration-200"
                                   style="background-color: #d1d5db !important; color: #1f2937 !important;">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Messages de succès/erreur -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Liste des commandes -->
                    @if($commandes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Commande</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($commandes as $commande)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $commande->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $commande->user->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $commande->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($commande->total_prix ?? 0, 2) }} €
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $statutColors = [
                                                        'en_attente' => 'bg-yellow-100 text-yellow-800',
                                                        'confirmee' => 'bg-blue-100 text-blue-800',
                                                        'en_preparation' => 'bg-purple-100 text-purple-800',
                                                        'livree' => 'bg-green-100 text-green-800',
                                                        'annulee' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statutLabels = [
                                                        'en_attente' => 'En attente',
                                                        'confirmee' => 'Confirmée',
                                                        'en_preparation' => 'En préparation',
                                                        'livree' => 'Livrée',
                                                        'annulee' => 'Annulée',
                                                    ];
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statutColors[$commande->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statutLabels[$commande->statut] ?? $commande->statut }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('commandes.show', $commande) }}" class="text-blue-600 hover:text-blue-900 mr-3">Voir</a>
                                                <a href="{{ route('commandes.edit', $commande) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifier</a>
                                                <form action="{{ route('commandes.destroy', $commande) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $commandes->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Aucune commande trouvée.</p>
                            <a href="{{ route('commandes.create') }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Créer votre première commande
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>