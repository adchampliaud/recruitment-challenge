<?php

declare(strict_types=1);

namespace App\Core\Application\Capital;

final readonly class CapitalByCountryHandler
{
    public function __construct(
        private CapitalServiceInterface $capitalService,
    ) {
    }

    public function __invoke(CapitalByCountryMessage $message): void
    {
        $this->capitalService->fillCapital($message->countryCode);
    }
}
