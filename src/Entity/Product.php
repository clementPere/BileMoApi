<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *      "self",
 *       href = @Hateoas\Route(
 *              "api_product_get_item",
 *              parameters = { "id" = "expr(object.getId())"}
 *       ),
 *       exclusion = @Hateoas\Exclusion(groups="get_products")
 * )
 */
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get_products'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get_products'])]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['get_products'])]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: ProductDetailValue::class, inversedBy: 'products')]
    #[Groups(['get_product'])]
    private Collection $productDetailValues;

    public function __construct()
    {
        $this->productDetailValues = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
        }

        return $this;
    }

    public function removeProductDetailValue(ProductDetailValue $productDetailValue): self
    {
        $this->productDetailValues->removeElement($productDetailValue);

        return $this;
    }
}
