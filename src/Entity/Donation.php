<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Cassandra\Date;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $iduser;

    #[ORM\Column(type: 'integer')]
    private $idinsti;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $amount;

    #[ORM\Column(type: 'string')]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(int $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdinsti(): ?int
    {
        return $this->idinsti;
    }

    public function setIdinsti(int $idinsti): self
    {
        $this->idinsti = $idinsti;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

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
