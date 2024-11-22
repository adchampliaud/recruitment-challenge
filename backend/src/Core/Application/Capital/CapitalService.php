<?php

declare(strict_types=1);

namespace App\Core\Application\Capital;

use App\Infrastructure\CapitalApi\CapitalApiInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class CapitalService implements CapitalServiceInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private CapitalApiInterface $capitalApi,
        private LoggerInterface $logger,
    ) {
    }

    public function fillCapital(string $countryCode): void
    {
        if (!Countries::exists($countryCode) && !Countries::alpha3CodeExists($countryCode)) {
            throw new NotFoundCountryCodeException();
        }

        $capitalName = $this->capitalApi->getCapitalByCountry($countryCode);

        $this->messageBus->dispatch(new CapitalDetailMessage($capitalName));
    }

    public function fillCapitalDetails(string $capitalName): void
    {
        $capitalDetails = $this->capitalApi->getCapitalDetail($capitalName);

        $this->logger->info("Capital details for {$capitalName}", $capitalDetails);
    }
}
