<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Car",
 *     type="object",
 *     required={"make", "model", "year", "color", "registration_number", "price_per_day"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="make", type="string", example="Toyota"),
 *     @OA\Property(property="model", type="string", example="Camry"),
 *     @OA\Property(property="year", type="integer", example=2020),
 *     @OA\Property(property="color", type="string", example="Red"),
 *     @OA\Property(property="registration_number", type="string", example="ABC1234"),
 *     @OA\Property(property="price_per_day", type="number", format="float", example=50.0),
 *     @OA\Property(property="available", type="boolean", example=true)
 * )
 */

class Cars extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'make',
        'model',
        'year',
        'color',
        'registration_number',
        'price_per_day',
        'available',
    ];
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}
