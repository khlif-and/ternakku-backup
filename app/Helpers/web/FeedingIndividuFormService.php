<?php

namespace App\Helpers\Web;

use App\Models\Farm;

class FeedingIndividuFormService
{
    public function getDropdownData(Farm $farm): array
    {
        return [
            'livestocks' => $farm->livestocks()
                ->with(['livestockType:id,name', 'livestockBreed:id,name'])
                ->get()
                ->sortBy(function ($livestock) {
                    return $livestock->eartag_number ?? $livestock->eartag ?? $livestock->id;
                }),
        ];
    }
}
