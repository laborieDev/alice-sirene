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
     * @Rest\Get("/search/{therm}", name="_search_company")
     */
    public function getSearchResults(Request $request, $therm)
    {
        $perPage = $request->get('perPage') ?? 5;
        $page = $request->get('page') ?? 1;

        $url = self::SIRENE_V1_URL."/full_text/{$therm}?per_page={$perPage}&page={$page}";

        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            throw new NotFoundHttpException('Error to Sirene API');
        }

        $content = $response->toArray();
        $etablissement = $content['etablissement'] ?? [];

        return new JsonResponse([
            'searchName' => $therm,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => $content['totalPages'] ?? 1,
            'totalResults' => $content['totalPages'] ?? sizeof($etablissement),
            'etablissement' => $etablissement,
            'renderHtml' => $this->apiLib->getEtablissementListRender($etablissement)
        ]);
    }

    /**
     * @Rest\Get("/show/{siret}", name="_view_company")
     */
    public function getCompany(Request $request, $siret)
    {
        $url = self::SIRENE_V1_URL."/siret/{$siret}";

        $response = $this->client->request('GET', $url);
        $content = $response->toArray();

        if ($response->getStatusCode() !== 200) {
            throw new NotFoundHttpException('Sirene API not found');
        }

        $etablissementDatas = $content['etablissement'] ?? [];
        $etablissement = $this->apiLib->denormalizeEtablissement($etablissementDatas);

        return new JsonResponse([
            'etablissement' => $etablissementDatas,
            'renderHtml' => ''
        ]);
    }
}
