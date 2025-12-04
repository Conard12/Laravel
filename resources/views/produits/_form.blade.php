<div class="space-y-6">
    <div>
        <x-input-label for="nom" :value="__('Nom du produit')" />
        <x-text-input id="nom" name="nom" type="text" class="mt-1 block w-full" 
                      :value="old('nom', $produit->nom ?? '')" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('nom')" />
    </div>

    <div>
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" rows="3" 
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  placeholder="Description du produit...">{{ old('description', $produit->description ?? '') }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>

    <div>
        <x-input-label for="category_id" :value="__('Catégorie')" />
        <select id="category_id" name="category_id" 
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">Sélectionner une catégorie</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                        {{ old('category_id', $produit->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->nom }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
    </div>

    <div>
        <x-input-label for="prix" :value="__('Prix (€)')" />
        <x-text-input id="prix" name="prix" type="number" step="0.01" min="0" class="mt-1 block w-full" 
                      :value="old('prix', $produit->prix ?? '')" required />
        <x-input-error class="mt-2" :messages="$errors->get('prix')" />
    </div>

    <div>
        <x-input-label for="image" :value="__('Image du produit')" />
        <input type="file" id="image" name="image" accept="image/*" 
               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
        <x-input-error class="mt-2" :messages="$errors->get('image')" />
        @if(isset($produit) && $produit->image)
            <div class="mt-2">
                <img src="{{ asset('images/produits/' . $produit->image) }}" alt="{{ $produit->nom }}" class="w-32 h-32 object-cover rounded-lg">
            </div>
        @endif
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Enregistrer') }}</x-primary-button>
    </div>
</div>