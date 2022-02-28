<?php

namespace App\Service\FakeServer;

use App\Service\DataProcessorInterface;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FakeServerApi implements DataProcessorInterface
{
    private HttpClientInterface $client;

    private string $endpoint;

    public function __construct(HttpClientInterface $client, string $endpoint)
    {
        $this->client = $client->withOptions([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
        $this->endpoint = $endpoint;
    }

    /**
     * @param array $data
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ErrorException
     * @throws NotModifiedException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function requestChanges(array $data): array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->endpoint,
                [
                    'json' => $data
                ]
            );

            $statusCode = $response->getStatusCode();
        } catch (TransportException $e) {
            throw new ErrorException('Some server error');
        }

        if ($statusCode === 204) {
            throw new NotModifiedException('No content');
        }

        $newData = $response->toArray();

        return $newData['changes'];
    }
}