<?php

declare(strict_types=1);

namespace App\Tests\Core\Application\Capital;

use App\Common\AbstractIntegrationTest;
use App\Core\Application\Capital\CapitalDetailMessage;
use App\Core\Application\Capital\CapitalServiceInterface;
use App\Core\Application\Capital\NotFoundCountryCodeException;
use App\Infrastructure\ApiClient\ApiClientInterface;

/** @internal */
final class CapitalServiceIntegrationTest extends AbstractIntegrationTest
{
    private readonly CapitalServiceInterface $capitalService;
    private readonly ApiClientInterface $apiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->setService(ApiClientInterface::class, $this->apiClient);
        $this->capitalService = $this->getService(CapitalServiceInterface::class);
    }

    /**
     * @test
     *
     * @dataProvider getCountries
     */
    public function it_should_fill_capital(
        string $countryCode,
    ): void {
        $expectedCapital = 'Paris';
        $this->apiClient->expects(self::once())->method('get')->willReturn([['capital' => [$expectedCapital]]]);
        $this->capitalService->fillCapital($countryCode);

        $messages = $this->getMessages();
        self::assertCount(1, $messages);
        $message = current($messages);
        self::assertInstanceOf(CapitalDetailMessage::class, $message);
        self::assertEquals($expectedCapital, $message->capitalName);
    }

    public static function getCountries(): array
    {
        return [
            'with_alpha2_code' => [
                'countryCode' => 'FR',
            ],
            'with_alpha3_code' => [
                'countryCode' => 'FRA',
            ],
        ];
    }

    /** @test */
    public function it_should_throw_exception_on_fill_capital_with_invalid_country_code(): void
    {
        self::expectException(NotFoundCountryCodeException::class);
        $this->capitalService->fillCapital('XXX');
        self::assertCount(0, $this->getMessages());
    }
}
