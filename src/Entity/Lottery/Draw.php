<?php

namespace App\Entity\Lottery;

use App\Entity\Transaction;
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

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $launched_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $done_at = null;

    #[ORM\OneToMany(mappedBy: 'draw', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column]
    private ?bool $is_done = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Transaction $winner = null;

    public function __construct(
    ) {
        $this->created_at = new DateTimeImmutable();
        $this->transactions = new ArrayCollection();
        $this->is_done = false;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getLaunchedAt(): ?\DateTimeImmutable
    {
        return $this->launched_at;
    }

    public function setLaunchedAt(?\DateTimeImmutable $launched_at): self
    {
        $this->launched_at = $launched_at;

        return $this;
    }

    public function getDoneAt(): ?\DateTimeImmutable
    {
        return $this->done_at;
    }

    public function setDoneAt(?\DateTimeImmutable $done_at): self
    {
        $this->done_at = $done_at;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setDraw($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getDraw() === $this) {
                $transaction->setDraw(null);
            }
        }

        return $this;
    }

    public function isIsDone(): ?bool
    {
        return $this->is_done;
    }

    public function setIsDone(bool $is_done): self
    {
        $this->is_done = $is_done;

        return $this;
    }

    public function getWinner(): ?Transaction
    {
        return $this->winner;
    }

    public function setWinner(?Transaction $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
