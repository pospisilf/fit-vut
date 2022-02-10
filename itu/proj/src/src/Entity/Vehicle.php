<?php

namespace App\Entity;
# Author: Filip Pospisil (xpospi0f)
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SPZ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VIN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fuel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transmition;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $odometer;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="vehicles")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=Color::class, inversedBy="vehicles")
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Model::class, inversedBy="vehicles")
     */
    private $model;

    /**
     * @ORM\ManyToOne(targetEntity=Engine::class, inversedBy="vehicles")
     */
    private $engine;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="vehicles")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=FuelRecord::class, mappedBy="vehicle")
     */
    private $fuelRecords;

    /**
     * @ORM\OneToMany(targetEntity=ServiceRecord::class, mappedBy="vehicle")
     */
    private $serviceRecords;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $WheelDrive;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $stk;

    public function __construct()
    {
        $this->fuelRecords = new ArrayCollection();
        $this->serviceRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSPZ(): ?string
    {
        return $this->SPZ;
    }

    public function setSPZ(?string $SPZ): self
    {
        $this->SPZ = $SPZ;

        return $this;
    }

    public function getVIN(): ?string
    {
        return $this->VIN;
    }

    public function setVIN(?string $VIN): self
    {
        $this->VIN = $VIN;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getFuel(): ?string
    {
        return $this->fuel;
    }

    public function setFuel(?string $fuel): self
    {
        $this->fuel = $fuel;

        return $this;
    }

    public function getTransmition(): ?string
    {
        return $this->transmition;
    }

    public function setTransmition(?string $transmition): self
    {
        $this->transmition = $transmition;

        return $this;
    }

    public function getOdometer(): ?string
    {
        return $this->odometer;
    }

    public function setOdometer(?string $odometer): self
    {
        $this->odometer = $odometer;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getEngine(): ?Engine
    {
        return $this->engine;
    }

    public function setEngine(?Engine $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|FuelRecord[]
     */
    public function getFuelRecords(): Collection
    {
        return $this->fuelRecords;
    }

    public function addFuelRecord(FuelRecord $fuelRecord): self
    {
        if (!$this->fuelRecords->contains($fuelRecord)) {
            $this->fuelRecords[] = $fuelRecord;
            $fuelRecord->setVehicle($this);
        }

        return $this;
    }

    public function removeFuelRecord(FuelRecord $fuelRecord): self
    {
        if ($this->fuelRecords->removeElement($fuelRecord)) {
            // set the owning side to null (unless already changed)
            if ($fuelRecord->getVehicle() === $this) {
                $fuelRecord->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ServiceRecord[]
     */
    public function getServiceRecords(): Collection
    {
        return $this->serviceRecords;
    }

    public function addServiceRecord(ServiceRecord $serviceRecord): self
    {
        if (!$this->serviceRecords->contains($serviceRecord)) {
            $this->serviceRecords[] = $serviceRecord;
            $serviceRecord->setVehicle($this);
        }

        return $this;
    }

    public function removeServiceRecord(ServiceRecord $serviceRecord): self
    {
        if ($this->serviceRecords->removeElement($serviceRecord)) {
            // set the owning side to null (unless already changed)
            if ($serviceRecord->getVehicle() === $this) {
                $serviceRecord->setVehicle(null);
            }
        }

        return $this;
    }

    public function getWheelDrive(): ?string
    {
        return $this->WheelDrive;
    }

    public function setWheelDrive(?string $WheelDrive): self
    {
        $this->WheelDrive = $WheelDrive;

        return $this;
    }

    public function getStk(): ?\DateTimeInterface
    {
        return $this->stk;
    }

    public function setStk(?\DateTimeInterface $stk): self
    {
        $this->stk = $stk;

        return $this;
    }
}
