<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Bites\Shared\Models\ApiData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReceiveDataController extends Controller
{
    public function store(Request $request)
    {
        // Validate data as array; add optional timestamp/source
        $validator = Validator::make($request->all(), [
            'data' => ['required', 'array'],
            'source' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $payload = $validator->validated();

            $apiData = ApiData::create([
                'content' => $request->input('data'),
                'source' => $request->input('source', 'api'),
            ]);

            Log::info('ApiData saved', [
                'id' => $apiData->id,
                'count' => is_array($payload['data']) ? count($payload['data']) : null,
                'source' => $payload['source'] ?? null,
            ]);

            return response()->json([
                'message' => 'Data saved successfully',
                'id' => $apiData->id,
            ], 200);
        } catch (\Throwable $throwable) {
            Log::error('ReceiveDataController store failed', [
                'error' => $throwable->getMessage(),
                'trace' => $throwable->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Server error',
            ], 500);
        }
    }
}
