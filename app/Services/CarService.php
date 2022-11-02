<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Car;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Facades\Log;

final class CarService
{

    /**
     * @throws \Exception
     * @throws HttpClientException
     */
    public function update($validatedData, $id)
    {
        try {
            $car = Car::findOrFail($id);
            if (!empty($validatedData['model'])) $car->model = $validatedData['model'];
            $car->save();

            return $car;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
