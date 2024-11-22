<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\CapitalApi;

use App\Common\AbstractIntegrationTest;
use App\Infrastructure\ApiClient\ApiClientInterface;
use App\Infrastructure\CapitalApi\CapitalApiInterface;
use App\Infrastructure\CapitalApi\MalformedResponseException;
use App\Infrastructure\CapitalApi\NotFoundException;

/** @internal */
final class CapitalApiIntegrationTest extends AbstractIntegrationTest
{
    private readonly CapitalApiInterface $capitalApi;
    private readonly ApiClientInterface $apiClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->setService(ApiClientInterface::class, $this->apiClient);
        $this->capitalApi = $this->getService(CapitalApiInterface::class);
    }

    /** @test */
    public function it_should_return_expected_capital(): void
    {
        $expectedCapital = 'Paris';
        $this->apiClient->expects(self::once())->method('get')->willReturn([['capital' => [$expectedCapital]]]);
        $capital = $this->capitalApi->getCapitalByCountry('fr');
        self::assertEquals($expectedCapital, $capital);
    }

    /** @test */
    public function it_should_throw_exception_when_response_not_contain_array(): void
    {
        self::expectException(MalformedResponseException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn(['Paris']);
        $this->capitalApi->getCapitalByCountry('fr');
    }

    /** @test */
    public function it_should_throw_exception_when_response_has_not_expected_key(): void
    {
        self::expectException(MalformedResponseException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn([['test' => 'test']]);
        $this->capitalApi->getCapitalByCountry('fr');
    }

    /** @test */
    public function it_should_throw_exception_when_capital_not_contain_array(): void
    {
        self::expectException(MalformedResponseException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn([['capital' => 'test']]);
        $this->capitalApi->getCapitalByCountry('fr');
    }

    /** @test */
    public function it_should_throw_exception_when_capital_is_not_string(): void
    {
        self::expectException(MalformedResponseException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn([['capital' => [1]]]);
        $this->capitalApi->getCapitalByCountry('fr');
    }

    /** @test */
    public function it_should_throw_exception_when_response_is_empty(): void
    {
        self::expectException(NotFoundException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn([]);
        $this->capitalApi->getCapitalByCountry('fr');
    }

    /** @test */
    public function it_should_throw_exception_when_capital_is_empty(): void
    {
        self::expectException(NotFoundException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn([['capital' => []]]);
        $this->capitalApi->getCapitalByCountry('fr');
    }

    /** @test */
    public function it_should_return_capital_detail(): void
    {
        $expectedJson = json_encode(['capital' => []]);
        $this->apiClient->expects(self::once())->method('get')->willReturn([json_decode($expectedJson, true)]);
        $capitalDetail = $this->capitalApi->getCapitalDetail('Paris');
        self::assertEquals($expectedJson, json_encode($capitalDetail));
    }

    /** @test */
    public function it_should_throw_exception_when_capital_detail_is_empty(): void
    {
        self::expectException(NotFoundException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn([]);
        $this->capitalApi->getCapitalDetail('fr');
    }

    /** @test */
    public function it_should_throw_exception_when_capital_detail_is_not_array(): void
    {
        self::expectException(MalformedResponseException::class);
        $this->apiClient->expects(self::once())->method('get')->willReturn(['test']);
        $this->capitalApi->getCapitalDetail('fr');
    }
}
