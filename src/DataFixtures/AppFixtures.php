<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $employee = new Employee();
            $employee->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->unique()->email())
                ->setRole($faker->numberBetween(0, 1))
                ->setContractType($faker->randomElement(['CDI', 'CDD']))
                ->setHireDate($faker->dateTimeBetween('-10 years', 'now'))
                ->setActive($faker->boolean(80))
                ->setPassword(password_hash('password123', PASSWORD_BCRYPT));

            $manager->persist($employee);
        }

        $startDate = $faker->dateTimeBetween('-2 years', 'now');
        $deadline = (clone $startDate)->modify('+' . $faker->numberBetween(30, 365) . ' days');

        $project = new Project();
        $project->setName($faker->catchPhrase())
            ->setStartDate($startDate)
            ->setDeadline($deadline)
            ->setArchived($faker->boolean(20));

        $manager->persist($project);

        $manager->flush();
    }
}
