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
 * @Route("/api", name="api")
 */
class ApiController extends AbstractController
{
    const SIRENE_V1_URL = 'https://entreprise.data.gouv.fr/api/sirene/v1';
    const SIRENE_V3_URL = 'https://entreprise.data.gouv.fr/api/sirene/v3';

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
     * @Rest\Get("/search/{term}", name="_search_company", options={"expose"=true})
     */
    public function getSearchResults(Request $request, $term)
    {
        $term = strtoupper($term);
        $perPage = $request->get('perPage') ?? 3;
        $page = $request->get('page') ?? 1;

        $url = self::SIRENE_V3_URL."/unites_legales/?denomination={$term}&per_page={$perPage}&page={$page}";

        $response = $this->client->request('GET', $url);
        $this->checkApiError($response);

        $content = [];

        try {
            $content = $response->toArray();
        } catch (\Exception $e) {}

        $etablissements = $content['unites_legales'] ?? [];
        $meta = $content['meta'] ?? [];

        $datas = [
            'searchName' => $term,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => $meta['total_pages'] ?? 1,
            'totalResults' => $meta['total_results'] ?? sizeof($etablissements),
            'etablissements' => $etablissements
        ];

        return new JsonResponse(array_merge($datas, [
            'renderHtml' => $this->apiLib->getEtablissementListRender($datas)
        ]));
    }

    /**
     * @Rest\Get("/show/{siret}", name="_view_company")
     */
    public function getCompany(Request $request, $siret)
    {
        $url = self::SIRENE_V3_URL."/etablissements/{$siret}";

        $response = $this->client->request('GET', $url);
        $this->checkApiError($response);

        $content = [];

        try {
            $content = $response->toArray();
        } catch (\Exception $e) {}

        $etablissementDatas = $content['etablissement']['unite_legale'] ?? [];
        $etablissement = $this->apiLib->denormalizeEtablissement($etablissementDatas);

        return new JsonResponse([
            'etablissement' => $etablissementDatas,
            'renderHtml' => $this->apiLib->getSingletablissementRender($etablissement)
        ]);
    }

    public function checkApiError($response)
    {
        if ($response->getStatusCode() === 500) {
            throw new NotFoundHttpException('Error to Sirene API');
        }
    }
}
