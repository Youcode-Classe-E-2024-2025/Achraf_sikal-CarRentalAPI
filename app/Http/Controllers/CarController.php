<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cars",
     *     summary="Retrieve a list of all cars",
     *     tags={"Cars"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all cars retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Cars retrieved successfully"),
     *             @OA\Property(property="car", type="array", @OA\Items(
     *                 ref="#/components/schemas/Car"
     *             ))
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error"),
     * )
     */
    public function index()
    {
        $cars = Cars::all();
        return response()->json([
            'status' => true,
            'message' => 'Car Created Successfully',
            'cars' => $cars
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/cars",
     *     summary="Create a new car",
     *     tags={"Cars"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Car data",
     *         @OA\JsonContent(
     *             required={"make", "model", "year", "color", "registration_number", "price_per_day"},
     *             @OA\Property(property="make", type="string", example="Toyota"),
     *             @OA\Property(property="model", type="string", example="Camry"),
     *             @OA\Property(property="year", type="integer", example=2020),
     *             @OA\Property(property="color", type="string", example="Red"),
     *             @OA\Property(property="registration_number", type="string", example="ABC1234"),
     *             @OA\Property(property="price_per_day", type="number", format="float", example=50.0),
     *             @OA\Property(property="available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Car Created Successfully"),
     *             @OA\Property(property="car", ref="#/components/schemas/Car")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/cars/{id}",
     *     summary="Retrieve car details",
     *     tags={"Cars"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the car to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Car retrieved successfully"),
     *             @OA\Property(property="car", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="make", type="string", example="Toyota"),
     *                 @OA\Property(property="model", type="string", example="Corolla"),
     *                 @OA\Property(property="year", type="integer", example=2020)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Car not found")
     * )
     */
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
