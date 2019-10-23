<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"player", "read"}},
 *     denormalizationContext={"groups"={"player", "write"}}
 * )
 */
class Player extends User
{
    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups("player")
     */
    private $index;

    /**
     * @var Scorecard[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Scorecard", mappedBy="player", cascade={"all"})
     * @Groups("player")
     */
    private $scorecards;

    /**
     * @return float
     */
    public function getIndex(): float
    {
        return $this->index;
    }

    /**
     * @param float $index
     * @return Player
     */
    public function setIndex(float $index): self
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScorecards()
    {
        return $this->scorecards;
    }

    /**
     * @param Collection|Scorecard[] $scorecards
     * @return Player
     */
    public function setScorecards($scorecards): self
    {
        $this->scorecards = $scorecards;
        return $this;
    }


}