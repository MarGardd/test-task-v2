<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendTransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;


class TransactionController extends Controller
{
    /**
     * Получить суммы выплат по сотрудникам
     *
     * @return JsonResponse
     */
    public static $hourlyRate = 500; //Оплата в час
    public static function getTransactions(): JsonResponse
    {
        $result = Transaction::query()
            ->where('completed', false)
            ->get()
            ->groupBy('employee_id')
            ->transform(function (Collection $item){
                $total_salary = $item->sum('hours') * self::$hourlyRate;
                return $total_salary;
            });

        $result = array_chunk($result->toArray(), 1, true); //Форматирование под формат из ТЗ
        return response()->json($result, 200);
    }

    /**
     * Создание транзакции
     *
     * @param SendTransactionRequest $request
     * @return JsonResponse
     */
    public static function createTransaction(SendTransactionRequest $request): JsonResponse
    {
        $transaction = [
            'employee_id' => $request->employee_id,
            'hours' => $request->hours
        ];
        try {
            Transaction::query()->create($transaction);
            return response()->json(['message' => 'Данные успешно отправлены!'], 201);
        } catch (\Exception $e){
            return response()->json(['message' => 'Ошибка создания транзакции', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Выполнение всех текущих транзакций
     * @return JsonResponse
     */
    public static function executeTransactions(): JsonResponse
    {
        try {
            $currentTransactions = Transaction::query()->where('completed', false)->get();
            foreach ($currentTransactions as $transaction){
                /**
                 * Логика выполнения выплаты
                 */
                Transaction::query()->find($transaction->id)->update(['completed' => true]);
            }
            return response()->json(['message' => 'Вся сумма была успешно выплачена!'], 201);
        } catch (\Exception $e){
            return response()->json(['message' => 'Ошибка при выполнении выплат', 'error' => $e->getMessage()], 500);
        }
    }
}
