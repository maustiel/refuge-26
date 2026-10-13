<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ShelterController;


Route::redirect('/', '/animaux');

Route::view('/composants', 'styleguide')->name('styleguide');

Route::get('/animaux', [AnimalController::class, 'index'])->name('animals.index');
Route::get('/animaux/{id}', [AnimalController::class, 'show'])->whereNumber('id')->name('animals.show');

Route::get('/animaux/nouveau', [AnimalController::class, 'create'])->name('animals.create');
Route::post('/animaux', [AnimalController::class, 'store'])->name('animals.store');

Route::get('/animaux/{id}/modifier', [AnimalController::class, 'edit'])->whereNumber('id')->name('animals.edit');
Route::put('/animaux/{id}', [AnimalController::class, 'update'])->whereNumber('id')->name('animals.update');


Route::patch('/animaux/{id}/adoption', [AnimalController::class, 'adopt'])->whereNumber('id')->name('animals.adopt');

Route::delete('/animaux/{id}', [AnimalController::class, 'destroy'])->whereNumber('id')->name('animals.destroy');


Route::get('/refuges/{id}', [ShelterController::class, 'show'])->whereNumber('id')->name('shelters.show');
