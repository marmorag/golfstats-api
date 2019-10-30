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
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="App\Entity\Player", inversedBy="scorecards")
     * @Groups("scorecard")
     */
    private $player;

    /**
     * @var Course
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @Groups("scorecard")
     */
    private $course;

    /**
     * @var Collection|Score[]
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
     * @var Landmark
     * @ORM\ManyToOne(targetEntity="App\Entity\Landmark")
     * @Groups("scorecard")
     */
    private $landmark;

    /**
     * @var DateTime
     * @ORM\Column(type="date")
     * @Groups("scorecard")
     */
    private $date;

    /**
     * @var float
     * @ORM\Column(type="float")
     * @Groups("scorecard")
     */
    private $playerIndex;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->playerIndex = 54.0;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     * @return Scorecard
     */
    public function setPlayer(Player $player): self
    {
        $this->player = $player;
        return $this;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @param Course $course
     * @return Scorecard
     */
    public function setCourse(Course $course): self
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    /**
     * @param Collection|Score[] $scores
     * @return Scorecard
     */
    public function setScores($scores): self
    {
        $this->scores = $scores;
        return $this;
    }

    /**
     * @return Landmark
     */
    public function getLandmark(): Landmark
    {
        return $this->landmark;
    }

    /**
     * @param Landmark $landmark
     * @return Scorecard
     */
    public function setLandmark(Landmark $landmark): self
    {
        $this->landmark = $landmark;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Scorecard
     */
    public function setDate(DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return float
     */
    public function getPlayerIndex(): float
    {
        return $this->playerIndex;
    }

    /**
     * @param float $playerIndex
     * @return Scorecard
     */
    public function setPlayerIndex(float $playerIndex): self
    {
        $this->playerIndex = $playerIndex;
        return $this;
    }

    /**
     * @return int?
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Scorecard
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
