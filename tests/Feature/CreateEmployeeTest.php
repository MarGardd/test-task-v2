<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Тест создания сотрудника с корректными данными
     * @return void
     */
    private $uri = '/api/add-user';
    public function testCreateEmployee(): void
    {
        $employeeData = [
            'email' => 'test@mail.ru',
            'password' => '12345'
        ];
        $response = $this->post($this->uri, $employeeData);

        $response->assertStatus(201)->assertJson([
            'message' => 'Пользователь успешно создан'
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@mail.ru']);
    }

    /**
     * Тест создания сотрудника с некорректными данными
     * @return void
     */
    public function testCreateEmployeeWithInvalidData(): void
    {
        $this->assertRequestValidation($this->uri, ['email' => '', 'password' => '12345'], 'Не указан Email');
        $this->assertRequestValidation($this->uri, ['email' => 'testmailru', 'password' => '12345'], 'Введен неверный Email');
        $this->assertRequestValidation($this->uri, ['email' => 'test@mail.ru', 'password' => ''], 'Не указан пароль');
        User::query()->create(['email' => 'test@mail.ru', 'password' => '12345345']);
        $this->assertRequestValidation($this->uri, ['email' => 'test@mail.ru', 'password' => '12345'], 'Такой Email уже существует');
    }

}
