<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('À propos de Velstore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Notre Mission</h3>
                    <p class="mb-4">Velstore est une marketplace dédiée à connecter les vendeurs locaux avec des clients passionnés.</p>
                    
                    <h3 class="text-lg font-bold mb-4">Pour les Vendeurs</h3>
                    <p class="mb-4">Créez votre boutique en quelques clics et commencez à vendre vos produits.</p>

                    <h3 class="text-lg font-bold mb-4">Pour les Clients</h3>
                    <p>Découvrez des produits uniques et soutenez le commerce local.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
