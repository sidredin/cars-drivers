<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Car;
use App\Models\Driver;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Log;

final class DriverService
{

    /**
     * @throws \Exception
     * @throws HttpClientException
     */
    public function update($validatedData, $driverId)
    {
        try {
            $driver = Driver::findOrFail($driverId);
            if (array_key_exists('car_id', $validatedData)) {
                switch (true) {
                    case (!empty($driver->car_id) && $validatedData['car_id'] != null) :
                        throw new HttpClientException('The driver is already driving a car', 422);
                    case (!empty(Car::find($validatedData['car_id'])?->driver)):
                        throw new HttpClientException('This car is already in use', 422);
                    default:
                        if ($validatedData['car_id'] != null && !Car::find($validatedData['car_id']))
                            throw new HttpClientException('Car not found', 422);
                        $driver->car_id = $validatedData['car_id'];

                }
            }
            if (!empty($validatedData['name'])) $driver->name = $validatedData['name'];
            $driver->save();

            return $driver;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
