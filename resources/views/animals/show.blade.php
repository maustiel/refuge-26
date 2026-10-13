<!-- resources/views/animals/show.blade.php -->
<x-layouts.app>
    <div class="max-w-2xl mx-auto"> <!-- J'ajoute un conteneur pour limiter la largeur -->
        <h1 class="text-3xl font-bold mb-4">{{ $animal->name }}</h1>

        <!-- === DÉBUT DU BLOC PHOTO === -->
        <div class="mb-6">
            @if ($animal->photo !== null)
                <img src="{{ asset('storage/'.$animal->photo) }}" alt="Photo de {{ $animal->name }}" class="aspect-4/3 w-full rounded-xl object-cover shadow-xs">
            @else
                <img src="{{ asset($animal->species->image()) }}" alt="" width="200" height="200" class="aspect-4/3 w-full rounded-xl bg-brand-50 object-contain p-8 shadow-xs dark:bg-brand-100">
            @endif
        </div>
        <!-- === FIN DU BLOC PHOTO === -->

        <div class="flex gap-2 my-2 flex-wrap">
            <x-badge variant="species">{{ $animal->species->label() }}</x-badge>
            @if ($animal->isAdopted())
                <x-badge variant="adopted">Adopté</x-badge>
            @endif
        </div>

        <p class="mt-4"><strong>Refuge :</strong> <a href="{{ route('shelters.show', $animal->shelter_id) }}"
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

        <div class="mt-6 flex gap-2 flex-wrap">
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
    </div>
</x-layouts.app>
