<x-app-layout>
    <!-- Import du CSS personnalisé -->
    <link rel="stylesheet" href="{{ asset('css/participant-style.css') }}">
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Boutique') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">✨ Découvrez Notre Boutique</h1>
                    <p class="hero-subtitle">
                        Explorez une sélection unique de produits créés par nos entrepreneurs passionnés
                    </p>
                </div>
            </div>

            <!-- Message de succès/erreur (Toast Notification) -->
            @if(session('success'))
                <div id="toast-success" class="fixed top-20 right-4 z-50 animate-slide-in-right" style="max-width: 400px;">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-green-500 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                <span class="text-2xl">✓</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Succès</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ session('success') }}</p>
                        </div>
                        <button onclick="document.getElementById('toast-success').remove()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div id="toast-error" class="fixed top-20 right-4 z-50 animate-slide-in-right" style="max-width: 400px;">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl border-l-4 border-red-500 p-4 flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                <span class="text-2xl">✕</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Erreur</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ session('error') }}</p>
                        </div>
                        <button onclick="document.getElementById('toast-error').remove()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Barre de recherche et filtres modernes -->
            <div class="search-container">
                <form method="GET" action="{{ route('shop.catalog') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="🔍 Rechercher un produit..." 
                               class="search-input w-full">
                    </div>
                    <div class="w-full md:w-64">
                        <select name="category_id" class="select-modern w-full">
                            <option value="">📂 Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary">
                        Filtrer
                    </button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('shop.catalog') }}" class="btn-secondary">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            <!-- Grille de produits -->
            @if($produits->count() > 0)
                <div class="product-grid">
                    @foreach($produits as $index => $produit)
                        <div class="product-card" style="animation-delay: {{ $index * 0.1 }}s;">
                            <!-- Image du produit -->
                            <div class="product-image-container">
                                @if($produit->image)
                                    <img src="{{ asset('images/produits/' . $produit->image) }}" 
                                         alt="{{ $produit->nom }}" 
                                         class="product-image">
                                @else
                                    <div class="product-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                        <span style="font-size: 4rem; opacity: 0.3;">📦</span>
                                    </div>
                                @endif
                                <div class="product-image-overlay"></div>
                                
                                <!-- Badge catégorie -->
                                @if($produit->category)
                                    <div class="product-badge badge-category">
                                        {{ $produit->category->nom }}
                                    </div>
                                @endif
                            </div>

                            <!-- Contenu du produit -->
                            <div class="product-content">
                                <h3 class="product-title">{{ $produit->nom }}</h3>
                                <p class="product-description">{{ $produit->description }}</p>
                                
                                @if($produit->user)
                                    <p class="product-vendor">
                                        {{ $produit->user->nom_entreprise ?? $produit->user->name }}
                                    </p>
                                @endif

                                <div class="product-footer">
                                    <span class="product-price">{{ number_format($produit->prix, 2) }} €</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                                        <input type="hidden" name="quantite" value="1">
                                        <button type="submit" class="btn-success">
                                            🛒 Ajouter
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 flex justify-center animate-fade-in">
                    {{ $produits->links() }}
                </div>
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h3 class="empty-state-title">Aucun produit trouvé</h3>
                    <p class="empty-state-description">
                        @if(request('search') || request('category_id'))
                            Essayez de modifier vos critères de recherche ou de filtrage.
                        @else
                            Il n'y a pas encore de produits disponibles dans notre boutique.
                        @endif
                    </p>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('shop.catalog') }}" class="btn-primary">
                            Voir tous les produits
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Script pour animations au scroll -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation des cards au scroll
            const cards = document.querySelectorAll('.product-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-scale-in');
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(card => {
                observer.observe(card);
            });

            // Animation du bouton "Ajouter au panier"
            const addToCartButtons = document.querySelectorAll('button[type="submit"]');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Animation de succès
                    const originalText = this.innerHTML;
                    this.innerHTML = '✓ Ajouté !';
                    this.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.background = '';
                    }, 2000);
                });
            });

            // Auto-hide toast notifications après 5 secondes
            const toastSuccess = document.getElementById('toast-success');
            const toastError = document.getElementById('toast-error');
            
            if (toastSuccess) {
                setTimeout(() => {
                    toastSuccess.style.opacity = '0';
                    toastSuccess.style.transform = 'translateX(100%)';
                    setTimeout(() => toastSuccess.remove(), 300);
                }, 5000);
            }
            
            if (toastError) {
                setTimeout(() => {
                    toastError.style.opacity = '0';
                    toastError.style.transform = 'translateX(100%)';
                    setTimeout(() => toastError.remove(), 300);
                }, 5000);
            }
        });
    </script>
</x-app-layout>
