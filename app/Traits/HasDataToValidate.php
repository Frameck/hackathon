<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

trait HasDataToValidate
{
    public function validateData(array $data, array $rules, ?int $statusCode = null): JsonResponse|array
    {
        $validated = Validator::make($data, $rules);

        if ($validated->errors()->isNotEmpty()) {
            return response()->json([
                'errors' => $validated->errors()->toArray(),
            ], $statusCode ?? Response::HTTP_BAD_REQUEST);
        }

        return $validated->safe()->all();
    }
}
