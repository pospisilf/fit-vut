<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Entity;

use App\Repository\GasStationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GasStationRepository::class)
 */
class GasStation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Fuelrecord::class, mappedBy="gas_station")
     */
    private $fuelrecords;

    public function __construct()
    {
        $this->fuelrecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Fuelrecord[]
     */
    public function getFuelrecords(): Collection
    {
        return $this->fuelrecords;
    }

    public function addFuelrecord(Fuelrecord $fuelrecord): self
    {
        if (!$this->fuelrecords->contains($fuelrecord)) {
            $this->fuelrecords[] = $fuelrecord;
            $fuelrecord->setGasStation($this);
        }

        return $this;
    }

    public function removeFuelrecord(Fuelrecord $fuelrecord): self
    {
        if ($this->fuelrecords->removeElement($fuelrecord)) {
            // set the owning side to null (unless already changed)
            if ($fuelrecord->getGasStation() === $this) {
                $fuelrecord->setGasStation(null);
            }
        }

        return $this;
    }
}
