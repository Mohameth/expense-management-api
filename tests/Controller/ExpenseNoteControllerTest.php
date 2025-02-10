<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Controller\ExpenseNoteController;
use App\Entity\Company;
use App\Entity\ExpenseNote;
use App\Entity\User;
use App\Enum\ExpenseType;
use App\Repository\CompanyRepository;
use App\Repository\ExpenseNoteRepository;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityManagerInterface;

final class ExpenseNoteControllerTest extends WebTestCase
{

    public function testCreateExpenseNoteSuccessfully(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/expenses', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'amount'    => 42,
            'type'      => 'essence',
            'date'      => '2019-12-09',
            'companyId' => 2
        ]));

        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
    }
}
