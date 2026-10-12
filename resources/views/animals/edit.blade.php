<x-layouts.app>
    <div class="max-w-md mx-auto">
        <x-card class="p-6">
            <h1 class="text-2xl font-bold mb-4 text-center">Modifier {{ $animal->name }}</h1>
            <form method="PUT" action="{{ route('animals.update', $animal->id) }}">
    @csrf


    <x-animal-fields :animal="$animal" :shelters="$shelters" :species="$species" :tags="$tags" />

    <x-button type="submit" class="w-full mt-4">Sauvegarder</x-button>
</form>
        </x-card>
    </div>
</x-layouts.app>
