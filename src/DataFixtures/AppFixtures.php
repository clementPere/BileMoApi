<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            ProductDetailTitleFixture::class,
            ProductDetailValueFixture::class,
            ProductFixture::class,
            CustomerFixture::class,
            UserFixture::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
    }
}
