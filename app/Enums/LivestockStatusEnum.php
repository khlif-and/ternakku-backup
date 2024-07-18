<?php

namespace App\Enums;

enum LivestockStatusEnum: string
{
    case HIDUP = 'hidup';
    case MATI = 'mati';
    case TERJUAL = 'terjual';
}
