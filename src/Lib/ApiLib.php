<?php

namespace App\Lib;

use App\Entity\Etablissement;
use Twig\Environment;

class ApiLib
{
    /** @var Environment */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param array $datas
     * @return Etablissement|null
     */
    public function denormalizeEtablissement(array $datas)
    {
        if (empty($datas)) return null;

        $etablissement = new Etablissement();
        $etablissement
            ->setSiren($datas['siren'] ?? null)
            ->setAddress($datas['geo_adresse'] ?? null)
            ->setName($datas['denomination'] ?? null)
            ->setCategory($datas['categorie_entreprise'] ?? null)
            ->setCreatedAt($datas['date_creation'] ? new \DateTime($datas['date_creation']) : null);


        $siege = $datas['etablissement_siege'] ?? null;

        if ($siege !== null) {
            $etablissement
                ->setAddress($siege['geo_adresse'] ?? null)
                ->setSiret($siege['siret'] ?? null)
                ->setNic($siege['nic'] ?? null);
        }

        return $etablissement;
    }

    /**
     * @param array $etablissements
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getEtablissementListRender(array $etablissements)
    {
        return $this->twig->render('Api/Etablissement/list.html.twig', [
            'etablissements' => $etablissements
        ]);
    }
}
