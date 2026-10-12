<!-- resources/views/animals/index.blade.php -->
<x-layouts.app>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($animals as $animal)
            <x-card class="flex flex-col h-full">
                <x-slot:image>
                    <img src="{{ asset($animal->species->image()) }}" alt="{{ $animal->species->label() }}">
                </x-slot:image>
                <div class="p-4 flex-grow">
                    <h2 class="text-xl font-bold"><a href="{{ route('animals.show', $animal->id) }}">{{ $animal->name }}</a></h2>
                    <p class="text-gray-600">{{ $animal->shelter->name }}</p>
                </div>
            </x-card>
        @empty
            <p>Aucun animal</p>
        @endforelse
    </div>
</x-layouts.app>
