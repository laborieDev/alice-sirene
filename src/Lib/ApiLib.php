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

    public function denormalizeEtablissement(array $datas)
    {
        if (empty($datas)) return null;

        $etablissement = new Etablissement();
        $etablissement
            ->setSiren($datas['siren'])
            ->setSiret($datas['siret'])
            ->setNic($datas['nic'])
            ->setName($datas['l1_normalisee'])
            ->setActivity($datas['libelle_activite_principale_entreprise'])
            ->setCompanyLabel($datas['libelle_nature_juridique_entreprise'])
            ->setCompanyCode($datas['activite_principale_entreprise'])
            ->constructFullAddress($datas);

        return $etablissement;
    }

    public function getEtablissementListRender(array $etablissements)
    {
        return $this->twig->render('Api/Etablissement/list.html.twig', [
            'etablissements' => $etablissements
        ]);
    }
}
