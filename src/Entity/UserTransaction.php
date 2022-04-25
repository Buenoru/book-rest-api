<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserTransactionRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTransactionRepository::class)
 */
class UserTransaction
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user_from;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user_to;

    /**
     * @ORM\Column(type="integer", columnDefinition="int not null CHECK (amount >= 0)")
     */
    private int $amount;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $is_success = false;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private DateTimeImmutable $creted_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFrom(): ?User
    {
        return $this->user_from;
    }

    public function setUserFrom(?User $user_from): self
    {
        $this->user_from = $user_from;

        return $this;
    }

    public function getUserTo(): ?User
    {
        return $this->user_to;
    }

    public function setUserTo(?User $user_to): self
    {
        $this->user_to = $user_to;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function isIsSuccess(): bool
    {
        return $this->is_success;
    }

    public function setIsSuccess(bool $is_success): self
    {
        $this->is_success = $is_success;

        return $this;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCretedAt(): DateTimeImmutable
    {
        return $this->creted_at;
    }

    public function setCretedAt(DateTimeImmutable $creted_at): self
    {
        $this->creted_at = $creted_at;

        return $this;
    }
}
