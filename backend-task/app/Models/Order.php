<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// то же самое что и Order
class Order extends Model
{
    protected $table = 'course_user';
    protected $guarded = [];

    public static $statusesRu = [
        0 => 'ожидает оплаты',
        1 => 'оплачен',
        2 => 'ошибка оплаты',
    ];

    public static function getRuStatus(int $status)
    {
        return self::$statusesRu[$status];
    }
}
