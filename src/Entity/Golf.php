<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GolfRepository")
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *          "post"={"access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('IS_FULLY_AUTHENTICATED')"},
 *     },
 *     normalizationContext={"groups"={"golf", "minimal", "read"}},
 *     denormalizationContext={"groups"={"golf", "write"}}
 * )
 */
class Golf
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups("minimal")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Groups({"minimal", "golf"})
     */
    private string $name;

    /**
     * @var Collection<int, Course>
     *
     * One To Many - Unidirectionnal
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Course")
     * @ORM\JoinTable(
     *     name="golfs_courses",
     *     joinColumns={@ORM\JoinColumn(name="golf_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="course_id", referencedColumnName="id", unique=true)}
     * )
     * @Groups("golf")
     */
    private $courses;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contact")
     * @Groups("golf")
     */
    private Contact $contact;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    /**
     * @param Collection<int, Course> $courses
     * @return Golf
     */
    public function setCourses($courses): self
    {
        $this->courses = $courses;
        return $this;
    }

    public function addCourse(Course $course): self
    {
        $this->courses->add($course);
        return $this;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
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
