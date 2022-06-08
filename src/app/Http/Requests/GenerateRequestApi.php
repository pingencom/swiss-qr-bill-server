<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Response;

class GenerateRequestApi extends GenerateRequest
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            Response::json([
                'error' => 'true',
                'title' => 'Validation failed',
                'message' => $validator->errors()
            ], \Illuminate\Http\Response::HTTP_BAD_REQUEST)
        );
    }
}
