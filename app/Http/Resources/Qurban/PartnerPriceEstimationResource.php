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

        $february_price_per_kg = $price2025 * 0.94;
        $february_total_price = $february_price_per_kg * $weight;
        $february_down_payment = $february_total_price * 0.60;
        $february_monthly_payment = ($february_total_price - $february_down_payment) / 4;

        return [
            'package' => [
                'id' => $this->id,
                'farm_id' => $this->farm_id,
                'order' => $this->order,
                'name' => $this->name,
                'start_weight' => $this->start_weight,
                'end_weight' => $this->end_weight,
                'price_per_kg' => $this->price_per_kg,
                'previous_price_per_kg' => $this->previous_price_per_kg,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
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
                    'february_2025_discount_6_percent' => [
                        'price_per_kg' => number_format($february_price_per_kg, 0, ',', '.'),
                        'total_price' => number_format($february_total_price, 0, ',', '.'),
                        'portion_1_7_price' => number_format($february_total_price / 7, 0, ',', '.'),
                        'down_payment_60_percent' => number_format($february_down_payment, 0, ',', '.'),
                        'portion_1_7_down_payment' => number_format($february_down_payment / 7, 0, ',', '.'),
                        'monthly_payment' => [
                            'march_to_june_approx_4_months' => number_format($february_monthly_payment, 0, ',', '.'),
                            'portion_1_7_per_month' => number_format($february_monthly_payment / 7, 0, ',', '.'),
                            'portion_1_7_per_day' => number_format(($february_monthly_payment / 7) / 30, 0, ',', '.'),
                        ],
                    ],
                ],
            ],
        ];
    }
}
