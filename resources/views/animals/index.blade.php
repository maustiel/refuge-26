<x-layouts.app>

    <!-- Filtres -->
    <div class="mb-6 flex gap-2 flex-wrap">
        <a href="{{ route('animals.index') }}"
           class="px-4 py-2 rounded-md font-medium {{ request()->query('espece') === null ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' }}"
           {!! request()->query('espece') === null ? 'aria-current="page"' : '' !!}>
           Tous
        </a>

        @foreach (\App\Enums\Species::cases() as $sp)
            <a href="{{ route('animals.index', ['espece' => $sp->value]) }}"
               class="px-4 py-2 rounded-md font-medium {{ request()->query('espece') === $sp->value ? 'bg-brand-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' }}"
               {!! request()->query('espece') === $sp->value ? 'aria-current="page"' : '' !!}>
                {{ $sp->label() }}
            </a>
        @endforeach
    </div>

    <!-- Grille -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($animals as $animal)
            <x-animal-card :animal="$animal" />
        @empty
            <p>Aucun animal</p>
        @endforelse
    </div>
</x-layouts.app>
