<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"score", "read"}},
 *     denormalizationContext={"groups"={"score", "write"}}
 * )
 */
class Score
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Hole")
     * @Groups({"score", "scorecard"})
     */
    private Hole $hole;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"score", "scorecard"})
     */
    private int $score;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getHole(): Hole
    {
        return $this->hole;
    }

    public function setHole(Hole $hole): self
    {
        $this->hole = $hole;
        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }
}
