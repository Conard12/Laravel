<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conditions Générales de Vente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">1. Objet</h3>
                    <p class="mb-4">Les présentes conditions régissent les ventes par les vendeurs sur la marketplace Velstore.</p>
                    
                    <h3 class="text-lg font-bold mb-4">2. Prix</h3>
                    <p class="mb-4">Les prix de nos produits sont indiqués en euros toutes taxes comprises (TTC).</p>

                    <h3 class="text-lg font-bold mb-4">3. Commandes</h3>
                    <p class="mb-4">Vous pouvez passer commande sur notre site Internet.</p>

                    <h3 class="text-lg font-bold mb-4">4. Livraison</h3>
                    <p>Les produits sont livrés à l'adresse de livraison indiquée au cours du processus de commande.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
