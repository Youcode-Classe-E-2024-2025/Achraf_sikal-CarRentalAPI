<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cars::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|digits:4|before_or_equal:' . date('Y'),
            'color' => 'required|string|max:50',
            'registration_number' => 'required|string|unique:cars,registration_number|max:255',
            'price_per_day' => 'required|numeric|min:0.01',
            'available' => 'nullable|boolean',
        ]);

        $car = Cars::create([
            'make' => $validatedData['make'],
            'model' => $validatedData['model'],
            'year' => $validatedData['year'],
            'color' => $validatedData['color'],
            'registration_number' => $validatedData['registration_number'],
            'price_per_day' => $validatedData['price_per_day'],
            'available' => $validatedData['available'] ?? true,
        ]);
        return $car;
    }

    public function show(Cars $Car)
    {
        return $Car;
    }

    public function update(Request $request, Cars $Car)
    {
        $Car->update($request->all());
        return $Car;
    }

    public function destroy(Cars $Car)
    {
        $Car->delete();
        return response()->noContent();
    }
}
