<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

   

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

    /**
     * @ORM\Column(type="array", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="ownerBookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="renterBookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $renter;

    /**
     * @ORM\Column(type="array")
     */
    private $dateRenter = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", mappedBy="ownerComment", cascade={"persist", "remove"})
     */
    private $commentOwner;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Comment", mappedBy="renterComment", cascade={"persist", "remove"})
     */
    private $commentRenter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $NoticeOwnerStatus;

    /**
     * @ORM\Column(type="boolean")
     */
    private $NoticeRenterStatus;

 

    public function __construct()
    {
        $this->Owner = new ArrayCollection();
        $this->owner = new ArrayCollection();
        $this->renter = new ArrayCollection();
        $this->createdAt = new \Datetime ;
        $this->updatedAt = new \Datetime ;
        $this->NoticeOwnerStatus = 1 ;
        $this->NoticeRenterStatus = 1 ;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?array
    {
        return $this->date;
    }

    public function setDate(array $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRenter(): ?User
    {
        return $this->renter;
    }

    public function setRenter(?User $renter): self
    {
        $this->renter = $renter;

        return $this;
    }

    public function getDateRenter(): ?array
    {
        return $this->dateRenter;
    }

    public function setDateRenter(array $dateRenter): self
    {
        $this->dateRenter = $dateRenter;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
    * @ORM\PreUpdate()
    */
    
    public function changeUpdatedAtOnPersist()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getCommentOwner(): ?Comment
    {
        return $this->commentOwner;
    }

    public function setCommentOwner(?Comment $commentOwner): self
    {
        $this->commentOwner = $commentOwner;

        // set (or unset) the owning side of the relation if necessary
        $newOwnerComment = $commentOwner === null ? null : $this;
        if ($newOwnerComment !== $commentOwner->getOwnerComment()) {
            $commentOwner->setOwnerComment($newOwnerComment);
        }

        return $this;
    }

    public function getCommentRenter(): ?Comment
    {
        return $this->commentRenter;
    }

    public function setCommentRenter(?Comment $commentRenter): self
    {
        $this->commentRenter = $commentRenter;

        // set (or unset) the owning side of the relation if necessary
        $newRenterComment = $commentRenter === null ? null : $this;
        if ($newRenterComment !== $commentRenter->getRenterComment()) {
            $commentRenter->setRenterComment($newRenterComment);
        }

        return $this;
    }

    public function getNoticeOwnerStatus(): ?bool
    {
        return $this->NoticeOwnerStatus;
    }

    public function setNoticeOwnerStatus(bool $NoticeOwnerStatus): self
    {
        $this->NoticeOwnerStatus = $NoticeOwnerStatus;

        return $this;
    }

    public function getNoticeRenterStatus(): ?bool
    {
        return $this->NoticeRenterStatus;
    }

    public function setNoticeRenterStatus(bool $NoticeRenterStatus): self
    {
        $this->NoticeRenterStatus = $NoticeRenterStatus;

        return $this;
    }
}
