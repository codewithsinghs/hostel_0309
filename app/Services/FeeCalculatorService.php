<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Accessory;
use App\Models\Faculty;
use Illuminate\Support\Collection;

class FeeCalculatorService
{
    /**
     * Calculate fee breakup
     *
     * @param int $facultyId
     * @param int $months
     * @param array $accessoryIds
     * @return array
     */
    public function calculate(int $facultyId, int $months, array $accessoryIds = []): array
    {
        $faculty = Faculty::findOrFail($facultyId);

        $fees = Fee::with('feeHead')
            ->where('is_active', 1)
            ->whereHas('feeHead', function ($q) use ($faculty) {
                $q->where('university_id', $faculty->university_id);
            })
            ->get();

        $baseFees = [];
        $oneTimeFees = [];
        $total = 0;
        $discountData = null;

        foreach ($fees as $fee) {
            $amount = 0;

            switch ($fee->frequency) {
                case 'monthly':
                    $amount = $fee->amount * $months;
                    break;

                case 'halfyearly':
                    if ($months >= 6) {
                        $amount = $fee->amount;
                        if ($fee->discount_percentage > 0) {
                            $discount = $fee->amount * ($fee->discount_percentage / 100);
                            $discountData = [
                                'type' => 'Half-Yearly Discount',
                                'percentage' => $fee->discount_percentage,
                                'amount' => -$discount,
                            ];
                            $amount -= $discount;
                        }
                    }
                    break;

                case 'yearly':
                    if ($months >= 12) {
                        $amount = $fee->amount;
                        if ($fee->discount_percentage > 0) {
                            $discount = $fee->amount * ($fee->discount_percentage / 100);
                            $discountData = [
                                'type' => 'Yearly Discount',
                                'percentage' => $fee->discount_percentage,
                                'amount' => -$discount,
                            ];
                            $amount -= $discount;
                        }
                    }
                    break;

                case 'onetime':
                    $amount = $fee->amount;
                    $oneTimeFees[] = [
                        'name' => $fee->name,
                        'amount' => $amount,
                    ];
                    break;
            }

            if ($fee->frequency !== 'onetime') {
                $baseFees[] = [
                    'name' => $fee->name,
                    'amount' => $amount,
                ];
            }

            $total += $amount;
        }

        // Add accessories
        $accessories = [];
        if (!empty($accessoryIds)) {
            $selectedAccessories = Accessory::whereIn('id', $accessoryIds)->get();

            foreach ($selectedAccessories as $acc) {
                $accAmount = $acc->price * $months;
                $accessories[] = [
                    'name' => $acc->accessory_head->name ?? $acc->name,
                    'amount' => $accAmount,
                ];
                $total += $accAmount;
            }
        }

        $normalizedBaseFees = collect($baseFees)
            ->groupBy('name')
            ->map(function ($items, $name) {
                return [
                    'name' => $name,
                    'amount' => $items->sum('amount'),
                ];
            })
            ->values();

            // $durationText = match($months) {
            //     1 => "1 Month (Temporary Stay)",
            //     3 => "3 Months (Quarterly Stay)",
            //     6 => "6 Months (Half-Yearly Stay)",
            //     12 => "12 Months (Yearly Stay)",
            //     default => "$months Months Stay"
            // };

            $durationText = match($months) {
                1 => "1 Month",
                2 => "2 Month",
                3 => "3 Months",
                6 => "6 Months",
                12 => "12 Months",
                default => "$months Months Stay"
            };

        return [
            'stay_duration' => $durationText,
            'base_fees' => $normalizedBaseFees,
            'one_time_fees' => $oneTimeFees,
            'accessories' => $accessories,
            'discount' => $discountData,
            'total' => $total,

        ];
    }
}
