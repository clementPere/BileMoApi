<?php

namespace App\DataFixtures;

use App\Entity\ProductDetailTitle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\DataMobile;

class ProductDetailTitleFixture extends Fixture
{
    public function __construct(private DataMobile $data)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->data->getDataMobile() as $title => $value) {
            $product = new ProductDetailTitle;
            $product->setValue($title);
            $manager->persist($product);
            $this->addReference($title, $product);
        }
        $manager->flush();
    }
}
