@props(['animal'])

<x-card class="flex flex-col h-full">
    <x-slot:image>
        @if ($animal->photo !== null)
            <img src="{{ asset('storage/'.$animal->photo) }}" alt="Photo de {{ $animal->name }}" class="aspect-4/3 w-full rounded-xl object-cover shadow-xs">
        @else
            <img src="{{ asset($animal->species->image()) }}" alt="{{ $animal->species->label() }}">
        @endif
    </x-slot:image>
    <div class="p-4 flex-grow">
        <h2 class="text-xl font-bold">
            <a href="{{ route('animals.show', $animal->id) }}">{{ $animal->name }}</a>
        </h2>
        <p class="text-gray-600">
            <a href="{{ route('shelters.show', $animal->shelter_id) }}" class="hover:underline hover:text-brand-600">
                {{ $animal->shelter->name }}
            </a>
        </p>
    </div>
</x-card>
