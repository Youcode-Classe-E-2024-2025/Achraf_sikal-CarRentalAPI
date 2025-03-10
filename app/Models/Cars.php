<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
