<?php

namespace App\Lib;

use Twig\Environment;


class ApiLib
{
    /** @var Environment */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function getEtablissementListRender(array $etablissement)
    {
        return $this->twig->render('Api/Etablissement/list.html.twig', [
            'etablissements' => $etablissement
        ]);
    }
}
