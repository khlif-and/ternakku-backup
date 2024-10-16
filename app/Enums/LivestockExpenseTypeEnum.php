<?php

namespace App\Enums;

enum LivestockExpenseTypeEnum: int
{
    case TREATMENT = 1;
    case FEEDING = 2;
    case AI = 3;
    case NI = 4;
    case PREGNANT_CHECK = 5;
}
