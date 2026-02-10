<?php

namespace App\Helpers\Web;

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
