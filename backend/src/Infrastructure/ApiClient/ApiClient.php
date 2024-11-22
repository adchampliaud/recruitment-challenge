<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient;

use App\Common\AssertService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class ApiClient implements ApiClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
    ) {
    }

    public function get(string $path): array
    {
        return $this->callApi('GET', $path, 0);
    }

    private function callApi(
        string $method,
        string $path,
        int $retry,
    ): array {
        $response = $this->httpClient->request($method, $path);
        try {
            return $response->toArray();
        } catch (ClientException $exception) {
            if ($exception->getCode() === 429 && $retry <= 4) {
                $this->logger->notice(
                    'Api receive too much call',
                    [
                        'Path' => $path,
                        'Http Code' => $exception->getCode(),
                        'Body response' => $exception->getResponse()->getContent(false),
                    ],
                );
                sleep(10);

                return $this->callApi($method, $path, $retry + 1);
            }
            AssertService::notEquals($exception->getCode(), 429, new TooManyRequestsHttpException());

            throw $exception;
        } catch (JsonException $exception) {
            $this->logger->notice('Error on parse call api.', ['path' => $path, 'response' => $response->getContent(false)]);

            throw $exception;
        }
    }
}
