<?php

namespace App\Entity;

use App\Repository\TimeTableRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimeTableRepository::class)
 */
class TimeTable
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
    private $subject;

    /**
     * @ORM\Column(type="time")
     */
    private $starttime;

    /**
     * @ORM\Column(type="time")
     */
    private $endtime;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private $class;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
    public function getStarttime(): ?\DateTimeInterface
    {
        return $this->starttime;
    }
    public function setStarttime(\DateTimeInterface $starttime): self
    {
        $this->starttime = $starttime;

        return $this;
    }
    public function getEndtime(): ?\DateTimeInterface
    {
        return $this->endtime;
    }

    public function setEndtime(\DateTimeInterface $endtime): self
    {
        $this->endtime = $endtime;

        return $this;
    }
}
