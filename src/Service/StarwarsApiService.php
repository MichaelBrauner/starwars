<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Webmozart\Assert\Assert;

final readonly class StarwarsApiService {
    public function __construct(
        private HttpClientInterface $starwarsClient,
    )
    {
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getAllFilmsAsArray(): array
    {
        try {
            $response = $this->getAllFilms();
        } catch (TransportExceptionInterface $e) {
            throw new ServiceUnavailableHttpException(null, 'Unable to reach the Starwars API', $e);
        }

        try {
            $dataArray = $response->toArray();
        } catch (Exception $e) {
            throw new NotFoundHttpException('Unexpected error when processing the response from the Starwars API', $e);
        }

        try {
            $films = PropertyAccess::createPropertyAccessor()->getValue($dataArray, '[data][allFilms][films]');
        } catch (AccessException $e) {
            throw new NotFoundHttpException('Invalid structure in the response data from the Starwars API', $e);
        }

        if ($films === null) {
            throw new NotFoundHttpException('No films found in the Starwars API response');
        }

        Assert::isArray($films);
        return $films;
    }
    /**
     * @throws TransportExceptionInterface
     */
    public function getAllFilms(): ResponseInterface
    {
        $query = <<<'GRAPHQL'
            query ExampleQuery {
              allFilms {
                films {
                  title
                  openingCrawl
                  director
                  producers
                  releaseDate
                }
              }
            }
            GRAPHQL;

        return $this->starwarsClient->request(
            method: 'POST',
            url: '',
            options: [
                'json' => [
                    'query' => $query,
                ],
            ]
        );
    }
}