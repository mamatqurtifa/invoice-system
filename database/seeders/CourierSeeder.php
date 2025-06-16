<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Courier;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            ['name' => 'JNE'],
            ['name' => 'TIKI'],
            ['name' => 'POS Indonesia'],
            ['name' => 'SiCepat'],
            ['name' => 'J&T Express'],
            ['name' => 'Anteraja'],
            ['name' => 'Ninja Express'],
            ['name' => 'Wahana'],
        ];

        foreach ($couriers as $courier) {
            Courier::create($courier);
        }
    }
}