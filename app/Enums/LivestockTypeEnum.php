<?php

namespace App\Enums;

enum LivestockTypeEnum: string
{
    case SAPI = 'sapi';
    case KERBAU = 'kerbau';
    case DOMBA = 'domba';
    case KAMBING = 'kambing';
}
