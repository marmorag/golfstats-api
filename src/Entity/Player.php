<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player extends User
{
    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $index;

    /**
     * @var Scorecard[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Scorecard", mappedBy="player", cascade={"all"})
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