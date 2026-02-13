<?php

namespace App\Helpers\web;

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
