<?php

namespace App\Entity;

use App\Entity\Vehicle;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email" , message="Cet email est déja utilisé.")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Votre prénom doit avoir minimum {{ limit }} characteres.",
     *      maxMessage = "Votre prénom doit avoir maximum {{ limit }} characteres."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=30)
     *  @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Votre nom doit avoir minimum {{ limit }} characteres.",
     *      maxMessage = "Votre nom doit avoir maximum {{ limit }} characteres."
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $avatar;

    /**
     *  @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    protected $email;
   
    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $average_score;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibleStatus;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicle", mappedBy="user", orphanRemoval=true)
     */
    private $vehicles;

    /**
     * @ORM\Column(type="string" , nullable=false)
     * @Assert\NotBlank(message="Le numéro de téléphone est obligatoire")
     * @Assert\Length(
     *      min = 10,
     *      max = 10, 
     *      minMessage = "Le numéro de télephone doint contenir minimum 10 charactères.",
     *      maxMessage = "Le numéro de télephone doint contenir maximum 10 charactères."
     * )
     *  @Assert\Regex(pattern="/^[0-9]*$/", message="Numéro uniquement.") 
     */
    private $phoneNumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="owner")
     */
    private $ownerBookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="renter")
     */
    private $renterBookings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="customer")
     */
    private $comments;



    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->visibleStatus = 1 ;
        $this->renterDates = new ArrayCollection();
        $this->vehicles = new ArrayCollection();
        $this->enabled = 1;    
        $this->ownerBookings = new ArrayCollection();
        $this->renterBookings = new ArrayCollection();
        $this->comments = new ArrayCollection();   
        $this->avatar =  'pictures/no-user.png';
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAverageScore(): ?float
    {
        return $this->average_score;
    }

    public function setAverageScore(?float $average_score): self
    {
        $this->average_score = $average_score;

        return $this;
    }

    public function getVisibleStatus(): ?int
    {
        return $this->visibleStatus;
    }

    public function setVisibleStatus(bool $visibleStatus): self
    {
        $this->visibleStatus = $visibleStatus;

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
     * @return Collection|Vehicle[]
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    public function addVehicle(Vehicle $vehicle): self
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles[] = $vehicle;
            $vehicle->setUser($this);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): self
    {
        if ($this->vehicles->contains($vehicle)) {
            $this->vehicles->removeElement($vehicle);
            // set the owning side to null (unless already changed)
            if ($vehicle->getUser() === $this) {
                $vehicle->setUser(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getOwnerBookings(): Collection
    {
        return $this->ownerBookings;
    }

    public function addOwnerBooking(Booking $ownerBooking): self
    {
        if (!$this->ownerBookings->contains($ownerBooking)) {
            $this->ownerBookings[] = $ownerBooking;
            $ownerBooking->setOwner($this);
        }

        return $this;
    }

    public function removeOwnerBooking(Booking $ownerBooking): self
    {
        if ($this->ownerBookings->contains($ownerBooking)) {
            $this->ownerBookings->removeElement($ownerBooking);
            // set the owning side to null (unless already changed)
            if ($ownerBooking->getOwner() === $this) {
                $ownerBooking->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getRenterBookings(): Collection
    {
        return $this->renterBookings;
    }

    public function addRenterBooking(Booking $renterBooking): self
    {
        if (!$this->renterBookings->contains($renterBooking)) {
            $this->renterBookings[] = $renterBooking;
            $renterBooking->setRenter($this);
        }

        return $this;
    }

    public function removeRenterBooking(Booking $renterBooking): self
    {
        if ($this->renterBookings->contains($renterBooking)) {
            $this->renterBookings->removeElement($renterBooking);
            // set the owning side to null (unless already changed)
            if ($renterBooking->getRenter() === $this) {
                $renterBooking->setRenter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCustomer($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getCustomer() === $this) {
                $comment->setCustomer(null);
            }
        }

        return $this;
    }

    public function getCountPost(): int
    {
        $allVehicles = $this->getVehicles();

        $countPost = 0 ;

       foreach($allVehicles as $vehicle){

        if( $vehicle->getDates() != null && $vehicle->getStatus() == 1){
            $countPost++ ;
        }
    }
        return $countPost ;

    }

    public function getCountLeasingOk(): int
    {

       $allBookingsOwner = $this->getOwnerBookings() ;
       $allBookingsRenter = $this->getRenterBookings() ;

       $bookingsOk = 0 ;

       foreach($allBookingsOwner as $booking ){

            if( $booking->getStatus() == 5 ) {
                $bookingsOk++ ;
            }
       }
       foreach($allBookingsRenter as $booking ){

            if( $booking->getStatus() == 5 ) {
                
                $bookingsOk++ ;
            }
       }

       return $bookingsOk ;
    }
 

}
