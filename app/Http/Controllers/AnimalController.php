<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Enums\Species;

use App\Models\Animal;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Http\Requests\AnimalRequest;

use App\Models\Tag;


class AnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::whereNull('adopted_at')->orderBy('name')->get();
        return view('animals.index', ['animals' => $animals]);
    }

    public function show(int $id)
    {
        $animal = Animal::findOrFail($id);
        return view('animals.show', ['animal' => $animal]);
    }

    public function create()
    {
        $shelters = Shelter::all();
        $species = Species::cases();
        $tags = Tag::orderBy('name')->get();
        return view('animals.create', ['animal' => new Animal, 'shelters' => $shelters, 'species' => $species, 'tags' => $tags]);
    }

    public function store(AnimalRequest $request)
    {
        $validated = $request->validated();
        $animal = Animal::create($validated);
        $animal->tags()->sync($validated['tags'] ?? []); // Synchronise les tags
        return redirect()->route('animals.show', $animal->id)->with('status', "L'animal a été ajouté.");
    }

    public function edit(int $id)
    {
        $animal = Animal::findOrFail($id);
        $shelters = Shelter::all();
        $species = Species::cases();
        $tags = Tag::orderBy('name')->get();
        return view('animals.edit', ['animal' => $animal, 'shelters' => $shelters, 'species' => $species, 'tags' => $tags]);
    }

    public function update(AnimalRequest $request, int $id)
    {
        $animal = Animal::findOrFail($id);
        $validated = $request->validated();
        $animal->update($validated);
        $animal->tags()->sync($validated['tags'] ?? []); // Synchronise les tags
        return redirect()->route('animals.show', $animal->id)->with('status', "L'animal a été modifié.");
    }

    public function adopt(int $id)
    {
        $animal = Animal::findOrFail($id);
        if (! $animal->isAdopted()) {
            $animal->update(['adopted_at' => today()]);
        }
        return redirect()->route('animals.show', $animal->id)->with('status', "{$animal->name} a trouvé une famille.");
    }
}
