<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Faculty;
use App\Models\Accessory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

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

        Log::info('fee service;', $accessoryIds);

        $fees = Fee::with('feeHead')
            ->where('is_active', 1)
            ->whereHas('feeHead', function ($q) use ($faculty) {
                $q->where('university_id', $faculty->university_id);
                // ->where('is_default', true);
            })
            ->get();



        // $baseFees = [];
        // $oneTimeFees = [];
        // $total = 0;
        // $discountData = null;
        // Log::info('fees', $fees->toArray());
        // foreach ($fees as $fee) {
        //     $amount = 0;

        //     switch ($fee->frequency) {
        //         case 'monthly':
        //             $amount = $fee->amount * $months;
        //             break;

        //         case 'halfyearly':
        //             if ($months >= 6) {
        //                 $amount = $fee->amount;
        //                 if ($fee->discount_percentage > 0) {
        //                     $discount = $fee->amount * ($fee->discount_percentage / 100);
        //                     $discountData = [
        //                         'type' => 'Half-Yearly Discount',
        //                         'percentage' => $fee->discount_percentage,
        //                         'amount' => -$discount,
        //                     ];
        //                     $amount -= $discount;
        //                 }
        //             }
        //             break;

        //         case 'yearly':
        //             if ($months >= 12) {
        //                 $amount = $fee->amount;
        //                 if ($fee->discount_percentage > 0) {
        //                     $discount = $fee->amount * ($fee->discount_percentage / 100);
        //                     $discountData = [
        //                         'type' => 'Yearly Discount',
        //                         'percentage' => $fee->discount_percentage,
        //                         'amount' => -$discount,
        //                     ];
        //                     $amount -= $discount;
        //                 }
        //             }
        //             break;

        //         case 'onetime':
        //             $amount = $fee->amount;
        //             $oneTimeFees[] = [
        //                 'name' => $fee->name,
        //                 'amount' => $amount,
        //             ];
        //             break;
        //     }

        //     if ($fee->frequency !== 'onetime') {
        //         $baseFees[] = [
        //             'name' => $fee->name,
        //             'amount' => $amount,
        //         ];
        //     }

        //     $total += $amount;
        // }


         $result = $this->fcalculate($fees, $months);

            // Add accessories
        $accessories = [];
        Log::info("message from service accesories", $accessories);
        if (!empty($accessoryIds)) {
            $selectedAccessories = Accessory::whereIn('accessory_head_id', $accessoryIds)->get();
            Log::info('selected acc', $selectedAccessories->toArray());
            foreach ($selectedAccessories as $acc) {
                $accAmount = $acc->price * $months;
                $accessories[] = [
                    'name' => $acc->accessory_head->name ?? $acc->name,
                    'amount' => $accAmount,
                ];
                $total += $accAmount;
            }
        }

        // Log::info('basefee', collect($baseFees->toArray()) );
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

        $durationText = match ($months) {
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

    // Map frequency keyword => months
    protected array $freqMap = [
        'monthly'    => 1,
        'bimonthly'  => 2,
        'quarterly'  => 3,
        'halfyearly' => 6,
        'yearly'     => 12,
        // 'onetime' will be handled separately (size 0)
    ];

    /**
     * Calculate fee breakup.
     *
     * @param  array|Illuminate\Support\Collection  $fees  Each fee must have:
     *         - frequency (string)
     *         - amount (numeric)
     *         - discount_percentage (numeric)
     *         - name (string)
     *         - is_one_time (bool / int)
     * @param  int $months  requested duration in months (>=1)
     * @return array normalized response with breakdown & total
     */
    public function fcalculate($fees, int $months): array
    {
        $months = max(0, (int)$months);
        $plans = [];
        $oneTime = [];
        foreach ($fees as $f) {
            $freq = $f->frequency ?? ($f['frequency'] ?? null);
            $amt  = (float) ($f->amount ?? ($f['amount'] ?? 0));
            $disc = (float) ($f->discount_percentage ?? ($f['discount_percentage'] ?? 0));
            $name = $f->name ?? ($f['name'] ?? 'Fee');

            $isOneTime = (int) ($f->is_one_time ?? ($f['is_one_time'] ?? 0)) === 1;

            if ($isOneTime) {
                $oneTime[] = [
                    'name' => $name,
                    'amount' => round($amt, 2),
                ];
                continue;
            }

            // Unknown frequency -> skip
            if (!isset($this->freqMap[$freq])) {
                continue;
            }

            $size = (int)$this->freqMap[$freq];
            $plans[] = [
                'name' => $name,
                'frequency' => $freq,
                'size' => $size,
                'amount' => round($amt, 2),
                'discount_pct' => round($disc, 2),
                // precompute cost of a FULL chunk after discount:
                'chunk_cost' => round($amt * (1 - ($disc / 100)), 2),
            ];
        }

        // if no recurring plans, just return one_time totals
        if (count($plans) === 0) {
            $oneTotal = array_sum(array_column($oneTime, 'amount'));
            return [
                'base_fees' => [],
                'partial' => null,
                'one_time_fees' => $oneTime,
                'total' => round($oneTotal, 2),
            ];
        }

        // DP limit: allow overpay up to maxPlan size (so we can consider 'm >= months' solutions)
        $maxSize = max(array_column($plans, 'size'));
        $limit = $months + $maxSize;

        $INF = 1e12;
        $dp = array_fill(0, $limit + 1, $INF);
        $used = array_fill(0, $limit + 1, null); // store plan index used
        $prev = array_fill(0, $limit + 1, null);

        $dp[0] = 0;

        // Build dp using full chunks only (each chunk cost includes full-chunk discount)
        for ($m = 1; $m <= $limit; $m++) {
            foreach ($plans as $i => $p) {
                $s = $p['size'];
                if ($m - $s >= 0) {
                    $cand = $dp[$m - $s] + $p['chunk_cost'];
                    if ($cand < $dp[$m]) {
                        $dp[$m] = round($cand, 2);
                        $used[$m] = $i;
                        $prev[$m] = $m - $s;
                    }
                }
            }
        }

        // 1) Best exact (if exists)
        $bestExactCost = $dp[$months] < $INF ? $dp[$months] : null;

        // 2) Best overpay (m >= months)
        $bestOverpayCost = null;
        $bestOverpayM = null;
        for ($m = $months; $m <= $limit; $m++) {
            if ($dp[$m] < $INF && ($bestOverpayCost === null || $dp[$m] < $bestOverpayCost)) {
                $bestOverpayCost = $dp[$m];
                $bestOverpayM = $m;
            }
        }

        // 3) Best prorate: take a dp base and add one partial chunk prorated
        $bestProrateCost = null;
        $bestProrateDetail = null;
        for ($base = 0; $base < $months; $base++) {
            if ($dp[$base] === $INF) continue;
            $remaining = $months - $base;
            foreach ($plans as $i => $p) {
                $s = $p['size'];
                if ($remaining < $s) {
                    // prorate last chunk: cost = (amount * remaining / size)
                    $prorated = round($p['amount'] * ($remaining / $s), 2);
                    $cand = round($dp[$base] + $prorated, 2);
                    if ($bestProrateCost === null || $cand < $bestProrateCost) {
                        $bestProrateCost = $cand;
                        $bestProrateDetail = [
                            'base_m' => $base,
                            'plan_index' => $i,
                            'partial_months' => $remaining,
                            'partial_amount' => $prorated,
                        ];
                    }
                }
            }
        }

        // Choose the minimum overall strategy among exact, overpay and prorate
        $candidates = [];
        if (!is_null($bestExactCost)) $candidates['exact'] = ['cost' => $bestExactCost, 'm' => $months];
        if (!is_null($bestOverpayCost)) $candidates['overpay'] = ['cost' => $bestOverpayCost, 'm' => $bestOverpayM];
        if (!is_null($bestProrateCost)) $candidates['prorate'] = ['cost' => $bestProrateCost];

        // select minimal
        $bestKey = null; $bestCost = INF;
        foreach ($candidates as $k => $v) {
            if ($v['cost'] < $bestCost) {
                $bestCost = $v['cost']; $bestKey = $k;
            }
        }

        // Reconstruct breakdown
        $baseFees = [];
        $partial = null;
        if ($bestKey === 'exact' || $bestKey === 'overpay') {
            $mTarget = $candidates[$bestKey]['m'];
            $m = $mTarget;
            $counts = [];
            while ($m > 0) {
                $i = $used[$m];
                if ($i === null) { break; } // safety
                $p = $plans[$i];
                $counts[$i] = ($counts[$i] ?? 0) + 1;
                $m = $prev[$m];
            }
            // convert counts to readable baseFees
            foreach ($counts as $i => $qty) {
                $p = $plans[$i];
                $chunk_amt = $p['chunk_cost'] * $qty;
                $discountApplied = ($p['discount_pct'] > 0) ? round(($p['amount'] * $qty) - $chunk_amt, 2) : 0.0;
                $baseFees[] = [
                    'name' => $p['name'],
                    'frequency' => $p['frequency'],
                    'unit_months' => $p['size'],
                    'quantity' => $qty,
                    'amount' => round($chunk_amt, 2),
                    'discount_amount' => round($discountApplied, 2),
                    'unit_amount_before_discount' => round($p['amount'], 2),
                    'discount_pct' => round($p['discount_pct'], 2),
                ];
            }
        } elseif ($bestKey === 'prorate') {
            // reconstruct dp base for base_m
            $detail = $bestProrateDetail;
            $mBase = $detail['base_m'];
            $m = $mBase;
            $counts = [];
            while ($m > 0) {
                $i = $used[$m];
                if ($i === null) break;
                $counts[$i] = ($counts[$i] ?? 0) + 1;
                $m = $prev[$m];
            }
            foreach ($counts as $i => $qty) {
                $p = $plans[$i];
                $chunk_amt = $p['chunk_cost'] * $qty;
                $discountApplied = ($p['discount_pct'] > 0) ? round(($p['amount'] * $qty) - $chunk_amt, 2) : 0.0;
                $baseFees[] = [
                    'name' => $p['name'],
                    'frequency' => $p['frequency'],
                    'unit_months' => $p['size'],
                    'quantity' => $qty,
                    'amount' => round($chunk_amt, 2),
                    'discount_amount' => $discountApplied,
                    'unit_amount_before_discount' => round($p['amount'], 2),
                    'discount_pct' => round($p['discount_pct'], 2),
                ];
            }

            // add partial item
            $p = $plans[$detail['plan_index']];
            $partial = [
                'name' => $p['name'],
                'frequency' => $p['frequency'],
                'unit_months' => $p['size'],
                'months_used' => $detail['partial_months'],
                'amount' => round($detail['partial_amount'], 2),
                'prorated_from' => round($p['amount'], 2),
                // no discount for prorated portion
            ];
        }

        // One-time fees sum
        $oneTotal = array_sum(array_column($oneTime, 'amount'));
        $recurringTotal = round($bestCost ?? 0, 2);
        $grandTotal = round($recurringTotal + $oneTotal, 2);

        return [
            'base_fees' => $baseFees,
            'partial' => $partial,
            'one_time_fees' => $oneTime,
            'recurring_total' => round($recurringTotal, 2),
            'one_time_total' => round($oneTotal, 2),
            'total' => round($grandTotal, 2),
            'strategy' => $bestKey, // "exact", "overpay" or "prorate"
            'months_requested' => $months,
        ];
    }





    // Deep


    // public function calculate(int $facultyId, int $months, array $accessoryIds = []): array
    // {
    //     $faculty = Faculty::findOrFail($facultyId);
        
    //     Log::info('fee service;', $accessoryIds);
        
    //     $fees = Fee::with('feeHead')
    //         ->where('is_active', 1)
    //         ->whereHas('feeHead', function ($q) use ($faculty) {
    //             $q->where('university_id', $faculty->university_id);
    //         })
    //         ->get();

    //     $baseFees = [];
    //     $oneTimeFees = [];
    //     $total = 0;
    //     $discountData = null;
        
    //     Log::info('fees', $fees->toArray());
        
    //     foreach ($fees as $fee) {
    //         $amount = $this->calculateFeeAmount($fee, $months);
            
    //         if ($fee->frequency === 'onetime') {
    //             $oneTimeFees[] = [
    //                 'name' => $fee->name,
    //                 'amount' => $amount,
    //             ];
    //         } else if ($amount > 0) {
    //             $baseFees[] = [
    //                 'name' => $fee->name,
    //                 'amount' => $amount,
    //             ];
    //         }
            
    //         $total += $amount;
    //     }
        
    //     // Add accessories
    //     $accessories = $this->calculateAccessories($accessoryIds, $months);
    //     $total += collect($accessories)->sum('amount');
        
    //     // Normalize base fees by grouping and summing amounts
    //     $normalizedBaseFees = collect($baseFees)
    //         ->groupBy('name')
    //         ->map(function ($items, $name) {
    //             return [
    //                 'name' => $name,
    //                 'amount' => $items->sum('amount'),
    //             ];
    //         })
    //         ->values();
            
    //     $durationText = $this->getDurationText($months);
        
    //     return [
    //         'stay_duration' => $durationText,
    //         'base_fees' => $normalizedBaseFees,
    //         'one_time_fees' => $oneTimeFees,
    //         'accessories' => $accessories,
    //         'discount' => $discountData,
    //         'total' => $total,
    //     ];
    // }
    
    // private function calculateFeeAmount(Fee $fee, int $months): float
    // {
    //     switch ($fee->frequency) {
    //         case 'monthly':
    //             return $this->calculateMonthly($fee, $months);
                
    //         case 'bimonthly':
    //             return $this->calculateBimonthly($fee, $months);
                
    //         case 'quarterly':
    //             return $this->calculateQuarterly($fee, $months);
                
    //         case 'halfyearly':
    //             return $this->calculateHalfYearly($fee, $months);
                
    //         case 'yearly':
    //             return $this->calculateYearly($fee, $months);
                
    //         case 'onetime':
    //             return $this->calculateOneTime($fee);
                
    //         default:
    //             return 0;
    //     }
    // }
    
    // private function calculateMonthly(Fee $fee, int $months): float
    // {
    //     return $fee->amount * $months;
    // }
    
    // private function calculateBimonthly(Fee $fee, int $months): float
    // {
    //     $periods = ceil($months / 2);
    //     return $fee->amount * $periods;
    // }
    
    // private function calculateQuarterly(Fee $fee, int $months): float
    // {
    //     $periods = ceil($months / 3);
    //     return $fee->amount * $periods;
    // }
    
    // private function calculateHalfYearly(Fee $fee, int $months): float
    // {
    //     if ($months < 6) {
    //         return 0;
    //     }
        
    //     return $this->applyDiscount($fee, $fee->amount, 'Half-Yearly Discount');
    // }
    
    // private function calculateYearly(Fee $fee, int $months): float
    // {
    //     if ($months < 12) {
    //         return 0;
    //     }
        
    //     return $this->applyDiscount($fee, $fee->amount, 'Yearly Discount');
    // }
    
    // private function calculateOneTime(Fee $fee): float
    // {
    //     return $fee->amount;
    // }
    
    // private function applyDiscount(Fee $fee, float $amount, string $discountType): float
    // {
    //     if ($fee->discount_percentage <= 0) {
    //         return $amount;
    //     }
        
    //     $discount = $amount * ($fee->discount_percentage / 100);
        
    //     // Store discount data (you might want to refactor this to handle multiple discounts)
    //     $discountData = [
    //         'type' => $discountType,
    //         'percentage' => $fee->discount_percentage,
    //         'amount' => -$discount,
    //     ];
        
    //     return $amount - $discount;
    // }
    
    // private function calculateAccessories(array $accessoryIds, int $months): array
    // {
    //     $accessories = [];
        
    //     if (!empty($accessoryIds)) {
    //         $selectedAccessories = Accessory::whereIn('accessory_head_id', $accessoryIds)->get();
            
    //         Log::info('selected acc', $selectedAccessories->toArray());
            
    //         foreach ($selectedAccessories as $acc) {
    //             $accAmount = $acc->price * $months;
    //             $accessories[] = [
    //                 'name' => $acc->accessory_head->name ?? $acc->name,
    //                 'amount' => $accAmount,
    //             ];
    //         }
    //     }
        
    //     return $accessories;
    // }
    
    // private function getDurationText(int $months): string
    // {
    //     return match ($months) {
    //         1 => "1 Month",
    //         2 => "2 Months",
    //         3 => "3 Months",
    //         6 => "6 Months",
    //         12 => "12 Months",
    //         default => "$months Months"
    //     };
    // }













   
    // private $discounts = [];

    // public function calculate(int $facultyId, int $months, array $accessoryIds = []): array
    // {
    //     $faculty = Faculty::findOrFail($facultyId);
        
    //     Log::info('fee service;', $accessoryIds);
        
    //     $fees = Fee::with('feeHead')
    //         ->where('is_active', 1)
    //         ->whereHas('feeHead', function ($q) use ($faculty) {
    //             $q->where('university_id', $faculty->university_id);
    //         })
    //         ->get();

    //     $baseFees = [];
    //     $oneTimeFees = [];
    //     $total = 0;
        
    //     Log::info('fees', $fees->toArray());
        
    //     // Reset discounts for each calculation
    //     $this->discounts = [];
        
    //     foreach ($fees as $fee) {
    //         $amountDetails = $this->calculateFeeAmount($fee, $months);
    //         $amount = $amountDetails['amount'];
            
    //         if ($fee->frequency === 'onetime') {
    //             $oneTimeFees[] = [
    //                 'name' => $fee->name,
    //                 'amount' => $amount,
    //             ];
    //         } else if ($amount > 0) {
    //             $baseFees[] = [
    //                 'name' => $fee->name,
    //                 'amount' => $amount,
    //             ];
    //         }
            
    //         $total += $amount;
    //     }
        
    //     // Add accessories
    //     $accessories = $this->calculateAccessories($accessoryIds, $months);
    //     $total += collect($accessories)->sum('amount');
        
    //     // Normalize base fees by grouping and summing amounts
    //     $normalizedBaseFees = collect($baseFees)
    //         ->groupBy('name')
    //         ->map(function ($items, $name) {
    //             return [
    //                 'name' => $name,
    //                 'amount' => $items->sum('amount'),
    //             ];
    //         })
    //         ->values();
            
    //     $durationText = $this->getDurationText($months);
        
    //     return [
    //         'stay_duration' => $durationText,
    //         'base_fees' => $normalizedBaseFees,
    //         'one_time_fees' => $oneTimeFees,
    //         'accessories' => $accessories,
    //         'discount' => !empty($this->discounts) ? $this->discounts[0] : null,
    //         'total' => $total,
    //     ];
    // }
    
    // private function calculateFeeAmount(Fee $fee, int $months): array
    // {
    //     switch ($fee->frequency) {
    //         case 'monthly':
    //             return [
    //                 'amount' => $this->calculateMonthly($fee, $months),
    //                 'discount_applied' => false
    //             ];
                
    //         case 'bimonthly':
    //             return [
    //                 'amount' => $this->calculateBimonthly($fee, $months),
    //                 'discount_applied' => false
    //             ];
                
    //         case 'quarterly':
    //             return [
    //                 'amount' => $this->calculateQuarterly($fee, $months),
    //                 'discount_applied' => false
    //             ];
                
    //         case 'halfyearly':
    //             return $this->calculatePeriodicWithDiscount($fee, $months, 6, 'Half-Yearly');
                
    //         case 'yearly':
    //             return $this->calculatePeriodicWithDiscount($fee, $months, 12, 'Yearly');
                
    //         case 'onetime':
    //             return [
    //                 'amount' => $this->calculateOneTime($fee),
    //                 'discount_applied' => false
    //             ];
                
    //         default:
    //             return [
    //                 'amount' => 0,
    //                 'discount_applied' => false
    //             ];
    //     }
    // }
    
    // private function calculateMonthly(Fee $fee, int $months): float
    // {
    //     return $fee->amount * $months;
    // }
    
    // private function calculateBimonthly(Fee $fee, int $months): float
    // {
    //     $periods = ceil($months / 2);
    //     return $fee->amount * $periods;
    // }
    
    // private function calculateQuarterly(Fee $fee, int $months): float
    // {
    //     $periods = ceil($months / 3);
    //     return $fee->amount * $periods;
    // }
    
    // private function calculatePeriodicWithDiscount(Fee $fee, int $months, int $periodMonths, string $discountType): array
    // {
    //     $amount = 0;
    //     $discountApplied = false;
    //     $remainingMonths = $months;
        
    //     // Calculate full periods
    //     $fullPeriods = floor($remainingMonths / $periodMonths);
    //     if ($fullPeriods > 0) {
    //         $periodAmount = $fee->amount * $fullPeriods;
            
    //         // Apply discount if applicable
    //         if ($fee->discount_percentage > 0) {
    //             $discount = $periodAmount * ($fee->discount_percentage / 100);
    //             $periodAmount -= $discount;
    //             $discountApplied = true;
                
    //             // Record discount
    //             $this->discounts[] = [
    //                 'type' => $discountType . ' Discount',
    //                 'percentage' => $fee->discount_percentage,
    //                 'amount' => -$discount,
    //             ];
    //         }
            
    //         $amount += $periodAmount;
    //         $remainingMonths -= $fullPeriods * $periodMonths;
    //     }
        
    //     // Calculate remaining months using monthly rate
    //     if ($remainingMonths > 0) {
    //         $monthlyRate = $fee->amount / $periodMonths;
    //         $amount += $monthlyRate * $remainingMonths;
    //     }
        
    //     return [
    //         'amount' => $amount,
    //         'discount_applied' => $discountApplied
    //     ];
    // }
    
    // private function calculateOneTime(Fee $fee): float
    // {
    //     return $fee->amount;
    // }
    
    // private function calculateAccessories(array $accessoryIds, int $months): array
    // {
    //     $accessories = [];
        
    //     if (!empty($accessoryIds)) {
    //         $selectedAccessories = Accessory::whereIn('accessory_head_id', $accessoryIds)->get();
            
    //         Log::info('selected acc', $selectedAccessories->toArray());
            
    //         foreach ($selectedAccessories as $acc) {
    //             $accAmount = $acc->price * $months;
    //             $accessories[] = [
    //                 'name' => $acc->accessory_head->name ?? $acc->name,
    //                 'amount' => $accAmount,
    //             ];
    //         }
    //     }
        
    //     return $accessories;
    // }
    
    // private function getDurationText(int $months): string
    // {
    //     return match ($months) {
    //         1 => "1 Month",
    //         2 => "2 Months",
    //         3 => "3 Months",
    //         4 => "4 Months",
    //         5 => "5 Months",
    //         6 => "6 Months",
    //         7 => "7 Months",
    //         8 => "8 Months",
    //         9 => "9 Months",
    //         10 => "10 Months",
    //         11 => "11 Months",
    //         12 => "12 Months",
    //         default => "$months Months"
    //     };
    // }




   
}



