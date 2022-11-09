<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Repository\CustomerRepository;
use Faker;

class UserFixture extends Fixture
{

    public function __construct(private CustomerRepository $customerRepository)
    {
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 50; $i++) {
            $getCustomer = $this->customerRepository->find(rand(1, 10));
            $user = new User;
            $firstname = $faker->firstName;
            $lastname = $faker->lastName;
            $user->setFirstname($firstname)
                ->setLastname($lastname)
                ->setEmail($firstname . "." . $lastname . "@gmail.com")
                ->setCustomer($getCustomer);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
