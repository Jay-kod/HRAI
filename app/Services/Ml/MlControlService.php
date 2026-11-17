<?php

namespace App\Services\Ml;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MlControlService
{
    /**
     * @return array{online: bool, message: string, payload: array<string, mixed>}
     */
    public function health(): array
    {
        try {
            $response = Http::timeout(2)
                ->get(rtrim(config('services.ml.url'), '/').'/health')
                ->throw();
            $payload = $response->json();

            if (! is_array($payload)) {
                return ['online' => false, 'message' => 'The ML service returned an invalid health response.', 'payload' => []];
            }

            return [
                'online' => ($payload['status'] ?? null) === 'ok',
                'message' => ($payload['status'] ?? null) === 'ok' ? 'Service is ready for predictions.' : 'Service is reachable but model artifacts are unavailable.',
                'payload' => $payload,
            ];
        } catch (RequestException|ConnectionException) {
            return ['online' => false, 'message' => 'The local ML service is offline. Assessments will use the rule-based fallback.', 'payload' => []];
        }
    }

    /**
     * @return array<string, array<string, mixed>>|null
     */
    public function testPrediction(): ?array
    {
        try {
            $response = Http::timeout(5)
                ->retry(1, 100)
                ->post(rtrim(config('services.ml.url'), '/').'/predict', [
                    'features' => [
                        'age_cat' => 5,
                        'sex' => 1,
                        'bmi' => 27.4,
                        'high_bp' => 1,
                        'high_chol' => 0,
                        'smoker' => 0,
                        'hvy_alcohol' => 0,
                        'phys_activity' => 1,
                        'fruit' => 1,
                        'veggies' => 1,
                        'gen_health' => 3,
                        'mental_health' => 2,
                        'physical_health' => 1,
                        'diabetes' => 0,
                    ],
                ])
                ->throw();
            $conditions = $response->json('conditions');

            return is_array($conditions) && count($conditions) === 3 ? $conditions : null;
        } catch (RequestException|ConnectionException) {
            return null;
        }
    }
}
