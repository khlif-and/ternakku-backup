<?php

namespace App\Helpers\Web;

use App\Models\Farm;

class FeedingColonyFormService
{
    public function getDropdownData(Farm $farm): array
    {
        $farm->load('pens');

        return [
            'pens' => $farm->pens,
        ];
    }
}
