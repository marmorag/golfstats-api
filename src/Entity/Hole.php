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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
     */
    private int $number;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
     */
    private int $par;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
     */
    private int $handicap;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"course", "landmark", "hole"})
     */
    private int $length;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function getPar(): int
    {
        return $this->par;
    }

    public function setPar(int $par): self
    {
        $this->par = $par;
        return $this;
    }

    public function getHandicap(): int
    {
        return $this->handicap;
    }

    public function setHandicap(int $handicap): self
    {
        $this->handicap = $handicap;
        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;
        return $this;
    }
}
