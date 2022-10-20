<?php

namespace App\Entity\Admin;

use App\Factory\Entity\FactorableEntityInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config implements FactorableEntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $draw_begins_at_hour = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $draw_begins_at_day_no = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $draw_begins_at_concrete_day = null;

    #[ORM\Column]
    private ?bool $draw_is_concrete_day_set = null;

    #[ORM\Column(type: 'bigint')]
    #[Assert\Positive]
    private ?int $lottery_ticket_cost = null;

    #[ORM\Column(type: 'bigint')]
    #[Assert\Positive]
    private ?int $lottery_required_tickets_sum = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\PositiveOrZero]
    private ?float $fee_basic = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDrawBeginsAtHour(): ?\DateTimeImmutable
    {
        return $this->draw_begins_at_hour;
    }

    public function setDrawBeginsAtHour(\DateTimeImmutable $draw_begins_at_hour): self
    {
        $this->draw_begins_at_hour = $draw_begins_at_hour;

        return $this;
    }

    public function getDrawBeginsAtDayNo(): ?int
    {
        return $this->draw_begins_at_day_no;
    }

    public function setDrawBeginsAtDayNo(int $draw_begins_at_day_no): self
    {
        $this->draw_begins_at_day_no = $draw_begins_at_day_no;

        return $this;
    }

    public function getDrawBeginsAtConcreteDay(): ?int
    {
        return $this->draw_begins_at_concrete_day;
    }

    public function setDrawBeginsAtConcreteDay(int $draw_begins_at_concrete_day): self
    {
        $this->draw_begins_at_concrete_day = $draw_begins_at_concrete_day;

        return $this;
    }

    public function getDrawIsConcreteDaySet(): ?bool
    {
        return $this->draw_is_concrete_day_set;
    }

    public function setDrawIsConcreteDaySet(bool $draw_is_concrete_day_set): self
    {
        $this->draw_is_concrete_day_set = $draw_is_concrete_day_set;

        return $this;
    }

    public function getLotteryTicketCost(): ?int
    {
        return $this->lottery_ticket_cost;
    }

    public function setLotteryTicketCost(int $lottery_ticket_cost): self
    {
        $this->lottery_ticket_cost = $lottery_ticket_cost;

        return $this;
    }

    public function getLotteryRequiredTicketsSum(): ?int
    {
        return $this->lottery_required_tickets_sum;
    }

    public function setLotteryRequiredTicketsSum(int $lottery_required_tickets_sum): self
    {
        $this->lottery_required_tickets_sum = $lottery_required_tickets_sum;

        return $this;
    }

    public function getFeeBasic(): ?float
    {
        return $this->fee_basic;
    }

    public function setFeeBasic(float $fee_basic): self
    {
        $this->fee_basic = $fee_basic;

        return $this;
    }
}
