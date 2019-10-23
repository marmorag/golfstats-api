<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"course", "read"}},
 *     denormalizationContext={"groups"={"course", "write"}}
 * )
 */
class Course
{

    /**
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Groups("course")
     */
    private $name;

    /**
     * @var \App\Entity\Landmark[]|Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Landmark", mappedBy="id")
     * @Groups("course")
     */
    private $landmarks;

    public function __construct()
    {
        $this->landmarks = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Course
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Landmark[]|ArrayCollection
     */
    public function getLandmarks(): Collection
    {
        return $this->landmarks;
    }

    /**
     * @param Landmark[]|ArrayCollection $landmarks
     * @return Course
     */
    public function setLandmarks($landmarks): self
    {
        if ($landmarks instanceof ArrayCollection){
            $this->landmarks = $landmarks;
        } else {
            $this->landmarks = new ArrayCollection($landmarks);
        }

        return $this;
    }

    /**
     * @param Landmark $landmark
     * @return $this
     */
    public function addLandmark(Landmark $landmark): self
    {
        $this->landmarks->add($landmark);
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
     * @return Course
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }
}