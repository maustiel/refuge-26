{{-- Un champ de fichier. Comme <x-input>, il lit $errors : en erreur, aria-invalid="true" et aria-describedby="{name}-error". --}}
@props(['name'])

<input
    type="file"
    name="{{ $name }}"
    @if ($errors->has($name)) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
    {{ $attributes->merge(['id' => $name])->class('mt-2 block w-full text-sm text-zinc-700 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:font-semibold file:text-brand-700 hover:file:bg-brand-100 dark:text-zinc-300') }}
>
