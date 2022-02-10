<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     * @ORM\OneToMany(targetEntity=ServiceOperation::class, mappedBy="category")
     */
    private $serviceoperations;

    public function __construct()
    {
        $this->serviceoperations = new ArrayCollection();
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
     * @return Collection|Serviceoperation[]
     */
    public function getServiceoperations(): Collection
    {
        return $this->serviceoperations;
    }

    public function addServiceoperation(Serviceoperation $serviceoperation): self
    {
        if (!$this->serviceoperations->contains($serviceoperation)) {
            $this->serviceoperations[] = $serviceoperation;
            $serviceoperation->setCategory($this);
        }

        return $this;
    }

    public function removeServiceoperation(Serviceoperation $serviceoperation): self
    {
        if ($this->serviceoperations->removeElement($serviceoperation)) {
            // set the owning side to null (unless already changed)
            if ($serviceoperation->getCategory() === $this) {
                $serviceoperation->setCategory(null);
            }
        }

        return $this;
    }
}
