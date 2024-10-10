<?php

namespace App\Enums;

enum ReproductionCycleStatusEnum: int
{
    case INSEMINATION = 1;            // Proses inseminasi sedang berlangsung
    case INSEMINATION_FAILED = 2;      // Inseminasi gagal
    case PREGNANT = 3;                 // Sedang hamil
    case GAVE_BIRTH = 4;               // Berhasil melahirkan
    case BIRTH_FAILED = 5;             // Gagal melahirkan
    case WEANING = 6;                  // Proses penyapihan (berhenti menyusui anaknya)
}
