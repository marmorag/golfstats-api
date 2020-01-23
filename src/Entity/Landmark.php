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
    private int $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups({"course", "landmark", "scorecard"})
     */
    private string $name;

    /**
     * @var Collection<int, Hole>
     *
     * One To Many - Unidirectionnal
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Hole")
     * @ORM\JoinTable(
     *     name="landmarks_holes",
     *     joinColumns={@ORM\JoinColumn(name="landmark_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="hole_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @Groups({"course", "landmark"})
     */
    private $holes;

    /**
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private float $sssMen;

    /**
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private float $sssLady;

    /**
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private float $slopeMen;

    /**
     * @ORM\Column(type="float")
     * @Groups({"course", "landmark"})
     */
    private float $slopeLady;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Hole>
     */
    public function getHoles(): Collection
    {
        return $this->holes;
    }

    /**
     * @param Collection<int, Hole> $holes
     * @return Landmark
     */
    public function setHoles($holes): self
    {
        $this->holes = $holes;
        return $this;
    }

    public function getSssMen(): float
    {
        return $this->sssMen;
    }

    public function setSssMen(float $sssMen): self
    {
        $this->sssMen = $sssMen;
        return $this;
    }

    public function getSssLady(): float
    {
        return $this->sssLady;
    }

    public function setSssLady(float $sssLady): self
    {
        $this->sssLady = $sssLady;
        return $this;
    }

    public function getSlopeMen(): float
    {
        return $this->slopeMen;
    }

    public function setSlopeMen(float $slopeMen): self
    {
        $this->slopeMen = $slopeMen;
        return $this;
    }

    public function getSlopeLady(): float
    {
        return $this->slopeLady;
    }

    public function setSlopeLady(float $slopeLady): self
    {
        $this->slopeLady = $slopeLady;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
