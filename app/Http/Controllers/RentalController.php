<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentalController extends Controller
{
    public function rentCar(Request $request)
    {
        dd($request->user()->id);
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
            'user_id' => Auth::id(),
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $car->price_per_day * (strtotime($request->end_date) - strtotime($request->start_date)) / 86400,
            'status' => 'pending',
            'type' => 'rent',
        ]);

        return response()->json([
            'message' => 'Rental request created',
            'rental' => $rental->load('car') // Include car details
        ]);
    }
}
