<!-- resources/views/animals/create.blade.php -->
<x-layouts.app>
    <div class="max-w-md mx-auto">
        <x-card class="p-6">
            <h1 class="text-2xl font-bold mb-4 text-center">Ajouter un animal</h1>
            <form ...>
    @csrf


    <x-animal-fields :animal="$animal" :shelters="$shelters" :species="$species" :tags="$tags" />

    <x-button type="submit" class="w-full mt-4">Sauvegarder</x-button>
</form>
        </x-card>
    </div>
</x-layouts.app>
