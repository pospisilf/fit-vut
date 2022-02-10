<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Entity;

use App\Repository\ServiceOperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ServiceOperationRepository::class)
 */
class ServiceOperation
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
     * @ORM\Column(type="string", length=1024, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $interval_km;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $interval_time;

    /**
     * @ORM\OneToMany(targetEntity=ServiceRecord::class, mappedBy="operation")
     */
    private $serviceRecords;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="serviceoperations")
     */
    private $category;

    public function __construct()
    {
        $this->serviceRecords = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIntervalKm(): ?int
    {
        return $this->interval_km;
    }

    public function setIntervalKm(?int $interval_km): self
    {
        $this->interval_km = $interval_km;

        return $this;
    }

    public function getIntervalTime(): ?int
    {
        return $this->interval_time;
    }

    public function setIntervalTime(?int $interval_time): self
    {
        $this->interval_time = $interval_time;

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
            $serviceRecord->setOperation($this);
        }

        return $this;
    }

    public function removeServiceRecord(ServiceRecord $serviceRecord): self
    {
        if ($this->serviceRecords->removeElement($serviceRecord)) {
            // set the owning side to null (unless already changed)
            if ($serviceRecord->getOperation() === $this) {
                $serviceRecord->setOperation(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
