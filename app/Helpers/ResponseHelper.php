<?php

namespace App\Helpers;

use App\Contracts\ProvidesMacros;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class ResponseHelper implements ProvidesMacros
{
    public static function registerMacros(): void
    {
        Response::macro('error', fn () => (
            response()->json([
                RESP_MSG => 'Something went wrong',
            ], HttpResponse::HTTP_BAD_REQUEST)
        ));

        Response::macro('success', function (mixed $value = null) {
            $response = [
                RESP_MSG => 'Action completed successfully',
            ];

            if ($value) {
                $response = array_merge(
                    $response,
                    $value
                );
            }

            return response()->json($response, HttpResponse::HTTP_OK);
        });
    }
}
