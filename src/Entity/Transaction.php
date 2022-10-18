<?php

namespace App\Entity;

use App\Entity\Lottery\Draw;
use App\Factory\Entity\FactorableEntityInterface;
use App\Repository\TransactionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction implements FactorableEntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?string $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $block_number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $block_hash = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $timestamp = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $hash = null;

    #[ORM\Column(length: 255)]
    private ?string $transaction_from = null;

    #[ORM\Column(length: 255)]
    private ?string $transaction_to = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $value = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $gas = null;

    #[ORM\Column(type: 'bigint')]
    private ?int $gas_price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Draw $draw = null;

    public function __construct(
    ) {
        $this->created_at = new DateTimeImmutable();        
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getBlockNumber(): ?string
    {
        return $this->block_number;
    }

    public function setBlockNumber(?string $block_number): self
    {
        $this->block_number = $block_number;

        return $this;
    }

    public function getBlockHash(): ?string
    {
        return $this->block_hash;
    }

    public function setBlockHash(?string $block_hash): self
    {
        $this->block_hash = $block_hash;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(?string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getTransactionFrom(): ?string
    {
        return $this->transaction_from;
    }

    public function setTransactionFrom(string $transaction_from): self
    {
        $this->transaction_from = $transaction_from;

        return $this;
    }

    public function getTransactionTo(): ?string
    {
        return $this->transaction_to;
    }

    public function setTransactionTo(string $transaction_to): self
    {
        $this->transaction_to = $transaction_to;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getGas(): ?int
    {
        return $this->gas;
    }

    public function setGas(int $gas): self
    {
        $this->gas = $gas;

        return $this;
    }

    public function getGasPrice(): ?int
    {
        return $this->gas_price;
    }

    public function setGasPrice(int $gas_price): self
    {
        $this->gas_price = $gas_price;

        return $this;
    }

    public function getDraw(): ?Draw
    {
        return $this->draw;
    }

    public function setDraw(?Draw $draw): self
    {
        $this->draw = $draw;

        return $this;
    }
}
