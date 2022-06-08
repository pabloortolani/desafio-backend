<?php

namespace App\Adapters;

use App\Interfaces\ExternalServicesAdapter;
use Exception;

class ServiceAuthorizingAdapter extends AbstractHttpAdapter implements ExternalServicesAdapter
{
    protected function endpoint(): string
    {
        $baseUrl = env('SERVICE_AUTHORIZING_BASE_URL');

        if (empty($baseUrl)) {
            throw new Exception('Url de integração não encontrada!');
        }

        return $baseUrl;
    }

    protected function methodHTTP(): string
    {
        return 'POST';
    }

    public function success(?array $data): bool
    {
        return isset($data['message']) && $data['message'] === 'Autorizado';
    }
}
