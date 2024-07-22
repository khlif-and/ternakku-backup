<?php

namespace App\Enums;

enum LivestockStatusEnum: int
{
    case HIDUP = 1;
    case MATI = 2;
    case TERJUAL = 3;
}
