<?php

namespace App\DataFixtures;

use App\Entity\ProductDetailTitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\Data;

class ProductDetailTitleFixture extends Fixture
{
    public function __construct(private Data $data)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->data->getData() as $title => $value) {
            $product = new ProductDetailTitle;
            $product->setValue($title);
            $manager->persist($product);
            $this->addReference($title, $product);
        }
        $manager->flush();
    }
}
