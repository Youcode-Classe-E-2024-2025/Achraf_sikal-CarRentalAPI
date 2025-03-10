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

    public function show(Cars $Cars)
    {
        return $Cars;
    }

    public function update(Request $request, Cars $Cars)
    {
        $Cars->update($request->all());
        return $Cars;
    }

    public function destroy(Cars $Cars)
    {
        $Cars->delete();
        return response()->noContent();
    }
}
