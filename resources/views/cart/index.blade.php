<x-app-layout>
    <!-- Import du CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/participant-style.css') }}">
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mon Panier') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Hero Section -->
            <div class="hero-section" style="padding: 2rem;">
                <div class="hero-content">
                    <h1 class="hero-title" style="font-size: 2rem;">🛒 Mon Panier</h1>
                    <p class="hero-subtitle" style="font-size: 1rem;">
                        Finalisez vos achats et passez commande
                    </p>
                </div>
            </div>

            @if(count($cartItems) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Liste des produits -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 animate-fade-in">
                            <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                                Articles ({{ count($cartItems) }})
                            </h3>
                            
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:shadow-md transition-shadow duration-200">
                                        <!-- Image du produit -->
                                        <div class="flex-shrink-0">
                                            @if($item['produit']->image)
                                                <img src="{{ asset('images/produits/' . $item['produit']->image) }}" 
                                                     alt="{{ $item['produit']->nom }}" 
                                                     class="w-24 h-24 object-cover rounded-lg">
                                            @else
                                                <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg flex items-center justify-center">
                                                    <span class="text-3xl">📦</span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Informations du produit -->
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-1">
                                                {{ $item['produit']->nom }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                {{ Str::limit($item['produit']->description, 100) }}
                                            </p>
                                            <div class="flex items-center gap-4">
                                                <span class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                                    {{ number_format($item['produit']->prix, 2) }} €
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    × {{ $item['quantite'] }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex flex-col justify-between items-end">
                                            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {{ number_format($item['sous_total'], 2) }} €
                                            </div>
                                            <form action="{{ route('cart.remove', $item['produit']->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">
                                                    🗑️ Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Résumé de la commande -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-4 animate-slide-in-right">
                            <h3 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                                Résumé
                            </h3>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Sous-total</span>
                                    <span>{{ number_format($total, 2) }} €</span>
                                </div>
                                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                    <span>Livraison</span>
                                    <span class="text-green-600">Gratuite</span>
                                </div>
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-3 mt-3">
                                    <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-gray-100">
                                        <span>Total</span>
                                        <span class="text-gradient">{{ number_format($total, 2) }} €</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('orders.checkout') }}" class="btn-primary w-full block text-center mb-3">
                                Passer commande
                            </a>
                            
                            <a href="{{ route('shop.catalog') }}" class="btn-secondary w-full block text-center">
                                Continuer mes achats
                            </a>

                            <!-- Informations supplémentaires -->
                            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-start gap-2">
                                        <span>✓</span>
                                        <span>Paiement sécurisé</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span>✓</span>
                                        <span>Livraison gratuite</span>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span>✓</span>
                                        <span>Retour sous 14 jours</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Panier vide -->
                <div class="empty-state">
                    <div class="empty-state-icon">🛒</div>
                    <h3 class="empty-state-title">Votre panier est vide</h3>
                    <p class="empty-state-description">
                        Découvrez nos produits et ajoutez-les à votre panier pour commencer vos achats
                    </p>
                    <a href="{{ route('shop.catalog') }}" class="btn-primary">
                        Découvrir nos produits
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
