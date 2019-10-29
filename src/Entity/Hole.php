<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HoleRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"hole", "read"}},
 *     denormalizationContext={"groups"={"hole", "write"}}
 * )
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
     * @Groups({"course", "landmark", "hole"})
     */
    private $number;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
     */
    private $par;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
     */
    private $handicap;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
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
     * @return int?
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Hole
     */
    public function setId(int $id): self
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
