<?php

namespace App\Entity;
use App\Entity\Student;
use App\Repository\StaffRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=StaffRepository::class)
 */
class Staff
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
    private $email;

    /**
     * @ORM\Column(type="integer")
     */
    private $staff_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

        /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


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
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="App\Entity\Student", mappedBy="classteacher")
     */
    private $pupils;


    /**
     * @ORM\Column(type="string")
     */
    private $brochureFilename;

    

    public function __construct()
    {
        $this->pupils  = new ArrayCollection();
    }

    /**
     * @return Collection|Product[]
     */
    public function getPupils(): Collection
    {
        return $this->pupils;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getBrochureFilename()
    {
        return $this->brochureFilename;
    }

    public function setBrochureFilename($brochureFilename)
    {
        $this->brochureFilename = $brochureFilename;

        return $this;
    }

    public function getStaffId(): ?int
    {
        return $this->staff_id;
    }

    public function setStaffId(int $staff_id): self
    {
        $this->staff_id = $staff_id;

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
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function addPupil(Student $pupil): self
    {
        if (!$this->pupils->contains($pupil)) {
            $this->pupils[] = $pupil;
            $pupil->setClassteacher($this);
        }

        return $this;
    }

    public function removePupil(Student $pupil): self
    {
        if ($this->pupils->removeElement($pupil)) {
            // set the owning side to null (unless already changed)
            if ($pupil->getClassteacher() === $this) {
                $pupil->setClassteacher(null);
            }
        }

        return $this;
    }
}
