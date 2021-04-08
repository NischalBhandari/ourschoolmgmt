<?php

namespace App\Entity;
use App\Entity\Staff;
use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $class;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $section;

    /**
     * @ORM\Column(type="date")
     */
    private $admission_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

     /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="App\Entity\Staff", inversedBy="pupils")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $classteacher;
    public function getClassteacher(): ?Staff
    {
        return $this->classteacher;
    }

    public function setClassteacher(?Staff $classteacher): self
    {
        $this->classteacher = $classteacher;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getClass(): ?int
    {
        return $this->class;
    }

    public function setClass(int $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getAdmissionDate(): ?\DateTimeInterface
    {
        return $this->admission_date;
    }

    public function setAdmissionDate(\DateTimeInterface $admission_date): self
    {
        $this->admission_date = $admission_date;

        return $this;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function setParent(string $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

        /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function __toString(){
        // to show the name of the Category in the select
        return $this->name;
        // to show the id of the Category in the select
        // return $this->id;
    }
}
