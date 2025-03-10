<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('color');
            $table->string('registration_number')->unique();
            $table->decimal('price_per_day', 8, 2);
            $table->boolean('available')->default(true);
            $table->timestamps();
        });

        DB::table('cars')->insert([
            [
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2020,
                'color' => 'Red',
                'registration_number' => 'XY-1234-AB',
                'price_per_day' => 75.50,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Honda',
                'model' => 'Civic',
                'year' => 2021,
                'color' => 'Blue',
                'registration_number' => 'AB-5678-CD',
                'price_per_day' => 85.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Ford',
                'model' => 'Mustang',
                'year' => 2019,
                'color' => 'Black',
                'registration_number' => 'GH-2345-EF',
                'price_per_day' => 120.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Chevrolet',
                'model' => 'Camaro',
                'year' => 2020,
                'color' => 'Yellow',
                'registration_number' => 'IJ-6789-KL',
                'price_per_day' => 110.00,
                'available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'BMW',
                'model' => 'M3',
                'year' => 2022,
                'color' => 'White',
                'registration_number' => 'MN-4321-OP',
                'price_per_day' => 150.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Audi',
                'model' => 'A4',
                'year' => 2021,
                'color' => 'Silver',
                'registration_number' => 'QR-8765-ST',
                'price_per_day' => 95.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Mercedes-Benz',
                'model' => 'C-Class',
                'year' => 2018,
                'color' => 'Grey',
                'registration_number' => 'UV-1357-WX',
                'price_per_day' => 110.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Nissan',
                'model' => 'Altima',
                'year' => 2019,
                'color' => 'Green',
                'registration_number' => 'YZ-2468-AB',
                'price_per_day' => 70.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Hyundai',
                'model' => 'Elantra',
                'year' => 2020,
                'color' => 'Orange',
                'registration_number' => 'CD-1234-EF',
                'price_per_day' => 60.00,
                'available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Kia',
                'model' => 'Sorento',
                'year' => 2021,
                'color' => 'Purple',
                'registration_number' => 'GH-5678-IJ',
                'price_per_day' => 95.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Tesla',
                'model' => 'Model S',
                'year' => 2022,
                'color' => 'Black',
                'registration_number' => 'IJ-2345-XY',
                'price_per_day' => 180.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Jaguar',
                'model' => 'XE',
                'year' => 2021,
                'color' => 'Dark Blue',
                'registration_number' => 'KL-6789-ZW',
                'price_per_day' => 130.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Land Rover',
                'model' => 'Range Rover',
                'year' => 2020,
                'color' => 'Silver',
                'registration_number' => 'MN-1357-YZ',
                'price_per_day' => 160.00,
                'available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Volkswagen',
                'model' => 'Golf',
                'year' => 2019,
                'color' => 'Green',
                'registration_number' => 'OP-2468-QR',
                'price_per_day' => 85.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'make' => 'Subaru',
                'model' => 'Outback',
                'year' => 2021,
                'color' => 'Brown',
                'registration_number' => 'ST-1357-UV',
                'price_per_day' => 90.00,
                'available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
