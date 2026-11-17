<?php

namespace App\Services\Ml;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MlClient
{
    /**
     * @param array<string, int|float> $features
     * @return array<string, array<string, mixed>>|null
     */
    public function predict(array $features): ?array
    {
        try {
            $response = Http::timeout(5)
                ->retry(1, 100)
                ->post(rtrim(config('services.ml.url'), '/').'/predict', ['features' => $features])
                ->throw();

            $conditions = $response->json('conditions');

            return is_array($conditions) && count($conditions) === 3 ? $conditions : null;
        } catch (RequestException|ConnectionException) {
            return null;
        }
    }
}
