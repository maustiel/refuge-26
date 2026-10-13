<?php

use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/*
| Chapitre 18 : une photo par animal.
| Storage::fake('public') remplace le disque public par un dossier temporaire, vidé à chaque test :
| vos fichiers de storage/app/public ne sont jamais touchés.
| UploadedFile::fake()->image('rex.jpg') fabrique une vraie image JPEG de 10 × 10 pixels.
*/

test('les deux formulaires envoient en multipart/form-data et proposent un champ photo', function (): void {
    $animal = Animal::factory()->create();

    $this->get('/animaux/nouveau')
        ->assertOk()
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('type="file"', false)
        ->assertSee('name="photo"', false);

    $this->get("/animaux/{$animal->id}/modifier")
        ->assertOk()
        ->assertSee('enctype="multipart/form-data"', false)
        ->assertSee('name="photo"', false);
})->group('photo');

test('la photo envoyée à l\'ajout est rangée sur le disque public et son chemin en base', function (): void {
    Storage::fake('public');
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, [
        'photo' => UploadedFile::fake()->image('rex.jpg'),
    ]))->assertSessionHasNoErrors();

    $animal = Animal::where('name', 'Rex')->firstOrFail();

    expect($animal->photo)->toStartWith('animals/')->toEndWith('.jpg');
    Storage::disk('public')->assertExists($animal->photo);
})->group('photo');

test('la photo est facultative', function (): void {
    Storage::fake('public');
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter))->assertSessionHasNoErrors();

    expect(Animal::where('name', 'Rex')->firstOrFail()->photo)->toBeNull();
})->group('photo');

test('un PDF est refusé, avec un message en français', function (): void {
    Storage::fake('public');
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, [
        'photo' => UploadedFile::fake()->create('fiche-rex.pdf', 100, 'application/pdf'),
    ]))->assertSessionHasErrors(['photo' => 'Le champ photo doit être une image.']);

    $this->assertDatabaseCount('animals', 0);
    expect(Storage::disk('public')->allFiles())->toBeEmpty();
})->group('photo');

test('une image de plus de 2 Mo est refusée', function (): void {
    Storage::fake('public');
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, [
        'photo' => UploadedFile::fake()->image('rex.jpg')->size(3000),
    ]))->assertSessionHasErrors('photo');

    $this->assertDatabaseCount('animals', 0);
})->group('photo');

test('la fiche et la liste affichent la photo, sinon le pictogramme de l\'espèce', function (): void {
    $rex = Animal::factory()->create(['name' => 'Rex', 'species' => 'chien', 'photo' => 'animals/rex.jpg']);
    $minette = Animal::factory()->create(['name' => 'Minette', 'species' => 'chat', 'photo' => null]);

    $this->get("/animaux/{$rex->id}")
        ->assertOk()
        ->assertSee('storage/animals/rex.jpg', false)
        ->assertSee('alt="Photo de Rex"', false);

    $this->get("/animaux/{$minette->id}")
        ->assertOk()
        ->assertSee('images/species/chat.svg', false);

    $this->get('/animaux')
        ->assertOk()
        ->assertSee('storage/animals/rex.jpg', false)
        ->assertSee('images/species/chat.svg', false);
})->group('photo');

test('modifier sans choisir de fichier garde la photo', function (): void {
    Storage::fake('public');
    $shelter = Shelter::factory()->create();
    $old = UploadedFile::fake()->image('rex.jpg')->store('animals', 'public');
    $animal = Animal::factory()->for($shelter)->create(['photo' => $old]);

    $this->put("/animaux/{$animal->id}", validAnimal($shelter, ['description' => 'Un nouveau texte.']))
        ->assertSessionHasNoErrors();

    expect($animal->fresh()->photo)->toBe($old);
    Storage::disk('public')->assertExists($old);
})->group('photo');

test('remplacer la photo supprime l\'ancien fichier', function (): void {
    Storage::fake('public');
    $shelter = Shelter::factory()->create();
    $old = UploadedFile::fake()->image('rex.jpg')->store('animals', 'public');
    $animal = Animal::factory()->for($shelter)->create(['photo' => $old]);

    $this->put("/animaux/{$animal->id}", validAnimal($shelter, [
        'photo' => UploadedFile::fake()->image('rex-ete.jpg'),
    ]))->assertSessionHasNoErrors();

    $new = $animal->fresh()->photo;

    expect($new)->not->toBe($old);
    Storage::disk('public')->assertExists($new);
    Storage::disk('public')->assertMissing($old);
})->group('photo');

test('supprimer un animal supprime sa photo', function (): void {
    Storage::fake('public');
    $photo = UploadedFile::fake()->image('rex.jpg')->store('animals', 'public');
    $animal = Animal::factory()->create(['photo' => $photo]);

    $this->delete("/animaux/{$animal->id}")->assertRedirect(route('animals.index'));

    $this->assertModelMissing($animal);
    Storage::disk('public')->assertMissing($photo);
})->group('photo');
