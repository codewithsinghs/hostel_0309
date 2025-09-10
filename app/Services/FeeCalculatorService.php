<?php

namespace App\Services;

use App\Models\Fee;
use App\Models\Faculty;
use App\Models\Accessory;
use Illuminate\Support\Facades\DB;
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
    // public function calculate(int $facultyId, int $months, array $accessoryIds = []): array
    // {
    //     $faculty = Faculty::findOrFail($facultyId);

    //     Log::info('fee service;', $accessoryIds);

    //     $fees = Fee::with('feeHead')
    //         ->where('is_active', 1)
    //         ->whereHas('feeHead', function ($q) use ($faculty) {
    //             $q->where('university_id', $faculty->university_id);
    //             // ->where('is_default', true);
    //         })
    //         ->get();



    //     $baseFees = [];
    //     $oneTimeFees = [];
    //     $total = 0;
    //     $discountData = null;
    //     Log::info('fees', $fees->toArray());
    //     foreach ($fees as $fee) {
    //         $amount = 0;

    //         switch ($fee->frequency) {
    //             case 'monthly':
    //                 $amount = $fee->amount * $months;
    //                 break;

    //             case 'halfyearly':
    //                 if ($months >= 6) {
    //                     $amount = $fee->amount;
    //                     if ($fee->discount_percentage > 0) {
    //                         $discount = $fee->amount * ($fee->discount_percentage / 100);
    //                         $discountData = [
    //                             'type' => 'Half-Yearly Discount',
    //                             'percentage' => $fee->discount_percentage,
    //                             'amount' => -$discount,
    //                         ];
    //                         $amount -= $discount;
    //                     }
    //                 }
    //                 break;

    //             case 'yearly':
    //                 if ($months >= 12) {
    //                     $amount = $fee->amount;
    //                     if ($fee->discount_percentage > 0) {
    //                         $discount = $fee->amount * ($fee->discount_percentage / 100);
    //                         $discountData = [
    //                             'type' => 'Yearly Discount',
    //                             'percentage' => $fee->discount_percentage,
    //                             'amount' => -$discount,
    //                         ];
    //                         $amount -= $discount;
    //                     }
    //                 }
    //                 break;

    //             case 'onetime':
    //                 $amount = $fee->amount;
    //                 $oneTimeFees[] = [
    //                     'name' => $fee->name,
    //                     'amount' => $amount,
    //                 ];
    //                 break;
    //         }

    //         if ($fee->frequency !== 'onetime') {
    //             $baseFees[] = [
    //                 'name' => $fee->name,
    //                 'amount' => $amount,
    //             ];
    //         }

    //         $total += $amount;
    //     }

    //         // Add accessories
    //     $accessories = [];
    //     Log::info("message from service accesories", $accessories);
    //     if (!empty($accessoryIds)) {
    //         $selectedAccessories = Accessory::whereIn('accessory_head_id', $accessoryIds)->get();
    //         Log::info('selected acc', $selectedAccessories->toArray());
    //         foreach ($selectedAccessories as $acc) {
    //             $accAmount = $acc->price * $months;
    //             $accessories[] = [
    //                 'name' => $acc->accessory_head->name ?? $acc->name,
    //                 'amount' => $accAmount,
    //             ];
    //             $total += $accAmount;
    //         }
    //     }

    //     // Log::info('basefee', collect($baseFees->toArray()) );
    //     $normalizedBaseFees = collect($baseFees)
    //         ->groupBy('name')
    //         ->map(function ($items, $name) {
    //             return [
    //                 'name' => $name,
    //                 'amount' => $items->sum('amount'),
    //             ];
    //         })
    //         ->values();

    //     // $durationText = match($months) {
    //     //     1 => "1 Month (Temporary Stay)",
    //     //     3 => "3 Months (Quarterly Stay)",
    //     //     6 => "6 Months (Half-Yearly Stay)",
    //     //     12 => "12 Months (Yearly Stay)",
    //     //     default => "$months Months Stay"
    //     // };

    //     $durationText = match ($months) {
    //         1 => "1 Month",
    //         2 => "2 Month",
    //         3 => "3 Months",
    //         6 => "6 Months",
    //         12 => "12 Months",
    //         default => "$months Months Stay"
    //     };


    //     return [
    //         'stay_duration' => $durationText,
    //         'base_fees' => $normalizedBaseFees,
    //         'one_time_fees' => $oneTimeFees,
    //         'accessories' => $accessories,
    //         'discount' => $discountData,
    //         'total' => $total,

    //     ];
    // }





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
    //         'discount' => !empty($this->discounts) ? $this->discounts[0] : null,
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
    //     // For bi-monthly, we charge every 2 months
    //     // If duration is less than 2 months, charge for 1 period
    //     if ($months < 2) {
    //         return $fee->amount;
    //     }
        
    //     $periods = floor($months / 2);
    //     // Add partial period if there are remaining months
    //     if ($months % 2 !== 0) {
    //         $periods += 0.5; // Charge half for the remaining month
    //     }
        
    //     return $fee->amount * $periods;
    // }
    
    // private function calculateQuarterly(Fee $fee, int $months): float
    // {
    //     // For quarterly fees, only charge if duration meets minimum requirement
    //     if ($months < 3) {
    //         return 0; // Don't charge quarterly fees for stays less than 3 months
    //     }
        
    //     $periods = floor($months / 3);
    //     // Add partial period if there are remaining months
    //     if ($months % 3 !== 0) {
    //         $periods += ($months % 3) / 3; // Charge proportionally for remaining months
    //     }
        
    //     return $fee->amount * $periods;
    // }
    
    // private function calculateHalfYearly(Fee $fee, int $months): float
    // {
    //     // For half-yearly fees, only apply if duration meets minimum requirement
    //     if ($months < 6) {
    //         return 0; // Don't charge half-yearly fees for stays less than 6 months
    //     }
        
    //     $amount = $fee->amount;
        
    //     // Apply discount if applicable
    //     if ($fee->discount_percentage > 0) {
    //         $discount = $amount * ($fee->discount_percentage / 100);
    //         $amount -= $discount;
            
    //         // Record discount
    //         $this->discounts[] = [
    //             'type' => 'Half-Yearly Discount',
    //             'percentage' => $fee->discount_percentage,
    //             'amount' => -$discount,
    //         ];
    //     }
        
    //     return $amount;
    // }
    
    // private function calculateYearly(Fee $fee, int $months): float
    // {
    //     // For yearly fees, only apply if duration meets minimum requirement
    //     if ($months < 12) {
    //         return 0; // Don't charge yearly fees for stays less than 12 months
    //     }
        
    //     $amount = $fee->amount;
        
    //     // Apply discount if applicable
    //     if ($fee->discount_percentage > 0) {
    //         $discount = $amount * ($fee->discount_percentage / 100);
    //         $amount -= $discount;
            
    //         // Record discount
    //         $this->discounts[] = [
    //             'type' => 'Yearly Discount',
    //             'percentage' => $fee->discount_percentage,
    //             'amount' => -$discount,
    //         ];
    //     }
        
    //     return $amount;
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






    // private $discounts = [];
    // private $periodConfig = [
    //     'monthly' => ['min_months' => 1, 'has_discount' => false],
    //     'bimonthly' => ['min_months' => 1, 'has_discount' => false],
    //     'quarterly' => ['min_months' => 3, 'has_discount' => false],
    //     'halfyearly' => ['min_months' => 6, 'has_discount' => true, 'discount_type' => 'Half-Yearly'],
    //     'yearly' => ['min_months' => 12, 'has_discount' => true, 'discount_type' => 'Yearly'],
    // ];

    // public function calculate(int $facultyId, int $months, array $accessoryIds = []): array
    // {
    //     $faculty = Faculty::findOrFail($facultyId);
        
    //     // $fees = Fee::with('feeHead')
    //     //     ->where('is_active', 1)
    //     //     ->whereHas('feeHead', function ($q) use ($faculty) {
    //     //         $q->where('university_id', $faculty->university_id);
    //     //     })
    //     //     ->get();
    
    //          // Only select the fields we actually need
    //     $fees = Fee::with(['feeHead' => function ($query) {
    //             $query->select('id', 'name', 'university_id'); // Only select needed fields from feeHead
    //         }])
    //         ->select('id', 'name', 'amount', 'frequency', 'discount_percentage', 'fee_head_id', 'is_active')
    //         ->where('is_active', 1)
    //         ->whereHas('feeHead', function ($q) use ($faculty) {
    //             $q->where('university_id', $faculty->university_id);
    //         })
    //         ->get();

    //     $baseFees = [];
    //     $oneTimeFees = [];
    //     $total = 0;
        
    //     // Reset discounts for each calculation
    //     $this->discounts = [];
        
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
    //         'discount' => !empty($this->discounts) ? $this->discounts[0] : null,
    //         'total' => $total,
    //     ];
    // }
    
    // private function calculateFeeAmount(Fee $fee, int $months): float
    // {
    //     if ($fee->frequency === 'onetime') {
    //         return $fee->amount;
    //     }
        
    //     if (!isset($this->periodConfig[$fee->frequency])) {
    //         return 0;
    //     }
        
    //     $config = $this->periodConfig[$fee->frequency];
        
    //     // Check if duration meets minimum requirement
    //     if ($months < $config['min_months']) {
    //         return 0;
    //     }
        
    //     // Calculate based on frequency
    //     switch ($fee->frequency) {
    //         case 'monthly':
    //             return $fee->amount * $months;
                
    //         case 'bimonthly':
    //             $periods = floor($months / 2);
    //             if ($months % 2 !== 0) {
    //                 $periods += 0.5; // Charge half for the remaining month
    //             }
    //             return $fee->amount * $periods;
                
    //         case 'quarterly':
    //             $periods = floor($months / 3);
    //             if ($months % 3 !== 0) {
    //                 $periods += ($months % 3) / 3; // Charge proportionally for remaining months
    //             }
    //             return $fee->amount * $periods;
                
    //         case 'halfyearly':
    //         case 'yearly':
    //             $amount = $fee->amount;
                
    //             // Apply discount if applicable
    //             if ($config['has_discount'] && $fee->discount_percentage > 0) {
    //                 $discount = $amount * ($fee->discount_percentage / 100);
    //                 $amount -= $discount;
                    
    //                 // Record discount
    //                 $this->discounts[] = [
    //                     'type' => $config['discount_type'] . ' Discount',
    //                     'percentage' => $fee->discount_percentage,
    //                     'amount' => -$discount,
    //                 ];
    //             }
                
    //             return $amount;
                
    //         default:
    //             return 0;
    //     }
    // }
    
    // private function calculateAccessories(array $accessoryIds, int $months): array
    // {
    //     $accessories = [];
        
    //     if (!empty($accessoryIds)) {
    //         $selectedAccessories = Accessory::whereIn('accessory_head_id', $accessoryIds)->get();
            
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
    //     $texts = [
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
    //         12 => "12 Months"
    //     ];
        
    //     return $texts[$months] ?? "$months Months";
    // }




  
    private $discounts = [];
    private $periodConfig = [
        'monthly' => ['min_months' => 1, 'has_discount' => false],
        'bimonthly' => ['min_months' => 1, 'has_discount' => false],
        'quarterly' => ['min_months' => 3, 'has_discount' => false],
        'halfyearly' => ['min_months' => 6, 'has_discount' => true, 'discount_type' => 'Half-Yearly'],
        'yearly' => ['min_months' => 12, 'has_discount' => true, 'discount_type' => 'Yearly'],
    ];

    public function calculate(int $facultyId, int $months, array $accessoryIds = []): array
    {
        $faculty = Faculty::findOrFail($facultyId);
        
        // Only select the fields we actually need
        $fees = Fee::with(['feeHead' => function ($query) {
                $query->select('id', 'name', 'university_id'); // Only select needed fields from feeHead
            }])
            ->select('id', 'name', 'amount', 'frequency', 'discount_percentage', 'fee_head_id', 'is_active')
            ->where('is_active', 1)
            ->whereHas('feeHead', function ($q) use ($faculty) {
                $q->where('university_id', $faculty->university_id);
            })
            ->get();

        $baseFees = [];
        $oneTimeFees = [];
        $total = 0;
        
        // Reset discounts for each calculation
        $this->discounts = [];
        
        foreach ($fees as $fee) {
            $amount = $this->calculateFeeAmount($fee, $months);
            
            if ($fee->frequency === 'onetime') {
                $oneTimeFees[] = [
                    'name' => $fee->name,
                    'amount' => $amount,
                ];
            } else if ($amount > 0) {
                $baseFees[] = [
                    'name' => $fee->name,
                    'amount' => $amount,
                ];
            }
            
            $total += $amount;
        }
        
        // Add accessories (optimized query)
        $accessories = $this->calculateAccessories($accessoryIds, $months);
        $total += collect($accessories)->sum('amount');
        
        // Normalize base fees by grouping and summing amounts
        $normalizedBaseFees = collect($baseFees)
            ->groupBy('name')
            ->map(function ($items, $name) {
                return [
                    'name' => $name,
                    'amount' => $items->sum('amount'),
                ];
            })
            ->values();
            
        $durationText = $this->getDurationText($months);
        
        return [
            'stay_duration' => $durationText,
            'base_fees' => $normalizedBaseFees,
            'one_time_fees' => $oneTimeFees,
            'accessories' => $accessories,
            'discount' => !empty($this->discounts) ? $this->discounts[0] : null,
            'total' => $total,
        ];
    }
    
    private function calculateFeeAmount(Fee $fee, int $months): float
    {
        if ($fee->frequency === 'onetime') {
            return $fee->amount;
        }
        
        if (!isset($this->periodConfig[$fee->frequency])) {
            return 0;
        }
        
        $config = $this->periodConfig[$fee->frequency];
        
        // Check if duration meets minimum requirement
        if ($months < $config['min_months']) {
            return 0;
        }
        
        // Calculate based on frequency
        switch ($fee->frequency) {
            case 'monthly':
                return $fee->amount * $months;
                
            case 'bimonthly':
                $periods = floor($months / 2);
                if ($months % 2 !== 0) {
                    $periods += 0.5; // Charge half for the remaining month
                }
                return $fee->amount * $periods;
                
            case 'quarterly':
                $periods = floor($months / 3);
                if ($months % 3 !== 0) {
                    $periods += ($months % 3) / 3; // Charge proportionally for remaining months
                }
                return $fee->amount * $periods;
                
            case 'halfyearly':
            case 'yearly':
                $amount = $fee->amount;
                
                // Apply discount if applicable
                if ($config['has_discount'] && $fee->discount_percentage > 0) {
                    $discount = $amount * ($fee->discount_percentage / 100);
                    $amount -= $discount;
                    
                    // Record discount
                    $this->discounts[] = [
                        'type' => $config['discount_type'] . ' Discount',
                        'percentage' => $fee->discount_percentage,
                        'amount' => -$discount,
                    ];
                }
                
                return $amount;
                
            default:
                return 0;
        }
    }
    
    private function calculateAccessories(array $accessoryIds, int $months): array
    {
        $accessories = [];
        
        if (!empty($accessoryIds)) {
            // Only select the fields we need
            $selectedAccessories = Accessory::with(['accessoryHead' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->select('id', 'name', 'price', 'accessories_heads_id')
                ->whereIn('accessories_heads_id', $accessoryIds)
                ->get();
            
            foreach ($selectedAccessories as $acc) {
                $accAmount = $acc->price * $months;
                $accessories[] = [
                    'name' => $acc->accessory_head->name ?? $acc->name,
                    'amount' => $accAmount,
                ];
            }
        }
        
        return $accessories;
    }
    
    private function getDurationText(int $months): string
    {
        $texts = [
            1 => "1 Month",
            2 => "2 Months", 
            3 => "3 Months",
            4 => "4 Months",
            5 => "5 Months",
            6 => "6 Months",
            7 => "7 Months",
            8 => "8 Months", 
            9 => "9 Months",
            10 => "10 Months",
            11 => "11 Months",
            12 => "12 Months"
        ];
        
        return $texts[$months] ?? "$months Months";
    }







}





