<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Etablissement
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $siren;

    /**
     * @var string
     */
    protected $siret;

    /**
     * @var string
     */
    protected $nic;

    /**
     * @var string
     */
    protected $fullAddress;

    /**
     * @var string
     */
    protected $companyCode;

    /**
     * @var string
     */
    protected $companyLabel;

    /**
     * @var string
     */
    protected $activity;

    public function __construct()
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSiren(): string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    public function getSiret(): string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getNic(): string
    {
        return $this->nic;
    }

    public function setNic(string $nic): self
    {
        $this->nic = $nic;

        return $this;
    }

    public function getFullAddress(): string
    {
        return $this->fullAddress;
    }

    public function setFullAddress(string $fullAddress): self
    {
        $this->fullAddress = $fullAddress;

        return $this;
    }

    public function getCompanyCode(): string
    {
        return $this->companyCode;
    }

    public function setCompanyCode(string $companyCode): self
    {
        $this->companyCode = $companyCode;

        return $this;
    }

    public function getCompanyLabel(): string
    {
        return $this->companyLabel;
    }

    public function setCompanyLabel(string $companyLabel): self
    {
        $this->companyLabel = $companyLabel;

        return $this;
    }

    public function getActivity(): string
    {
        return $this->activity;
    }

    public function setActivity(string $activity): self
    {
        $this->activity = $activity;

        return $this;
    }
}
