<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nouvelle Commande') }}
            </h2>
                    <a href="{{ route('commandes.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded transition-colors duration-200"
           style="background-color: #6b7280 !important; color: white !important;">
            Retour
        </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('commandes.store') }}" method="POST" id="commandeForm">
                        @csrf
                        
                        <!-- Produits dynamiques -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Produits *</label>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div id="produits-container" class="my-4"></div>
                                <button type="button" id="ajouter-produit" class="mt-2 bg-green-500 text-white px-4 py-2 rounded">+ Ajouter un produit</button>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (optionnel)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Notes spéciales pour cette commande...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Résumé de la commande -->
                        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Résumé de la commande</h3>
                            <div id="resume-commande">
                                <p class="text-gray-600">Sélectionnez des produits pour voir le résumé</p>
                            </div>
                        </div>

                        <!-- Boutons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('commandes.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded transition-colors duration-200"
                               style="background-color: #6b7280 !important; color: white !important;">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded transition-colors duration-200"
                                    style="background-color: #3b82f6 !important; color: white !important;">
                                Créer la commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
const allProduits = @json($produits);

function createProduitRow() {
    let row = document.createElement('div');
    row.className = "flex items-center space-x-2 my-2";

    let select = document.createElement('select');
    select.name = "produits[]";
    select.required = true;
    select.className = "border rounded px-2 py-1 flex-1";
    select.innerHTML = '<option value="">Sélectionner un produit</option>';
    allProduits.forEach(function(produit) {
        select.innerHTML += `<option value="${produit.id}">${produit.nom} - ${produit.prix}€</option>`;
    });

    let input = document.createElement('input');
    input.type = "number";
    input.name = "quantites[]";
    input.min = 1;
    input.value = 1;
    input.required = true;
    input.className = "border rounded px-2 py-1 w-20";

    let btn = document.createElement('button');
    btn.type = "button";
    btn.innerText = "✕";
    btn.className = "text-red-600 font-bold px-2";
    btn.onclick = function() { row.remove(); updateResume(); };

    // Mettre à jour le résumé à chaque changement
    select.addEventListener('change', updateResume);
    input.addEventListener('input', updateResume);

    row.appendChild(select);
    row.appendChild(input);
    row.appendChild(btn);

    return row;
}

function updateResume() {
    let total = 0;
    let resume = '<div class="space-y-2">';
    const produitsDivs = document.querySelectorAll('#produits-container > div');
    produitsDivs.forEach(div => {
        const select = div.querySelector('select');
        const quantite = div.querySelector('input[type="number"]');
        if (select && quantite && select.value && quantite.value) {
            const produit = allProduits.find(p => p.id == select.value);
            if (produit) {
                const qte = parseInt(quantite.value);
                const sousTotal = produit.prix * qte;
                total += sousTotal;
                resume += `
                    <div class="flex justify-between text-sm">
                        <span>${produit.nom} x${qte}</span>
                        <span>${sousTotal.toFixed(2)}€</span>
                    </div>
                `;
            }
        }
    });
    resume += `
        <hr class="my-2">
        <div class="flex justify-between font-semibold">
            <span>Total</span>
            <span>${total.toFixed(2)}€</span>
        </div>
    </div>`;
    document.getElementById('resume-commande').innerHTML = resume;
}

document.getElementById('ajouter-produit').addEventListener('click', function() {
    let container = document.getElementById('produits-container');
    let row = createProduitRow();
    container.appendChild(row);
    updateResume();
});

// Add initial row
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('ajouter-produit').click();
});
</script>
</x-app-layout>