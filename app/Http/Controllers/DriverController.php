<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use App\Services\DriverService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            return response()->json(Driver::all());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return response()->json(['error' => Response::$statusTexts[$code]], $code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDriverRequest $request
     * @return JsonResponse
     */
    public function store(StoreDriverRequest $request)
    {
        try {
            return response()->json(Driver::create($request->validated()));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return response()->json(['error' => Response::$statusTexts[$code]], $code);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            return response()->json(Driver::findOrFail($id));
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
     * @param UpdateDriverRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateDriverRequest $request, DriverService $service, int $id)
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
     * @param \App\Models\Driver $driver
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        try {
            $driver = Driver::findOrFail($id);
            $driver?->delete();
            return response()->json('The driver was successfully deleted');
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
