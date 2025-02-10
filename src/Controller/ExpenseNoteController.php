<?php

namespace App\Controller;

use App\Entity\ExpenseNote;
use App\Enum\ExpenseType;
use App\Repository\CompanyRepository;
use App\Repository\ExpenseNoteRepository;
use App\Repository\UserRepository;
use App\Service\JwtService;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/expenses')]
final class ExpenseNoteController extends AbstractController
{
    /**#[Route('/expense-notes', name: 'expense_notes')]
    public function index(): Response
    {
        return $this->render('expense_note/index.html.twig', [
            'controller_name' => 'ExpenseNoteController',
        ]);
    }**/

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Get All expenseNotes for the user with id #1
     */
    #[Route('/', name: 'showAll', methods: ['GET'])]
    public function showAll(ExpenseNoteRepository $expenseRepository): JsonResponse
    {
        return $this->json(
            $expenseRepository->findAll(['user' => 1]),
            200
        );
    }

    /**
     * Get the expenseNote with id in route for the user with id 1
     */
    #[Route('/{id}', name: 'showExpenseNote', methods: ['GET'])]
    public function show(int $id, ExpenseNoteRepository $expenseRepository): JsonResponse
    {
        $expense = $expenseRepository->findOneBy(['id' => $id, 'user' => 1]);

        if (!$expense) {
            return $this->json(['error' => 'Expense note was not found'], 404);
        }

        return $this->json($expense, 200);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, UserRepository $userRepository, CompanyRepository $companyRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['amount'], $data['type'], $data['date'], $data['companyId'])) {
            return $this->json(['error' => 'Some fields are missing: amount, type, date of the note and companyId must be sent'], 400);
        }
        if (!($type = ExpenseType::tryFrom($data['type']))) {
            return $this->json(['error' => 'Invalid type value (try: essence, peage, repas or conference)'], 400);
        }
        if (!($company = $companyRepository->find($data['companyId']))) {
            return $this->json(['error' => 'The company with id '. $data['companyId'] .' was not found'], 404);
        }


        $expense = new ExpenseNote();
        $expense->setUser($userRepository->find(1)); // Or bind whichever user id is sent 
        $expense->setCompany($company);
        $expense->setAmount($data['amount']);
        $expense->setType($type);
        $expense->setNoteDate(new \DateTime($data['date']));
        $expense->setSubmissionDate(new \DateTime());

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        return $this->json($expense, 201);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(int $id, Request $request, ExpenseNoteRepository $expenseRepository, CompanyRepository $companyRepository): JsonResponse
    {
        $expense = $expenseRepository->findOneBy(['id' => $id, 'user' => 1]);

        if (!$expense) {
            return $this->json(['error' => 'Expense note was not found'], 404);
        }

        $data = json_decode($request->getContent(), true);
        
        if (isset($data['amount'])) {
            $expense->setAmount($data['amount']);
        }
        if (isset($data['type'])) {
            if (!($type = ExpenseType::tryFrom($data['type']))) {
                return $this->json(['error' => 'Invalid type value (try: essence, peage, repas or conference)'], 400);
            }
            $expense->setType($type);
        }
        if (isset($data['date'])) {
            $expense->setNoteDate(new \DateTime($data['date']));
        }
        if (isset($data['companyId'])) {
            $expense->setCompany($companyRepository->find($data['companyId']));
        }

        $this->entityManager->flush();

        return $this->json($expense, 200);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id, ExpenseNoteRepository $expenseRepository): JsonResponse
    {
        $expense = $expenseRepository->findOneBy(['id' => $id, 'user' => 1]);

        if (!$expense) {
            return $this->json(['error' => 'Expense note was not found'], 404);
        }

        $this->entityManager->remove($expense);
        $this->entityManager->flush();

        return $this->json(['message' => 'Expense note deleted'], 204);
    }

    #[Route('/protected/test', name: 'test-protected', methods: ['GET'])]
    public function getExpenses(): JsonResponse
    {

        return $this->json(['message' => 'Verification passed!'], 200);
    }
}
