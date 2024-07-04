<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        } catch (TransportExceptionInterface) {
            throw new NotFoundHttpException('Something went wrong with the Starwars API');
        }

        try {
            $dataArray = $response->toArray();
        } catch (Exception) {
            throw new NotFoundHttpException('Something went wrong with the Starwars API');
        }

        $films = PropertyAccess::createPropertyAccessor()->getValue($dataArray, '[data][allFilms][films]');

        if ($films === null) {
            throw new NotFoundHttpException('Something went wrong with the Starwars API');
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