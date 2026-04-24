<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Application data fixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $employees = [];
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
            $employees[] = $employee;
        }

        $startDate = $faker->dateTimeBetween('-2 years', 'now');
        $deadline = (clone $startDate)->modify('+'.$faker->numberBetween(30, 365).' days');

        $project = new Project();
        $project->setName($faker->catchPhrase())
            ->setStartDate($startDate)
            ->setDeadline($deadline)
            ->setArchived($faker->boolean(20));

        $manager->persist($project);

        // Default project with statuses, tasks and employee
        $defaultProject = new Project();
        $defaultProject->setName('Refonte site web')
            ->setStartDate(new \DateTime('2026-01-15'))
            ->setDeadline(new \DateTime('2026-06-30'))
            ->setArchived(false);

        $statusTodo = new Status();
        $statusTodo->setLabel('To do');
        $defaultProject->addStatus($statusTodo);

        $statusInProgress = new Status();
        $statusInProgress->setLabel('Doing');
        $defaultProject->addStatus($statusInProgress);

        $statusDone = new Status();
        $statusDone->setLabel('Done');
        $defaultProject->addStatus($statusDone);

        $defaultEmployee = $employees[0];
        $defaultProject->addEmployee($defaultEmployee);

        $manager->persist($defaultProject);

        $task1 = new Task();
        $task1->setTitle('Créer les maquettes')
            ->setDescription('Réaliser les maquettes des pages principales du site')
            ->setDeadline(new \DateTime('2026-03-01'))
            ->setProject($defaultProject)
            ->setStatus($statusDone)
            ->setEmployee($defaultEmployee);
        $manager->persist($task1);

        $task2 = new Task();
        $task2->setTitle('Développer la page d\'accueil')
            ->setDescription('Intégrer la maquette de la page d\'accueil en HTML/CSS')
            ->setDeadline(new \DateTime('2026-04-15'))
            ->setProject($defaultProject)
            ->setStatus($statusInProgress)
            ->setEmployee($defaultEmployee);
        $manager->persist($task2);

        $task3 = new Task();
        $task3->setTitle('Mettre en place l\'API')
            ->setDescription('Développer les endpoints REST pour le backend')
            ->setDeadline(new \DateTime('2026-05-30'))
            ->setProject($defaultProject)
            ->setStatus($statusTodo)
            ->setEmployee($defaultEmployee);
        $manager->persist($task3);

        $manager->flush();
    }
}
