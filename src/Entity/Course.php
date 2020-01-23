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
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @Groups("course")
     */
    private string $name;

    /**
     * @var Collection<int, Landmark>
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Landmark")
     * @ORM\JoinTable(
     *     name="courses_landmarks",
     *     joinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="landmark_id", referencedColumnName="id", unique=true)}
     * )
     * @Groups("course")
     */
    private $landmarks;

    public function __construct()
    {
        $this->landmarks = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, Landmark>
     */
    public function getLandmarks(): Collection
    {
        return $this->landmarks;
    }

    /**
     * @param Collection<int, Landmark> $landmarks
     * @return Course
     */
    public function setLandmarks($landmarks): self
    {
        $this->landmarks = $landmarks;
        return $this;
    }

    public function addLandmark(Landmark $landmark): self
    {
        $this->landmarks->add($landmark);
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
