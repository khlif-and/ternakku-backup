<?php

namespace App\Helpers\web;

class FeedMedicinePurchaseFormService
{
    public function getDropdownData(): array
    {
        return [
            'purchaseTypes' => [
                'forage' => 'Hijauan (Forage)',
                'concentrate' => 'Konsentrat (Concentrate)',
                'medicine' => 'Obat (Medicine)',
            ],
        ];
    }
}
