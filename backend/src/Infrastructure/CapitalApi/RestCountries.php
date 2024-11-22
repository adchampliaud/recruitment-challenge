<?php

declare(strict_types=1);

namespace App\Infrastructure\CapitalApi;

use App\Common\AssertService;
use App\Infrastructure\ApiClient\ApiClientInterface;

final readonly class RestCountries implements CapitalApiInterface
{
    private const string GET_CAPITAL_URL_PATTERN = 'https://restcountries.com/v3.1/alpha/%s';
    private const string GET_CAPITAL_DETAIL_URL_PATTERN = 'https://restcountries.com/v3.1/capital/%s';

    public function __construct(
        private ApiClientInterface $apiClient,
    ) {
    }

    public function getCapitalByCountry(string $countryCode): string
    {
        $response = $this->apiClient->get(sprintf(self::GET_CAPITAL_URL_PATTERN, $countryCode));
        AssertService::notEmpty($response, new NotFoundException(sprintf('Capital not found with country code %s', $countryCode)));
        AssertService::isArray($response[0], new MalformedResponseException(sprintf('Invalid response. Response is %s', json_encode($response))));
        AssertService::arrayHasKey('capital', $response[0], new MalformedResponseException(sprintf('Response need capital array key. Response is %s', json_encode($response))));
        AssertService::isArray($response[0]['capital'], new MalformedResponseException(sprintf('Invalid response. Response is %s', json_encode($response))));
        AssertService::notEmpty($response[0]['capital'], new NotFoundException(sprintf('Capital not found with country code %s', $countryCode)));
        AssertService::isString($response[0]['capital'][0], new MalformedResponseException(sprintf('Invalid response. Response is %s', json_encode($response))));

        return $response[0]['capital'][0];
    }

    public function getCapitalDetail(string $capital): array
    {
        $response = $this->apiClient->get(sprintf(self::GET_CAPITAL_DETAIL_URL_PATTERN, $capital));
        AssertService::notEmpty($response, new NotFoundException(sprintf('Capital not found with name %s', $capital)));
        AssertService::isArray($response[0], new MalformedResponseException(sprintf('Invalid response. Response is %s', json_encode($response))));

        return $response[0];
    }
}
