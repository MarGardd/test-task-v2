<?php

namespace Tests;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Ramsey\Uuid\Uuid;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Тест валидации для запросов
     * @param $uri
     * @param $data
     * @param $errorMessage
     * @return void
     */
    protected function assertRequestValidation($uri ,$data, $errorMessage)
    {
        $response = $this->post($uri, $data);

        $response->assertStatus(400)->assertJson([
            'error' => [
                'message' => $errorMessage
            ]
        ]);
    }

    /**
     * Создание транзакций (и пользователей) для теста
     * @return array
     */
    protected function createTestTransactions(): array
    {
        $users[] = User::query()->create(['email' => "test@mail.ru", 'password' => '1235']);
        $users[] = User::query()->create(['email' => "test2@mail.ru", 'password' => '1235']);
        Transaction::query()->insert([
            ['id' => Uuid::uuid4()->toString(), 'employee_id' => $users[0]->employee->id, 'hours' => 2],
            ['id' => Uuid::uuid4()->toString(), 'employee_id' => $users[0]->employee->id, 'hours' => 3],
            ['id' => Uuid::uuid4()->toString(), 'employee_id' => $users[1]->employee->id, 'hours' => 5],
            ['id' => Uuid::uuid4()->toString(), 'employee_id' => $users[1]->employee->id, 'hours' => 6],
        ]);
        return $users;
    }
}
