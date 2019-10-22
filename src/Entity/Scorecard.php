<?php

namespace App\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScorecardRepository")
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
     */
    private $player;

    /**
     * @var Course
     * @ReferenceOne(
     *     targetDocument="Course",
     *     storeAs="id"
     * )
     */
    private $course;
    /**
     * @var Score[]
     * @EmbedMany(
     *     targetDocument="Score"
     * )
     */
    private $scores;
    /**
     * @var DateTime
     * @Field(type="date")
     */
    private $date;
    /**
     * @var float
     * @Field(type="float")
     */
    private $playerIndex;

    public function __construct()
    {
        $this->course = '';
        $this->scores = array();
        $this->playerIndex = 54.0;
    }

    /**
     * @PrePersist
     */
    public function prePersist()
    {
        if (!isset($this->date)) {
            $this->date = (new DateTime('now', new DateTimeZone($_ENV['DATETIME_TIMEZ'])))->format($_ENV['DATETIME_FORMAT']);
        }
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
    public function setPlayer(Player $player): Scorecard
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
    public function setCourse(Course $course): Scorecard
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @return Score[]
     */
    public function getScores(): array
    {
        return $this->scores;
    }

    /**
     * @param Score[] $scores
     * @return Scorecard
     */
    public function setScores(array $scores): Scorecard
    {
        $this->scores = $scores;
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
    public function setDate(DateTime $date): Scorecard
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
    public function setPlayerIndex(float $playerIndex): Scorecard
    {
        $this->playerIndex = $playerIndex;
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
     * @return Scorecard
     */
    public function setId(string $id): Scorecard
    {
        $this->id = $id;
        return $this;
    }
}