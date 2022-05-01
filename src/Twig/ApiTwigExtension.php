<?php

namespace App\Twig;

use App\Lib\ApiLib;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ApiTwigExtension extends AbstractExtension
{
    /**
     * @var ApiLib
     */
    private $apiLib;

    public function __construct(ApiLib $apiLib)
    {
        $this->apiLib = $apiLib;
    }

    /**
     * Get Twig functions defined in this extension.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('denormalizeEtablissement', array( $this->apiLib, 'denormalizeEtablissement')),
        );
    }
}
