<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Enums\Species;

use App\Models\Animal;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use App\Http\Requests\AnimalRequest;

use App\Models\Tag;

use Illuminate\Support\Facades\Storage;



class AnimalController extends Controller
{
    public function index(Request $request)
    {
        $query = Animal::whereNull('adopted_at')->orderBy('name');

        $espece = $request->query('espece', '');
        $speciesEnum = Species::tryFrom($espece);


        if ($speciesEnum) {
            $query->where('species', $speciesEnum);
        }

        $animals = $query->get();
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

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('animals', 'public');
        }

        $animal = Animal::create($validated);
        $animal->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('animals.show', $animal->id)->with('status', 'L\'animal a été ajouté.');
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

        if ($request->hasFile('photo')) {
            // L'ancienne photo est remplacée : on la supprime
            if ($animal->photo !== null) {
                Storage::disk('public')->delete($animal->photo);
            }
            $validated['photo'] = $request->file('photo')->store('animals', 'public');
        }

        $animal->update($validated);
        $animal->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('animals.show', $animal->id)->with('status', 'L\'animal a été modifié.');
    }

    public function adopt(int $id)
    {
        $animal = Animal::findOrFail($id);
        if (! $animal->isAdopted()) {
            $animal->update(['adopted_at' => today()]);
        }
        return redirect()->route('animals.show', $animal->id)->with('status', "{$animal->name} a trouvé une famille.");
    }

    public function destroy(int $id)
    {
        $animal = Animal::findOrFail($id);

        // On supprime la photo du disque
        if ($animal->photo !== null) {
            Storage::disk('public')->delete($animal->photo);
        }

        $animal->delete();

        return redirect()->route('animals.index')->with('status', 'L\'animal a été supprimé.');
    }
}
