<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScorecardRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"scorecard", "read"}},
 *     denormalizationContext={"groups"={"scorecard", "write"}}
 * )
 */
class Scorecard
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="scorecards")
     * @Groups("scorecard")
     */
    private Player $player;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @Groups("scorecard")
     */
    private Course $course;

    /**
     * @var Collection<int, Score>
     * @ORM\ManyToMany(targetEntity="App\Entity\Score")
     * @ORM\JoinTable(
     *     name="scorecards_scores",
     *     joinColumns={@ORM\JoinColumn(name="scorecard_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="score_id", referencedColumnName="id")}
     * )
     * @Groups("scorecard")
     */
    private $scores;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Landmark")
     * @Groups("scorecard")
     */
    private Landmark $landmark;

    /**
     * @ORM\Column(type="date")
     * @Groups("scorecard")
     */
    private DateTime $date;

    /**
     * @ORM\Column(type="float")
     * @Groups("scorecard")
     */
    private float $playerIndex;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->playerIndex = 54.0;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): self
    {
        $this->player = $player;
        return $this;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function setCourse(Course $course): self
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    /**
     * @param Collection<int, Score> $scores
     * @return Scorecard
     */
    public function setScores($scores): self
    {
        $this->scores = $scores;
        return $this;
    }

    public function getLandmark(): Landmark
    {
        return $this->landmark;
    }

    public function setLandmark(Landmark $landmark): self
    {
        $this->landmark = $landmark;
        return $this;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getPlayerIndex(): float
    {
        return $this->playerIndex;
    }

    public function setPlayerIndex(float $playerIndex): self
    {
        $this->playerIndex = $playerIndex;
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
