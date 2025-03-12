<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Rental;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/rent",
     *     summary="Rent a car",
     *     tags={"Rentals"},
     *     security={{ "BearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Rental request details",
     *         @OA\JsonContent(
     *             required={"car_id", "start_date", "end_date"},
     *             @OA\Property(property="car_id", type="integer", example=1, description="ID of the car to rent"),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-03-10", description="Rental start date"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-03-20", description="Rental end date")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rental request created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rental request created"),
     *             @OA\Property(property="rental", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="car_id", type="integer", example=1),
     *                 @OA\Property(property="start_date", type="string", format="date", example="2025-03-10"),
     *                 @OA\Property(property="end_date", type="string", format="date", example="2025-03-20"),
     *                 @OA\Property(property="total_price", type="number", format="float", example=500.0),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="type", type="string", example="rent"),
     *                 @OA\Property(property="car", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="make", type="string", example="Toyota"),
     *                     @OA\Property(property="model", type="string", example="Camry"),
     *                     @OA\Property(property="price_per_day", type="number", format="float", example=50.0)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or car unavailable",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="This vehicle is currently rented by another customer.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized. Token is missing or invalid.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */


    public function rentCar(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);
        $car = Cars::find($request->car_id);

        if ($car->available == false) {
            return response()->json(['message' => 'This vehicle is currently rented by another customer.'], 400);
        }

        $rental = Rental::create([
            'user_id' => $request->user()->id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $car->price_per_day * (strtotime($request->end_date) - strtotime($request->start_date)) / 86400,
            'status' => 'pending',
            'type' => 'rent',
        ]);
        Cars::where('id', $request->car_id)->update(["available"=>false]);

        return response()->json([
            'message' => 'Rental request created',
            'rental' => $rental->load('car') // Include car details
        ],200);
    }
}
