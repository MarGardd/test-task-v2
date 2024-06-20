<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SendTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => [
                "required",
                "uuid",
                Rule::exists('employees', 'id')
            ],
            'hours' => "required|integer|gt:0"
        ];
    }

    public function messages()
    {
        return [
            'employee_id.required' => 'Id сотрудника не указан',
            'employee_id.uuid' => 'Id сотрудника должен быть uuid',
            'employee_id.exists' => 'Указанный сотрудник не существует',
            'hours.required' => 'Не указано кол-во отработанных часов',
            'hours.integer' => 'Кол-во часов должно быть целым числом',
            'hours.gt' => 'Кол-во отработанных часов должно быть больше 0'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => ['message' => $validator->errors()->first()]], 400));
    }
}
