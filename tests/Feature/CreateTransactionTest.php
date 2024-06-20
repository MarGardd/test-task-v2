<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    use DatabaseTransactions;
    private $uri = '/api/transactions/create';
    public function testCreateTransaction(): void
    {
        $user = User::query()->create(['email' => 'test@mail.ru', 'password' => '12345']);
        $data = [
            'employee_id' => $user->employee->id,
            'hours' => 5
        ];
        $response = $this->post($this->uri, $data);

        $response->assertStatus(201)->assertJson([
            'message' => "Данные успешно отправлены!"
        ]);
    }

    public function testCreateTransactionWithInvalidData()
    {
        $user = User::query()->create(['email' => 'test@mail.ru', 'password' => '12345']);
        $this->assertRequestValidation($this->uri, ['employee_id' => "", 'hours' => 4], "Id сотрудника не указан" );
        $this->assertRequestValidation($this->uri, ['employee_id' => "12", 'hours' => 4], "Id сотрудника должен быть uuid" );
        $this->assertRequestValidation($this->uri, ['employee_id' => "0e108d10-dac2-4cde-b8df-ad3177946826", 'hours' => 4], "Указанный сотрудник не существует" );
        $this->assertRequestValidation($this->uri, ['employee_id' => $user->employee->id], "Не указано кол-во отработанных часов" );
        $this->assertRequestValidation($this->uri, ['employee_id' => $user->employee->id, 'hours' => 4.5], "Кол-во часов должно быть целым числом" );
        $this->assertRequestValidation($this->uri, ['employee_id' => $user->employee->id, 'hours' => 0], "Кол-во отработанных часов должно быть больше 0" );
    }

}
