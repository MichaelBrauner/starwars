<?php

namespace App\Controller;

use App\Service\StarwarsApiService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;

class StarwarsController extends AbstractController {
    #[Cache(maxage: 3600, public: true, mustRevalidate: true)]
    #[Route('/', name: 'app_starwars_index', stateless: true)]
    public function index(): Response
    {
        return $this->render('starwars/index.html.twig');
    }

    #[Route('/list', name: 'app_starwars_list')]
    public function list(
        StarwarsApiService $starwarsApi,
        CacheInterface     $cache,
        #[MapQueryParameter]
        ?string            $search = null,
        #[MapQueryParameter]
        ?string            $sort = null,
    ): Response
    {
        $films = $cache->get('starwars.films', fn() => $starwarsApi->getAllFilmsAsArray());

        dump($search);
        if ($search) {
            $films = array_filter($films, function ($film) use ($search) {
                $title = strtolower($film['title']);
                $search = strtolower($search);

                return str_contains($title, $search);
            });
        }

        usort($films, function ($a, $b) use ($sort) {
            $dateA = new DateTime($a['releaseDate']);
            $dateB = new DateTime($b['releaseDate']);
            if ($sort === 'asc') {
                return $dateB <=> $dateA;
            }

            return $dateA <=> $dateB;
        });

        return $this->render('starwars/list.html.twig', ['films' => $films]);
    }
}
