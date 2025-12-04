<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mentions Légales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Éditeur du site</h3>
                    <p class="mb-4">Velstore SAS<br>
                    Capital social : 10 000 €<br>
                    RCS Paris B 123 456 789<br>
                    Siège social : 123 Rue du Commerce, 75000 Paris</p>
                    
                    <h3 class="text-lg font-bold mb-4">Hébergement</h3>
                    <p class="mb-4">Hébergeur : AWS<br>
                    Adresse : Amazon Web Services, Inc., P.O. Box 81226, Seattle, WA 98108-1226</p>

                    <h3 class="text-lg font-bold mb-4">Données personnelles</h3>
                    <p>Conformément à la loi Informatique et Libertés, vous disposez d'un droit d'accès et de rectification aux données vous concernant.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
