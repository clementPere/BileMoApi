<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer;
            $company = $faker->company;
            $role = ["ROLE_USER"];
            if ($i === 0) {
                $company = "admin";
                $role = ["ROLE_ADMIN"];
            }
            $plaintextPassword = str_replace(" ", "", $company) . "1234";
            $customer->setName($company)
                ->setEmail(str_replace(" ", "", $company) . "@gmail.com")
                ->setPassword($this->passwordHasher->hashPassword($customer, $plaintextPassword))
                ->setRoles($role);
            $manager->persist($customer);
        }
        $manager->flush();
    }
}
