<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ProductDetailValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductDetailValueRepository::class)]
class ProductDetailValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_product'])]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'productDetailValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['get_product'])]
    private ?ProductDetailTitle $productDetailTitle = null;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'productDetailValues')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getProductDetailTitle(): ?ProductDetailTitle
    {
        return $this->productDetailTitle;
    }

    public function setProductDetailTitle(?ProductDetailTitle $productDetailTitle): self
    {
        $this->productDetailTitle = $productDetailTitle;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addProductDetailValue($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeProductDetailValue($this);
        }

        return $this;
    }
}
