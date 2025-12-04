<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paiement Annulé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <div class="mb-4 text-red-600">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Paiement Annulé</h3>
                    <p class="mb-4">Vous avez annulé le processus de paiement.</p>
                    
                    <div class="mt-8">
                        <a href="{{ route('cart.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">
                            Retour au panier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
