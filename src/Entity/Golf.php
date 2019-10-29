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
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups("minimal")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     *
     * @Groups({"minimal", "golf"})
     */
    private $name;

    /**
     * @var Collection|Course[]
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
     * @var \App\Entity\Contact
     * @ORM\OneToOne(targetEntity="App\Entity\Contact")
     * @Groups("golf")
     */
    private $contact;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    /**
     * @param Course[]|ArrayCollection $courses
     * @return Golf
     */
    public function setCourses($courses): self
    {
        if ($courses instanceof ArrayCollection){
            $this->courses = $courses;
        }
        else {
            $this->courses = new ArrayCollection($courses);
        }

        return $this;
    }

    /**
     * @param Course $course
     * @return Golf
     */
    public function addCourse(Course $course): self
    {
        $this->courses->add($course);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    /**
     * @return Contact
     */
    public function getContact(): Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact $contact
     * @return Golf
     */
    public function setContact(Contact $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param string $name
     * @return Golf
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * @return Golf
     */
    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }
}