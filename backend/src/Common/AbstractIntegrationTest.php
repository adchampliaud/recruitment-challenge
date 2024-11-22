<?php

declare(strict_types=1);

namespace App\Common;

use InvalidArgumentException;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

/** @psalm-suppress UnusedClass */
abstract class AbstractIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    /**
     * @template T
     *
     * @param class-string<T> $serviceName
     *
     * @return T
     */
    protected function getService(string $serviceName): object
    {
        /** @var ?T $service */
        $service = static::getContainer()->get($serviceName);
        AssertService::notNull(
            $service,
            new InvalidArgumentException(sprintf('Service with reference "%s" not found', $serviceName)),
        );

        return $service;
    }

    /** @param class-string $serviceName */
    protected function setService(string $serviceName, object $service): void
    {
        AssertService::instanceOf($service, $serviceName, new LogicException());

        static::getContainer()->set($serviceName, $service);
    }

    protected function getMessages(): array
    {
        /** @var InMemoryTransport $asyncTransport */
        $asyncTransport = static::getContainer()->get('messenger.transport.async');

        return array_map(fn (Envelope $envelope) => $envelope->getMessage(), $asyncTransport->getSent());
    }
}
