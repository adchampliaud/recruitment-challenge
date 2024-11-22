<?php

declare(strict_types=1);

namespace App\Core\Application\Capital;

final readonly class CapitalDetailHandler
{
    public function __construct(
        private CapitalServiceInterface $capitalService,
    ) {
    }

    public function __invoke(CapitalDetailMessage $message): void
    {
        $this->capitalService->fillCapitalDetails($message->capitalName);
    }
}
