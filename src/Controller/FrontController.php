<?php

namespace App\Controller;

use App\Form\SearchCompanyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="app_front_home")
     * @Template("Front/home.html.twig")
     */
    public function getHome(Request $request)
    {
        $searchForm = $this->createForm(SearchCompanyType::class);

        return [
            'searchForm' => $searchForm->createView()
        ];
    }
}
