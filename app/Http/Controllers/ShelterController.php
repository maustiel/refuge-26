<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function show(int $id)
    {
        $shelter = Shelter::findOrFail($id);
        $animals = $shelter->animals()->whereNull('adopted_at')->orderBy('name')->get();

        return view('shelters.show', ['shelter' => $shelter, 'animals' => $animals]);
    }
}
