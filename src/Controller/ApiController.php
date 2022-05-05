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
        $perPage = $request->get('perPage') ?? 5;
        $page = $request->get('page') ?? 1;

        $url = self::SIRENE_V3_URL."/unites_legales/?denomination={$term}&per_page={$perPage}&page={$page}";

        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() === 500) {
            throw new NotFoundHttpException('Error to Sirene API');
        }

        $content = [];

        try {
            $content = $response->toArray();
        } catch (\Exception $e) {}

        $etablissements = $content['unites_legales'] ?? [];

        return new JsonResponse([
            'searchName' => $term,
            'perPage' => $perPage,
            'page' => $page,
            'totalPages' => $content['totalPages'] ?? 1,
            'totalResults' => $content['totalPages'] ?? sizeof($etablissements),
            'etablissement' => $etablissements,
            'renderHtml' => $this->apiLib->getEtablissementListRender($etablissements)
        ]);
    }

    /**
     * @Rest\Get("/show/{siret}", name="_view_company")
     */
    public function getCompany(Request $request, $siret)
    {
        $url = self::SIRENE_V3_URL."/etablissements/{$siret}";

        $response = $this->client->request('GET', $url);
        $content = $response->toArray();

        if ($response->getStatusCode() !== 200) {
            throw new NotFoundHttpException('Sirene API not found');
        }

        $etablissementDatas = $content['etablissement'] ?? [];
        dd($etablissementDatas);
        $etablissement = $this->apiLib->denormalizeEtablissement($etablissementDatas);
        dd($etablissement);

        return new JsonResponse([
            'etablissement' => $etablissementDatas,
            'renderHtml' => ''
        ]);
    }
}
