<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\ExpenseNote;
use App\Entity\User;
use App\Enum\ExpenseType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $mainUser = new User('bob@outlook.com', 'Bob', 'Smith', new \DateTime('1982-07-01'));
        $mainUser->setPassword($this->passwordHasher->hashPassword($mainUser, 'pass'));
        $manager->persist($mainUser);

        $microsoft = new Company('Microsoft');
        $apple     = new Company('Apple');
        $manager->persist($microsoft);
        $manager->persist($apple);

        $expenseNotes = $this->createExpenseNotes([
            [new \DateTime('2024-10-30'), 99.7, ExpenseType::FUEL, new \DateTime(), $mainUser, $microsoft],
            [new \DateTime('2024-11-12'), 12,   ExpenseType::TOLL, new \DateTime(), $mainUser, $apple],
            [new \DateTime('2025-01-04'), 24.3, ExpenseType::MEAL, new \DateTime(), $mainUser, $apple]
        ]);

        foreach ($expenseNotes as $expenseNote) {
            $manager->persist($expenseNote);
        }

        $manager->flush();
    }

    /**
     * @return Company[]
     */
    public function createCompanies($companyNames): array
    {
        $companies = [];
        foreach ($companyNames as $name) {
            $company = new Company($name);

            $companies[] = $company;
        }

        return $companies;
    }

    /**
     * @return Company[]
     */
    public function createExpenseNotes($expenseNotesData): array
    {
        $expenseNotes = [];
        foreach ($expenseNotesData as $data) {
            $expenseNote = new ExpenseNote();
            $expenseNote
                ->setNoteDate($data[0])
                ->setAmount($data[1])
                ->setType($data[2])
                ->setSubmissionDate($data[3])
                ->setUser($data[4])
                ->setCompany($data[5])
            ;

            $expenseNotes[] = $expenseNote;
        }

        return $expenseNotes;
    }
}
