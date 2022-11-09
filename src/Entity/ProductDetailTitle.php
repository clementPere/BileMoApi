<?php

namespace App\Entity;

use App\Repository\ProductDetailTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductDetailTitleRepository::class)]
class ProductDetailTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;

    #[ORM\OneToMany(mappedBy: 'productDetailTitle', targetEntity: ProductDetailValue::class)]
    private Collection $productDetailValues;

    public function __construct()
    {
        $this->productDetailValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, ProductDetailValue>
     */
    public function getProductDetailValues(): Collection
    {
        return $this->productDetailValues;
    }

    public function addProductDetailValue(ProductDetailValue $productDetailValue): self
    {
        if (!$this->productDetailValues->contains($productDetailValue)) {
            $this->productDetailValues->add($productDetailValue);
            $productDetailValue->setProductDetailTitle($this);
        }

        return $this;
    }

    public function removeProductDetailValue(ProductDetailValue $productDetailValue): self
    {
        if ($this->productDetailValues->removeElement($productDetailValue)) {
            // set the owning side to null (unless already changed)
            if ($productDetailValue->getProductDetailTitle() === $this) {
                $productDetailValue->setProductDetailTitle(null);
            }
        }

        return $this;
    }
}
