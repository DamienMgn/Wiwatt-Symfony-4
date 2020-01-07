<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DateRepository")
 */
class Date
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $availableDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="dates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvailableDate(): ?\DateTimeInterface
    {
        return $this->availableDate;
    }

    public function setAvailableDate(\DateTimeInterface $availableDate): self
    {
        $this->availableDate = $availableDate;

        return $this;
    }


    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

   
}
