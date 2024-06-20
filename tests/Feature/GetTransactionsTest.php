<?php

namespace Tests\Feature;

use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetTransactionsTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetTransactions(): void
    {

        $users = $this->createTestTransactions();

        $response = $this->get('/api/transactions');

        $response->assertStatus(200)->assertJsonFragment([
            $users[0]->employee->id => 5 * TransactionController::$hourlyRate,
            $users[1]->employee->id => 11 * TransactionController::$hourlyRate
        ]);

    }
}
