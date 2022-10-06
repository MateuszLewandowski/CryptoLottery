<?php

namespace App\Entity\Lottery;

use App\Factory\Entity\FactorableEntityInterface;
use App\Repository\Lottery\DrawRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrawRepository::class)]
class Draw implements FactorableEntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $tickets_quantity = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lauched_at = null;

    #[ORM\OneToMany(mappedBy: 'draw', targetEntity: Ticket::class)]
    private Collection $tickets;

    #[ORM\Column]
    private ?bool $is_done = null;

    public function __construct(
    ) {
        $this->is_done = false;
        $this->tickets = new ArrayCollection();
        $this->tickets_quantity = 0;
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTicketsQuantity(): ?int
    {
        return $this->tickets_quantity;
    }

    public function setTicketsQuantity(?int $tickets_quantity): self
    {
        $this->tickets_quantity = $tickets_quantity;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLauchedAt(): ?\DateTimeImmutable
    {
        return $this->lauched_at;
    }

    public function setLauchedAt(?\DateTimeImmutable $lauched_at): self
    {
        $this->lauched_at = $lauched_at;

        return $this;
    }

    public function getIsDone(): bool 
    {
        return $this->is_done;
    }

    public function setIsDone(bool $is_done): self 
    {
        $this->is_done = $is_done;

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setDraw($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getDraw() === $this) {
                $ticket->setDraw(null);
            }
        }

        return $this;
    }
}
