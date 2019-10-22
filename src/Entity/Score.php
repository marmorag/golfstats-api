<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Hole
     * @ORM\OneToOne(targetEntity="App\Entity\Hole")
     */
    private $hole;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Score
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Hole
     */
    public function getHole(): Hole
    {
        return $this->hole;
    }

    /**
     * @param Hole $hole
     * @return Score
     */
    public function setHole(Hole $hole): self
    {
        $this->hole = $hole;
        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     * @return Score
     */
    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }
}