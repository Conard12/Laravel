<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paiement Réussi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <div class="mb-4 text-green-600">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Merci pour votre commande !</h3>
                    <p class="mb-4">Votre paiement a été validé avec succès.</p>
                    <p class="text-gray-600">Numéro de commande : #{{ $commande->id }}</p>
                    
                    <div class="mt-8">
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                            Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
