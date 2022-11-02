<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarCollection;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json(new CarCollection(Car::all()));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return response()->json(['error' => Response::$statusTexts[$code]], $code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCarRequest $request
     * @return JsonResponse
     */
    public function store(StoreCarRequest $request): JsonResponse
    {
        try {
            return response()->json(Car::create($request->validated()));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return response()->json(['error' => Response::$statusTexts[$code]], $code);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Car $car
     * @return JsonResponse
     */
    public function show(int $id)
    {
        try {
            return response()->json(new CarResource(Car::findOrFail($id)));
        } catch (ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json(['error' => Response::$statusTexts[$code]], $code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCarRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateCarRequest $request, CarService $service, int $id)
    {

        try {
            return response()->json($service->update($request->validated(), $id));
        } catch (HttpClientException $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
        } catch (ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
            $msg = Response::$statusTexts[$code];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $msg = Response::$statusTexts[$code];
        }

        return response()->json(['error' => $msg], $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $driver = Car::findOrFail($id);
            $driver?->delete();
            return response()->json('The car was successfully deleted');
        } catch (HttpClientException $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
        } catch (ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
            $msg = Response::$statusTexts[$code];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $msg = Response::$statusTexts[$code];
        }

        return response()->json(['error' => $msg], $code);
    }
}
