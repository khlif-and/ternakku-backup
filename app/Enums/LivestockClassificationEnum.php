<?php

namespace App\Enums;

enum LivestockClassificationEnum: int
{
    case LAKTASI_BUNTING = 1;
    case LAKTASI_KOSONG = 2;
    case KERING_BUNTING = 3;
    case KERING_KOSONG = 4;
    case DARA_BUNTING = 5;
    case DARA_KOSONG = 6;
    case PEDET_JANTAN = 7;
    case PEDET_BETINA = 8;
    case PEJANTAN = 9;
}
