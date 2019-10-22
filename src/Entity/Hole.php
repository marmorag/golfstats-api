<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HoleRepository")
 */
class Hole
{

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $number;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $par;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $handicap;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $length;

    /**
     * Hole constructor.
     */
    public function __construct()
    {
        $this->number = 0;
        $this->par = 0;
        $this->handicap = 0;
        $this->length = 0;
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
     * @return Hole
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return Hole
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function getPar(): int
    {
        return $this->par;
    }

    /**
     * @param int $par
     * @return Hole
     */
    public function setPar(int $par): self
    {
        $this->par = $par;
        return $this;
    }

    /**
     * @return int
     */
    public function getHandicap(): int
    {
        return $this->handicap;
    }

    /**
     * @param int $handicap
     * @return Hole
     */
    public function setHandicap(int $handicap): self
    {
        $this->handicap = $handicap;
        return $this;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return Hole
     */
    public function setLength(int $length): self
    {
        $this->length = $length;
        return $this;
    }
}