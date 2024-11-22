<?php

declare(strict_types=1);

namespace App\Core\Application\Capital;

interface CapitalServiceInterface
{
    public function fillCapital(string $countryCode): void;

    public function fillCapitalDetails(string $capitalName): void;
}
