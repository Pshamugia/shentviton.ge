<?php

namespace App\Enums;

enum CartStatus: string
{
    case PENDING = "pending";
    case PAID = "paid";
    case PROCESSED = "processed";
}
