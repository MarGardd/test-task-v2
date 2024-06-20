<?php

namespace Tests\Feature;

use App\Http\Controllers\TransactionController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ExecuteTransactionsTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecuteTransactions(): void
    {
        $this->createTestTransactions();

        $response = $this->post('/api/transactions/execute');

        $response->assertStatus(201)->assertJson([
            'message' => "Вся сумма была успешно выплачена!"
        ]);
    }
}
