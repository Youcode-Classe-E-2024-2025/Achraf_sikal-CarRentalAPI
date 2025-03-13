<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Rental;
use App\Models\Payment;
use Stripe\Climate\Product;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Payment::all();
    }

    /**
     * @OA\Post(
     *     path="/api/checkout",
     *     summary="Initiate a car rental checkout with Stripe",
     *     tags={"Rentals"},
     *     security={{ "BearerAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_id"},
     *             @OA\Property(property="car_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Checkout session created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function checkout(Request $request)
    {
        $pay = Rental::where('user_id', $request->user()->id)
            ->where('car_id', $request->car_id)->with('car')
            ->first();
        $pay->total_price = (int) $pay->total_price;
        try {
            Stripe::setApiKey(config('stripe.sk'));
            $session = Session::create([
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $pay->car->make . ' ' . $pay->car->model,
                                'description' => "Car rental for " . $pay->car->make . " " . $pay->car->model,
                            ],
                            'unit_amount' => $pay->total_price * 100, // Stripe expects amount in cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('success'),
                'cancel_url' => route('index'),
                'metadata' => [
                    'make' => $pay->car->make,
                    'model' => $pay->car->model,
                    'year' => $pay->car->year,
                    'color' => $pay->car->color,
                    'registration_number' => $pay->car->registration_number,
                    'start_date' => $pay->start_date,
                    'end_date' => $pay->end_date,
                ],
            ]);

            return response()->json([
                'data' => $pay,
                'checkout_url' => $session->url,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create Stripe session',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/success",
     *     summary="Handle successful Stripe payment",
     *     tags={"Rentals"},
     *     security={{ "BearerAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Payment success message",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Payment successful! Thank you for renting a car.")
     *         )
     *     )
     * )
     */
    public function success()
    {
        return response()->json([
            'message' => 'Payment successful! Thank you for renting a car.'
        ]);
    }
    public function store(Request $request)
    {
        return Payment::create($request->all());
    }

    public function show(Payment $payment)
    {
        return $payment;
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
