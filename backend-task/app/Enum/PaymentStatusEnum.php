<?php

namespace App\Enum;

enum PaymentStatusEnum: int
{
    case pending = 0;
    case success = 1;
    case failed = 2;
}
