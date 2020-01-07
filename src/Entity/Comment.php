<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     *  @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      minMessage = "La note minimum est de {{ limit }}",
     *      maxMessage = "La note maximum est de {{ limit }}" )

     */
    private $score;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", inversedBy="commentOwner", cascade={"persist", "remove"})
     */
    private $ownerComment;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Booking", inversedBy="commentRenter", cascade={"persist", "remove"})
     */
    private $renterComment;

    public function __construct()
    {
        $this->createdAt = new \Datetime();
    }

    public function __toString()
    {
     return $this->content;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getOwnerComment(): ?Booking
    {
        return $this->ownerComment;
    }

    public function setOwnerComment(?Booking $ownerComment): self
    {
        $this->ownerComment = $ownerComment;

        return $this;
    }

    public function getRenterComment(): ?Booking
    {
        return $this->renterComment;
    }

    public function setRenterComment(?Booking $renterComment): self
    {
        $this->renterComment = $renterComment;

        return $this;
    }
    
}
