<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Golf
 * @package App\Document
 *
 * @ORM\Entity(repositoryClass="App\Repository\GolfRepository")
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
     * @Groups("minimal")
     */
    private $name;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\Course")
     */
    private $courses;

    /**
     * @var \App\Entity\Contact
     * @ORM\OneToOne(targetEntity="App\Entity\Contact")
     */
    private $contact;

    public function __construct()
    {
        $this->name = '';
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