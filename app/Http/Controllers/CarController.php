<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
        try {
            $validatedCar = Validator::make(
                $request->all(),
                [
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|digits:4|before_or_equal:' . date('Y'),
                'color' => 'required|string|max:50',
                'registration_number' => 'required|string|unique:cars,registration_number|max:255',
                'price_per_day' => 'required|numeric|min:0.01',
                'available' => 'nullable|boolean',
            ]);
            if ($validatedCar->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validatedCar->errors()
                ], 401);
            }

            $car = Cars::create([
                'make' => $request->make,
                'model' => $request->model,
                'year' => $request->year,
                'color' => $request->color,
                'registration_number' => $request->registration_number,
                'price_per_day' => $request->price_per_day,
                'available' => $request->available ?? true,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Car Created Successfully',
                'car' => $car
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show(Cars $Car)
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Car Created Successfully',
                'car' => $Car
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $th->getMessage()
            ], 500);
        }
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
