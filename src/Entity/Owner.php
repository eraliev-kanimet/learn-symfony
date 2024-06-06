<?php

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use App\Repository\OwnerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: OwnerRepository::class)]
#[HasLifecycleCallbacks]
class Owner
{
    use HasTimestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private mixed $id;

    #[ORM\Column(type: "string")]
    private mixed $name;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(nullable: false)]
    private mixed $car;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCar(mixed $car): void
    {
        $this->car = $car;
    }

    public function getName(): mixed
    {
        return $this->name;
    }

    public function getCar(): Car
    {
        return $this->car;
    }
}
