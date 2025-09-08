<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Seed Fee Heads
        $feeHeads = [
            ['id' => 1, 'name' => 'Hostel Fee', 'university_id' => 1],
            ['id' => 2, 'name' => 'Mess Fee', 'university_id' => 1],
            ['id' => 3, 'name' => 'Gym Fee', 'university_id' => 1],
            ['id' => 4, 'name' => 'Caution Money', 'university_id' => 1],
            ['id' => 5, 'name' => 'Laundry Service', 'university_id' => 1],
        ];

        DB::table('fee_heads')->insertOrIgnore($feeHeads);

        // Seed Fees with frequencies
        $fees = [
            // Hostel Fee - monthly, half-yearly (with discount), yearly (with discount)
            [
                'name' => 'Hostel Fee',
                'fee_head_id'     => 1,
                'amount'          => 3000,
                'frequency'       => 'monthly',
                'discount_percentage'=> 0,
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'name' => 'Hostel Fee',
                'fee_head_id'     => 1,
                'amount'          => 17000,
                'frequency'       => 'halfyearly',
                'discount_percentage'=> 5, // 5% discount
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'name' => 'Hostel Fee',
                'fee_head_id'     => 1,
                'amount'          => 33000,
                'frequency'       => 'yearly',
                'discount_percentage'=> 8, // 8% discount
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Mess Fee - monthly only
            [
                'name' => 'Mess Fee',
                'fee_head_id'     => 2,
                'amount'          => 1500,
                'frequency'       => 'monthly',
                'discount_percentage'=> 0,
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Gym Fee - optional monthly
            [
                'name' => 'Gym Fee',
                'fee_head_id'     => 3,
                'amount'          => 500,
                'frequency'       => 'monthly',
                'discount_percentage'=> 0,
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Caution Money - one time
            [
                'name' => 'Caution Money',
                'fee_head_id'     => 4,
                'amount'          => 2000,
                'frequency'       => 'onetime',
                'discount_percentage'=> 0,
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],

            // Laundry - quarterly
            [
                'name' => 'Laundry Service',
                'fee_head_id'     => 5,
                'amount'          => 1200,
                'frequency'       => 'quarterly',
                'discount_percentage'=> 0,
                'from_date'       => $now,
                'to_date'         => null,
                'is_active'       => 1,
                'created_by'      => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('fees')->insert($fees);
    }
}
