<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Accessory;

class FeeCalculatorService
{
    /**
     * Calculate full fee breakup for a student/guest.
     *
     * @param int $facultyId
     * @param int $months
     * @param array $accessoryIds
     * @return array
     */
    public function calculate($facultyId, $months, array $accessoryIds = [])
    {
        $breakup = [
            'base_fees' => [],
            'one_time_fees' => [],
            'accessories' => [],
            'discount' => null,
            'total' => 0,
        ];

        // Fetch active fees for the faculty
        $fees = Fee::with('feeHead')
            ->where('is_active', 1)
            ->where('from_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('to_date')->orWhere('to_date', '>=', now());
            })
            ->get();

        $total = 0;

        foreach ($fees as $fee) {
            $amount = 0;

            switch ($fee->frequency) {
                case 'monthly':
                    $amount = $fee->amount * $months;
                    break;
                case 'bimonthly':
                    $amount = $fee->amount * ceil($months / 2);
                    break;
                case 'quarterly':
                    $amount = $fee->amount * ceil($months / 3);
                    break;
                case 'halfyearly':
                    $amount = $fee->amount * ceil($months / 6);
                    break;
                case 'yearly':
                    $amount = $fee->amount * ceil($months / 12);
                    break;
                case 'onetime':
                    $amount = $fee->amount;
                    break;
            }

            $item = [
                'name' => $fee->name ?? $fee->feeHead->name,
                'amount' => $amount,
            ];

            if ($fee->frequency === 'onetime') {
                $breakup['one_time_fees'][] = $item;
            } else {
                $breakup['base_fees'][] = $item;
            }

            $total += $amount;
        }

        // Handle Accessories
        if (!empty($accessoryIds)) {
            $accessories = Accessory::whereIn('id', $accessoryIds)->get();

            foreach ($accessories as $acc) {
                $amount = $acc->price * $months; // assume accessory fee is monthly
                $breakup['accessories'][] = [
                    'name' => $acc->accessory_head->name,
                    'amount' => $amount,
                ];
                $total += $amount;
            }
        }

        // Check for discount on longer durations
        $discountApplied = null;
        if ($months >= 6) {
            $discountPercentage = ($months >= 12) ? 8 : 5;
            $discountAmount = round(($total * $discountPercentage) / 100, 2);

            $discountApplied = [
                'type' => $months >= 12 ? 'Yearly Discount' : 'Half-Yearly Discount',
                'percentage' => $discountPercentage,
                'amount' => -$discountAmount,
            ];

            $total -= $discountAmount;
        }

        $breakup['discount'] = $discountApplied;
        $breakup['total'] = $total;

        return $breakup;
    }
}
