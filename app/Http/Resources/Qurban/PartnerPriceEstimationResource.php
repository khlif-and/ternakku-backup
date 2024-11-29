<?php

namespace App\Http\Resources\Qurban;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerPriceEstimationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $weight = $request->input('weight');

        $price2025 = $this->price_per_kg;
        $price2024 = $this->previous_price_per_kg;

        $regular_price = $price2025 * $weight;

        $january_price_per_kg = $price2025 * 0.93;
        $january_total_price = $january_price_per_kg * $weight;
        $january_down_payment = $january_total_price * 0.50;
        $january_monthly_payment = ($january_total_price - $january_down_payment) / 5;

        return [
            'package' => new PartnerPriceResource($this),
            'formatted' => [
                'qurban_package' => 'SA-' . $this->order,
                'range_weight' => "{$this->start_weight} - {$this->end_weight}",
                'price_2024_per_kg' => number_format($price2024, 0, ',', '.'),
                'estimated_price_2025_per_kg' => number_format($price2025, 0, ',', '.'),
                'qurban_simulation_2025' => [
                    'selected_weight' => $weight,
                    'regular_qurban_price' => number_format($regular_price, 0, ',', '.'),
                ],
                'agreement_options' => [
                    'january_2025_discount_7_percent' => [
                        'price_per_kg' => number_format($january_price_per_kg, 0, ',', '.'),
                        'total_price' => number_format($january_total_price, 0, ',', '.'),
                        'portion_1_7_price' => number_format($january_total_price / 7, 0, ',', '.'),
                        'down_payment_50_percent' => number_format($january_down_payment, 0, ',', '.'),
                        'portion_1_7_down_payment' => number_format($january_down_payment / 7, 0, ',', '.'),
                        'monthly_payment' => [
                            'february_to_june_approx_5_months' => number_format($january_monthly_payment, 0, ',', '.'),
                            'portion_1_7_per_month' => number_format($january_monthly_payment / 7, 0, ',', '.'),
                            'portion_1_7_per_day' => number_format(($january_monthly_payment / 7) / 30, 0, ',', '.'),
                        ],
                    ],
                ],
            ],
        ];
    }
}
