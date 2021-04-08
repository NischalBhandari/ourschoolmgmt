<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=TeacherRepository::class)
 */
class Teacher extends User
{


    /**
     * @ORM\Column(type="string", length=40)
     */
    private $Phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $citizenship;


    public function getId(): ?int
    {
        return get_parent_class($this)->id;
    }

    public function getPhone(): ?string
    {
        return $this->Phone;
    }

    public function setPhone(string $Phone): self
    {
        $this->Phone = $Phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
    public function getCitizenship(): ?string
    {
        return $this->citizenship;
    }

    public function setCitizenship(string $citizenship): self
    {
        $this->address = $citizenship;

        return $this;
    }


}
