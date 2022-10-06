<?php

namespace App\Entity;

use App\Entity\Lottery\Ticket;
use App\Factory\Entity\FactorableEntityInterface;
use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet implements FactorableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $address = null;

    #[ORM\OneToOne(mappedBy: 'wallet', cascade: ['persist', 'remove'])]
    private ?Ticket $ticket = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(Ticket $ticket): self
    {
        // set the owning side of the relation if necessary
        if ($ticket->getWallet() !== $this) {
            $ticket->setWallet($this);
        }

        $this->ticket = $ticket;

        return $this;
    }
}
