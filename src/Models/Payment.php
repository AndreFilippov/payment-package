<?php

namespace PaymentModule\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
    ];

    /**
     * @param $data
     *
     * Создание платежа
     *
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function create($data){
        $payment = self::query()->create($data);
        return $payment;
    }

    /**
     * @param $user_id
     * @param $balance
     *
     * Пополнение баланса User
     *
     * @return bool
     */
    public static function setBalance($user_id, $balance){
        $user = User::find($user_id);
        if($user){
            $user->balance = $user->balance + $balance;
            $user->save();
        }
        return true;
    }
}
