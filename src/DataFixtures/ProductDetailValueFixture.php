<?php

namespace App\DataFixtures;

use App\Entity\ProductDetailValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Service\Data;

class ProductDetailValueFixture extends Fixture
{
    public function __construct(private Data $data)
    {
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->data->getData() as $title => $value) {
            switch ($title) {
                case "brand":
                    $this->setValue($value, $title, $manager);
                    break;
                case "operatingSystem":
                    $this->setValue($value, $title, $manager);
                    break;
                case "color":
                    $this->setValue($value, $title, $manager);
                    break;
                case "bluetooth":
                    $this->setValue($value, $title, $manager);
                    break;
                case "network":
                    $this->setValue($value, $title, $manager);
                    break;
                case "usb":
                    $this->setValue($value, $title, $manager);
                    break;
                case "screenSize":
                    $this->setValue($value, $title, $manager);
                    break;
                case "screenResolution":
                    $this->setValue($value, $title, $manager);
                    break;
                case "internalMemory":
                    $this->setValue($value, $title, $manager);
                    break;
                case "ramMemory":
                    $this->setValue($value, $title, $manager);
                    break;
            }
        }
    }



    private function setValue(array $value, string $title, ObjectManager $manager): void
    {
        foreach ($value as $val) {
            $product = new ProductDetailValue;
            $product->setValue($val)
                ->setProductDetailTitle($this->getReference($title));
            $manager->persist($product);
            $manager->flush();
        }
    }
}
