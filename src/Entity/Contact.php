<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
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
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $address;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $city;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $postalCode;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $telephoneNumber;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Contact
     */
    public function setAddress(string $address): Contact
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Contact
     */
    public function setCity(string $city): Contact
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     * @return Contact
     */
    public function setPostalCode(string $postalCode): Contact
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber(): string
    {
        return $this->telephoneNumber;
    }

    /**
     * @param string $telephoneNumber
     * @return Contact
     */
    public function setTelephoneNumber(string $telephoneNumber): Contact
    {
        $this->telephoneNumber = $telephoneNumber;
        return $this;
    }
}