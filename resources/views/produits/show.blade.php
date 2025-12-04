<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Détails du Produit') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('produits.edit', $produit) }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Modifier
                </a>
                <a href="{{ route('produits.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Image du produit -->
                        <div class="flex justify-center">
                            @if($produit->image)
                                <img src="{{ asset('images/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" 
                                     class="max-w-full h-auto rounded-lg shadow-lg">
                            @else
                                <div class="w-64 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-500 text-6xl">📦</span>
                                </div>
                            @endif
                        </div>

                        <!-- Informations du produit -->
                        <div class="space-y-6">
                            <div>
                                        <span class="text-gray-900">{{ $produit->id }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">Créé le:</span>
                                        <span class="text-gray-900">{{ $produit->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600">Modifié le:</span>
                                        <span class="text-gray-900">{{ $produit->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-3 pt-4">
                                <a href="{{ route('produits.edit', $produit) }}" 
                                   class="flex-1 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center">
                                    Modifier le produit
                                </a>
                                <form action="{{ route('produits.destroy', $produit) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout> 