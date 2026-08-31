<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AdzunaService
{
    private string $appId;
    private string $appKey;

    public function __construct()
    {
        $this->appId = config('services.adzuna.app_id', '');
        $this->appKey = config('services.adzuna.app_key', '');
    }

    public function buscarVagas(array $skills, string $localizacao = '', int $raioKm = 50): array
    {
        if (empty($this->appId) || empty($this->appKey)) {
            Log::error('Adzuna API: Credenciais (APP_ID ou APP_KEY) não foram configuradas.');
            return [];
        }

        $params = [
            'app_id'           => $this->appId,
            'app_key'          => $this->appKey,
            'what'             => implode(' ', $skills),
            'results_per_page' => 10,
        ];

        if (!empty($localizacao)) {
            $params['where'] = $localizacao;
            $params['distance'] = $raioKm;
        }

        try {
            $response = Http::timeout(8)->get('https://api.adzuna.com/v1/api/jobs/br/search/1', $params);

            if ($response->successful()) {
                return $response->json()['results'] ?? [];
            }

            Log::warning("Adzuna API retornou erro HTTP: {$response->status()}", [
                'body' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('Adzuna API: Falha de conexão ou timeout.', [
                'error' => $e->getMessage()
            ]);
        }

        return [];
    }
}
