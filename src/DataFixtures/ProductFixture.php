<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Repository\ProductDetailValueRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\Data;

class ProductFixture extends Fixture
{

    public function __construct(private Data $data, private ProductDetailValueRepository $productDetailValueRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $product = new Product;
            $product->setName("mobile" . $i)
                ->setPrice(100 + $i)
                ->setDescription("description mobile" . $i);
            foreach ($this->data->getData() as $title => $value) {
                $randomValue = $this->data->getData()[$title][array_rand($value)];
                $getDetail = $this->productDetailValueRepository->findOneBy(["value" => $randomValue]);
                $product->addProductDetailValue($getDetail);
            }
            $manager->persist($product);
        }
        $manager->flush();
    }
}
