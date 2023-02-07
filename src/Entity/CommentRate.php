<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CommentRateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRateRepository::class)]
class CommentRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Comment $comment = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ratedBy = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'Rate must be between {{ min }} and {{ max }}',
        min: 1,
        max: 5,
    )]
    private ?int $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?Comment
    {
        return $this->comment;
    }

    public function setComment(?Comment $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRatedBy(): ?User
    {
        return $this->ratedBy;
    }

    public function setRatedBy(?User $ratedBy): self
    {
        $this->ratedBy = $ratedBy;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }
}
