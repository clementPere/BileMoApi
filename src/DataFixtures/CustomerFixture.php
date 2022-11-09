<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CustomerFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer;
            $company = $faker->company;
            $customer->setName($company)
                ->setEmail(str_replace(" ", "", $company) . "@gmail.com")
                ->setPassword(str_replace(" ", "", $company) . "1234");
            $manager->persist($customer);
        }
        $manager->flush();
    }
}
