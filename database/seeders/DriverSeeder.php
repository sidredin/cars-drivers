<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    private array $drivers = [
        ['id' => 1, 'name' => 'Иван', 'car_id' => null,],
        ['id' => 2, 'name' => 'Пётр', 'car_id' => 3,],
        ['id' => 3, 'name' => 'Василий', 'car_id' => null,],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach ($this->drivers as $driver) {

            $current_timestamp = date('Y-m-d H:i:s');

            DB::table('drivers')->insert([
                'id' => $driver['id'],
                'name' => $driver['name'],
                'car_id' => $driver['car_id'],
                'created_at' => $current_timestamp,
                'updated_at' => $current_timestamp,
            ]);
        }
    }
}
