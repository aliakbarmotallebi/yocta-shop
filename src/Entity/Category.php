<?php

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
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="category")
     */
    private $catgory;

    public function __construct()
    {
        $this->catgory = new ArrayCollection();
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

    public function __toString() {
        return $this->name;
    }

    /**
     * @return Collection|Product[]
     */
    public function getCatgory(): Collection
    {
        return $this->catgory;
    }

    public function addCatgory(Product $catgory): self
    {
        if (!$this->catgory->contains($catgory)) {
            $this->catgory[] = $catgory;
            $catgory->setCategory($this);
        }

        return $this;
    }

    public function removeCatgory(Product $catgory): self
    {
        if ($this->catgory->removeElement($catgory)) {
            // set the owning side to null (unless already changed)
            if ($catgory->getCategory() === $this) {
                $catgory->setCategory(null);
            }
        }

        return $this;
    }
}
