<?php

declare(strict_types=1);

namespace App\Core\Application\Capital;

use App\Infrastructure\Message\AsyncMessageInterface;

final readonly class CapitalByCountryMessage implements AsyncMessageInterface
{
    public function __construct(
        public string $countryCode,
    ) {
    }
}
