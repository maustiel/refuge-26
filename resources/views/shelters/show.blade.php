<x-layouts.app>
    <div class="mb-8">
        <h1 class="text-3xl font-bold">{{ $shelter->name }}</h1>
        <p class="text-gray-600 text-lg">{{ $shelter->city }}</p>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Animaux à adopter</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($animals as $animal)
            <x-animal-card :animal="$animal" />
        @empty
            <p>Aucun animal dans ce refuge pour le moment.</p>
        @endforelse
    </div>
</x-layouts.app>
