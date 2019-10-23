<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LandmarkRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"landmark", "read"}},
 *     denormalizationContext={"groups"={"landmark", "write"}}
 * )
 */
class Landmark
{

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("scorecard")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"course", "landmark", "scorecard"})
     */
    private $name;

    /**
     * @var \App\Entity\Hole[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Hole", mappedBy="id")
     * @Groups({"course", "landmark"})
     */
    private $holes;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private $sssMen;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private $sssLady;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private $slopeMen;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
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