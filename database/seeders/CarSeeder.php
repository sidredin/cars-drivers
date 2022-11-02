<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarSeeder extends Seeder
{
    private array $cars = [
        ['id' => 1, 'model' => 'Lada Granta',],
        ['id' => 2, 'model' => 'Kia Rio',],
        ['id' => 3, 'model' => 'Hyundai Solaris',],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->cars as $car) {

            $current_timestamp = date('Y-m-d H:i:s');
            
            DB::table('cars')->insert([
                'id' => $car['id'],
                'model' => $car['model'],
                'created_at' => $current_timestamp,
                'updated_at' => $current_timestamp,
            ]);
        }
    }
}
