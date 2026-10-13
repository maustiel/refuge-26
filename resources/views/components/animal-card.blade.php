@props(['animal'])

<x-card class="flex flex-col h-full">
    <x-slot:image>
        <img src="{{ asset($animal->species->image()) }}" alt="{{ $animal->species->label() }}">
    </x-slot:image>
    <div class="p-4 flex-grow">
        <h2 class="text-xl font-bold">
            <a href="{{ route('animals.show', $animal->id) }}">{{ $animal->name }}</a>
        </h2>
        <p class="text-gray-600">
            <!-- Lien vers le refuge ajouté ici -->
            <a href="{{ route('shelters.show', $animal->shelter_id) }}" class="hover:underline hover:text-brand-600">
                {{ $animal->shelter->name }}
            </a>
        </p>
    </div>
</x-card>
