<?php

namespace App\Entity;

use App\Repository\CounterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CounterRepository::class)]
class Counter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $daily;

    #[ORM\Column(type: 'integer')]
    private $total;

    #[ORM\Column(type: 'string', length: 255)]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDaily(): ?int
    {
        return $this->daily;
    }

    public function setDaily(int $daily): self
    {
        $this->daily = $daily;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }
}
