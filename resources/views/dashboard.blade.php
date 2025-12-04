<x-app-layout>
    <!-- Import du CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/participant-style.css') }}">
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'entrepreneur' && Auth::user()->statut === 'approuve'))
                <!-- Dashboard Admin/Entrepreneur -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-bold mb-6">{{ __('Welcome') }} {{ Auth::user()->name }} !</h1>
                        <p class="mb-8 text-gray-600 dark:text-gray-400">
                            @if(Auth::user()->role === 'admin')
                                {{ __('You are logged in as administrator.') }}
                            @elseif(Auth::user()->role === 'entrepreneur' && Auth::user()->statut === 'approuve')
                                {{ __('You are logged in as entrepreneur.') }}
                            @endif
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Gestion des Produits -->
                            <div class="bg-orange-50 dark:bg-orange-900/20 p-6 rounded-lg border border-orange-200 dark:border-orange-700">
                                <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-200 mb-3">{{ __('Manage Products') }}</h3>
                                <p class="text-orange-600 dark:text-orange-300 mb-4">Gérez les produits</p>
                                <a href="{{ route('produits.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-700 text-white font-bold rounded transition-colors duration-200"
                                   style="background-color: #f97316 !important;">
                                    {{ __('Manage Products') }}
                                </a>
                            </div>

                            <!-- Gestion des Commandes -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg border border-green-200 dark:border-green-700">
                                <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-3">{{ __('Manage Orders') }}</h3>
                                <p class="text-green-600 dark:text-green-300 mb-4">Suivez et gérez les commandes</p>
                                <a href="{{ route('commandes.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold rounded transition-colors duration-200"
                                   style="background-color: #22c55e !important;">
                                    {{ __('Manage Orders') }}
                                </a>
                            </div>

                            <!-- Gestion des Catégories -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-3">{{ __('Manage Categories') }}</h3>
                                <p class="text-blue-600 dark:text-blue-300 mb-4">Créez et gérez les catégories de produits</p>
                                <a href="{{ route('categories.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded transition-colors duration-200"
                                   style="background-color: #3b82f6 !important;">
                                    {{ __('Manage Categories') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
            @elseif(Auth::user()->role === 'entrepreneur' && Auth::user()->statut === 'en_attente')
                <!-- Entrepreneur en attente -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg border border-yellow-200 dark:border-yellow-700">
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-3">{{ __('Account pending') }}</h3>
                    <p class="text-yellow-600 dark:text-yellow-300">
                        {{ __('Your entrepreneur account is awaiting approval by the administrator. You will receive an email as soon as your account is approved.') }}
                    </p>
                </div>
                
            @else
                <!-- Dashboard Participant Moderne -->
                <div class="hero-section" style="margin-bottom: 3rem;">
                    <div class="hero-content">
                        <h1 class="hero-title">👋 Bienvenue {{ Auth::user()->name }} !</h1>
                        <p class="hero-subtitle">
                            Découvrez des produits uniques créés par nos entrepreneurs passionnés
                        </p>
                    </div>
                </div>

                <!-- Quick Actions Cards -->
                <div class="quick-actions-grid">
                    <!-- Card Boutique -->
                    <div class="action-card animate-slide-in-left">
                        <div class="action-card-icon">🛍️</div>
                        <h3 class="action-card-title">Parcourir la Boutique</h3>
                        <p class="action-card-description">
                            Explorez notre catalogue de produits et trouvez ce qui vous plaît
                        </p>
                        <a href="{{ route('shop.catalog') }}" class="btn-primary">
                            Voir les produits
                        </a>
                    </div>

                    <!-- Card Mes Commandes -->
                    <div class="action-card animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="action-card-icon">📦</div>
                        <h3 class="action-card-title">Mes Commandes</h3>
                        <p class="action-card-description">
                            Suivez l'état de vos commandes et consultez votre historique
                        </p>
                        <a href="{{ route('orders.history') }}" class="btn-primary">
                            Voir mes commandes
                        </a>
                    </div>

                    <!-- Card Mon Panier -->
                    <div class="action-card animate-slide-in-right" style="animation-delay: 0.2s;">
                        <div class="action-card-icon">🛒</div>
                        <h3 class="action-card-title">Mon Panier</h3>
                        <p class="action-card-description">
                            Consultez votre panier et finalisez vos achats
                        </p>
                        <a href="{{ route('cart.index') }}" class="btn-primary">
                            Voir mon panier
                        </a>
                    </div>
                </div>

                <!-- Section informative supplémentaire -->
                <div class="mt-8 glass-card p-8 rounded-2xl animate-fade-in" style="animation-delay: 0.4s;">
                    <h3 class="text-2xl font-bold mb-4 text-gradient" style="font-family: var(--font-display);">
                        ✨ Pourquoi choisir Velstore ?
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="text-center">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">🎨</div>
                            <h4 class="font-semibold text-lg mb-2" style="font-family: var(--font-display);">Produits Uniques</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Des créations originales faites par des entrepreneurs locaux
                            </p>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">🚀</div>
                            <h4 class="font-semibold text-lg mb-2" style="font-family: var(--font-display);">Livraison Rapide</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Recevez vos commandes rapidement et en toute sécurité
                            </p>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">💎</div>
                            <h4 class="font-semibold text-lg mb-2" style="font-family: var(--font-display);">Qualité Premium</h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Tous nos produits sont soigneusement sélectionnés
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Script pour animations -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des action cards au scroll
            const cards = document.querySelectorAll('.action-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</x-app-layout>
