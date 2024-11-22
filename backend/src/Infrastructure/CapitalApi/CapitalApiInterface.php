<?php

declare(strict_types=1);

namespace App\Infrastructure\CapitalApi;

interface CapitalApiInterface
{
    public function getCapitalByCountry(string $countryCode): string;

    public function getCapitalDetail(string $capital): array;
}
