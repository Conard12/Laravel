<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Exhibitor - Tous les Produits') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Messages de succès -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Formulaire de recherche et filtres -->
                    <form method="GET" action="{{ route('exhibitor') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="Nom ou description...">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2 transition-colors duration-200" style="background-color: #6b7280 !important;">
                                    Rechercher
                                </button>
                                <a href="{{ route('exhibitor') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors duration-200" style="background-color: #d1d5db !important;">
                                    Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Affichage en grille des produits -->
                    @if($produits->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($produits as $produit)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                                    <!-- Image du produit -->
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                                        @if($produit->image)
                                            <img src="{{ asset('images/produits/' . $produit->image) }}" 
                                                 alt="{{ $produit->nom }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-400 text-4xl">📦</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Informations du produit -->
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                            {{ $produit->nom }}
                                        </h3>
                                        
                                        @if($produit->description)
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                                {{ Str::limit($produit->description, 80) }}
                                            </p>
                                        @endif
                                        
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-lg font-bold text-gray-900">
                                                {{ number_format($produit->prix, 2) }} €
                                            </span>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('produits.show', $produit) }}" 
                                               class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors duration-200">
                                                Voir détails
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg">Aucun produit trouvé.</p>
                            @if(request()->has('search'))
                                <a href="{{ route('exhibitor') }}" class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                                    Réinitialiser les filtres
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if($produits->hasPages())
                        <div class="mt-6">
                            {{ $produits->links() }}
                        </div>
                    @endif

                    <!-- Statistiques -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Affichage de <strong>{{ $produits->count() }}</strong> produit(s) sur <strong>{{ $produits->total() }}</strong> au total
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>


