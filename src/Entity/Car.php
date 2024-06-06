<?php

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[HasLifecycleCallbacks]
class Car
{
    use HasTimestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private mixed $id;

    #[ORM\Column(type: "string", unique: true)]
    private mixed $number;

    #[ORM\Column(type: "string")]
    private mixed $brand;

    #[ORM\Column(type: "string")]
    private mixed $model;

    #[ORM\OneToMany(targetEntity: Owner::class, mappedBy: 'car')]
    private Collection $owners;

    public function __construct()
    {
        $this->owners = new ArrayCollection();
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function setBrand(string $brand): void
    {
        $this->brand = $brand;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    public function getNumber(): mixed
    {
        return $this->number;
    }

    public function getBrand(): mixed
    {
        return $this->brand;
    }

    public function getModel(): mixed
    {
        return $this->model;
    }

    public function getOwners(): ArrayCollection|Collection
    {
        return $this->owners;
    }
}
