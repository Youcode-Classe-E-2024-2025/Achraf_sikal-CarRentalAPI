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
        return Cars::create($request->all());
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
