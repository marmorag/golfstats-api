<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LandmarkRepository")
 */
class Landmark
{

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var \App\Entity\Hole[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Hole")
     */
    private $holes;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $sssMen;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $sssLady;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $slopeMen;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $slopeLady;

    /**
     * Landmark constructor.
     */
    public function __construct()
    {
        $this->holes = new ArrayCollection();
        $this->slopeLady = 0.0;
        $this->slopeMen = 0.0;
        $this->sssLady = 0.0;
        $this->sssMen = 0.0;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Landmark
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Hole[]|ArrayCollection
     */
    public function getHoles(): Collection
    {
        return $this->holes;
    }

    /**
     * @param Hole[] $holes
     * @return Landmark
     */
    public function setHoles($holes): self
    {
        if ($holes instanceof ArrayCollection){
            $this->holes = $holes;
        } else {
            $this->holes = new ArrayCollection($holes);
        }
        return $this;
    }

    /**
     * @return float
     */
    public function getSssMen(): float
    {
        return $this->sssMen;
    }

    /**
     * @param float $sssMen
     * @return Landmark
     */
    public function setSssMen(float $sssMen): self
    {
        $this->sssMen = $sssMen;
        return $this;
    }

    /**
     * @return float
     */
    public function getSssLady(): float
    {
        return $this->sssLady;
    }

    /**
     * @param float $sssLady
     * @return Landmark
     */
    public function setSssLady(float $sssLady): self
    {
        $this->sssLady = $sssLady;
        return $this;
    }

    /**
     * @return float
     */
    public function getSlopeMen(): float
    {
        return $this->slopeMen;
    }

    /**
     * @param float $slopeMen
     * @return Landmark
     */
    public function setSlopeMen(float $slopeMen): self
    {
        $this->slopeMen = $slopeMen;
        return $this;
    }

    /**
     * @return float
     */
    public function getSlopeLady(): float
    {
        return $this->slopeLady;
    }

    /**
     * @param float $slopeLady
     * @return Landmark
     */
    public function setSlopeLady(float $slopeLady): self
    {
        $this->slopeLady = $slopeLady;
        return $this;
    }

    /**
     * @return string?
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Landmark
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

}