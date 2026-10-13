@props(['animal', 'shelters', 'species', 'tags'])

<div>
    <x-label for="name">Nom</x-label>
    <x-input type="text" id="name" name="name" :value="old('name', $animal->name)" class="w-full {{ $errors->has('name') ? 'border-red-500' : '' }}" aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}" aria-describedby="name-error" />
    @error('name')
        <p id="name-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <x-label for="species">Espèce</x-label>
    <x-select id="species" name="species" class="w-full {{ $errors->has('species') ? 'border-red-500' : '' }}" aria-invalid="{{ $errors->has('species') ? 'true' : 'false' }}" aria-describedby="species-error">
        <option value="">Sélectionnez une espèce</option>
        @foreach ($species as $sp)
            <option value="{{ $sp->value }}" @selected(old('species', $animal->species?->value) === $sp->value)>{{ $sp->label() }}</option>
        @endforeach
    </x-select>
    @error('species')
        <p id="species-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <x-label for="shelter_id">Refuge</x-label>
    <x-select id="shelter_id" name="shelter_id" class="w-full {{ $errors->has('shelter_id') ? 'border-red-500' : '' }}" aria-invalid="{{ $errors->has('shelter_id') ? 'true' : 'false' }}" aria-describedby="shelter_id-error">
        <option value="">Sélectionnez un refuge</option>
        @foreach ($shelters as $shelter)
            <option value="{{ $shelter->id }}" @selected(old('shelter_id', $animal->shelter_id) == $shelter->id)>{{ $shelter->name }}</option>
        @endforeach
    </x-select>
    @error('shelter_id')
        <p id="shelter_id-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <x-label for="birth_date">Date de naissance</x-label>
    <x-input type="date" id="birth_date" name="birth_date" :value="old('birth_date', $animal->birth_date?->format('Y-m-d'))" class="w-full {{ $errors->has('birth_date') ? 'border-red-500' : '' }}" aria-invalid="{{ $errors->has('birth_date') ? 'true' : 'false' }}" aria-describedby="birth_date-error" />
    @error('birth_date')
        <p id="birth_date-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <x-label for="description">Description</x-label>
    <x-textarea id="description" name="description" class="w-full {{ $errors->has('description') ? 'border-red-500' : '' }}" aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}" aria-describedby="description-error">{{ old('description', $animal->description) }}</x-textarea>
    @error('description')
        <p id="description-error" class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
<div>
    <x-label for="photo">Photo</x-label>
    <x-file-input name="photo" accept="image/*" />
    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">Facultatif. Une image de 2 Mo au plus.</p>
    @error('photo')
        <p id="photo-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
<fieldset class="mt-4">
    <legend class="text-sm font-medium mb-2">Traits de caractère</legend>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        @php
            $checkedTags = old('tags', $animal->tags?->pluck('id')->toArray() ?? []);
        @endphp
        @foreach($tags as $tag)
            <div class="flex items-center gap-2">
                <input type="checkbox" id="tag-{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $checkedTags))>
                <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
            </div>
        @endforeach
    </div>
    @error('tags.*')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</fieldset>
