<?php

namespace App\Controller;

use App\Lib\ApiLib;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    const SIRENE_V1_URL = 'https://entreprise.data.gouv.fr/api/sirene/v1';

    /** @var HttpClientInterface */
    private $client;

    /** @var ApiLib */
    private $apiLib;

    public function __construct(HttpClientInterface $client, ApiLib $apiLib)
    {
        $this->client = $client;
        $this->apiLib = $apiLib;
    }

    /**
     * @Rest\Get("/search-company", name="_search_company")
     */
    public function getSearchResults(Request $request)
    {
        $searchName = $request->get('name') ?? 'kipsoft';

        if ($searchName === null) {
            throw new NotFoundHttpException('Search name not found.');
        }

        $perPage = $request->get('perPage') ?? 5;
        $page = $request->get('page') ?? 1;

        $url = self::SIRENE_V1_URL."/full_text/{$searchName}?per_page={$perPage}&page={$page}";

        $response = $this->client->request('GET', $url);
        $content = $response->toArray();

        if ($response->getStatusCode() !== 200) {
            throw new NotFoundHttpException('Sirene API not found');
        }

        $etablissement = $content['etablissement'] ?? [];

        return new JsonResponse([
            'searchName' => $searchName,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => $content['totalPages'] ?? 1,
            'totalResults' => $content['totalPages'] ?? sizeof($etablissement),
            'etablissement' => $etablissement,
            'renderHtml' => $this->apiLib->getEtablissementListRender($etablissement)
        ]);
    }
}
