<!-- resources/views/animals/show.blade.php -->
<x-layouts.app>
    <h1 class="text-2xl font-bold">{{ $animal->name }}</h1>
    <div class="flex gap-2 my-2 flex-wrap">
        <x-badge variant="species">{{ $animal->species->label() }}</x-badge>
        @if ($animal->isAdopted())
            <x-badge variant="adopted">Adopté</x-badge>
        @endif
    </div>
    <p><strong>Refuge :</strong> <a href="{{ route('shelters.show', $animal->shelter_id) }}"
            class="underline hover:text-brand-600">{{ $animal->shelter->name }}</a></p>

    @if ($animal->age() !== null)
        <p><strong>Âge :</strong> {{ $animal->age() }} ans</p>
    @endif

    @if ($animal->description)
        <p><strong>Description :</strong> {{ $animal->description }}</p>
    @endif
    <div class="flex gap-2 mt-4 flex-wrap">
        @foreach ($animal->tags as $tag)
            <x-badge variant="tag">{{ $tag->name }}</x-badge>
        @endforeach
    </div>
    <div class="mt-4 flex gap-2 flex-wrap">
        <x-button variant="secondary" href="{{ route('animals.edit', $animal->id) }}">Modifier</x-button>

        @if (!$animal->isAdopted())
            <form method="POST" action="{{ route('animals.adopt', $animal->id) }}">
                @csrf
                @method('PATCH')
                <x-button type="submit">Adopter</x-button>
            </form>
        @endif

        <form method="POST" action="{{ route('animals.destroy', $animal->id) }}"
            onsubmit="return confirm('Supprimer cet animal ?')">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="danger">Supprimer</x-button>
        </form>
    </div>
</x-layouts.app>
