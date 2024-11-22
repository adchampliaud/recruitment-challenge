<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient;

interface ApiClientInterface
{
    public function get(string $path): array;
}
