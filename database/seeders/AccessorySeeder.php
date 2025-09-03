<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\AccessoryHead;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    public function run()
    {
        $admin = 2;
        $fromDate = Carbon::now();
        $toDate = Carbon::now()->addYear();
        // Create accessory_heads
        // $accessoryHeads = [
        //     ['name' => 'Study Table', 'created_by' => 2 ],
        //     ['name' => 'Chair', 'created_by' => 2 ],
        //     ['name' => 'Almira', 'created_by' => 2 ],
        //     ['name' => 'Locker', 'created_by' => 2 ],
        //     ['name' => 'Welcome Kit', 'created_by' => 2 ],
        //     ['name' => 'Studt Lamp', 'created_by' => 2 ],
        //     ['name' => 'Bookshelf', 'created_by' => 2 ],
        //     ['name' => 'Mattress', 'created_by' => 2 ],
        //     ['name' => 'BedSheet', 'created_by' => 2 ],
        //     ['name' => 'Pillow', 'created_by' => 2 ],
        // ];

        // $headMap = [];

        // foreach ($accessoryHeads as $head) {
        //     $created = AccessoryHead::firstOrCreate(['name' => $head['name']], $head);
        //     $headMap[$created->name] = $created->id;
        // }

        // $accessories = [
        //     ['accessory_head_id' => $headMap['Study Table'], 'price' => 0, 'is_default' => true, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Chair'], 'price' => 500, 'is_default' => false, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Almira'], 'price' => 0, 'is_default' => true, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Locker'], 'price' => 300, 'is_default' => true, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Welcome Kit'], 'price' => 0, 'is_default' => true, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Studt Lamp'], 'price' => 300, 'is_default' => false, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Mattress'], 'price' => 1000, 'is_default' => false, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Bookshelf'], 'price' => 500, 'is_default' => false, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['BedSheet'], 'price' => 500, 'is_default' => false, 'is_active' => true, 'created_by' => 2 ],
        //     ['accessory_head_id' => $headMap['Pillow'], 'price' => 400, 'is_default' => false, 'is_active' => true, 'created_by' => 2 ],

        // ];

        // foreach ($accessories as $item) {
        //     Accessory::where('accessory_head_id', $item['accessory_head_id'])->update(['is_active' => 0]);
        //     Accessory::create(array_merge($item, ['is_active' => 1]));
        // }

        // Create accessory_heads
        $accessoryHeads = [
            ['name' => 'Study Table', 'created_by' => $admin],
            ['name' => 'Chair', 'created_by' => $admin],
            ['name' => 'Almira', 'created_by' => $admin],
            ['name' => 'Locker', 'created_by' => $admin],
            ['name' => 'Welcome Kit', 'created_by' => $admin],
            ['name' => 'Studt Lamp', 'created_by' => $admin],
            ['name' => 'Bookshelf', 'created_by' => $admin],
            ['name' => 'Mattress', 'created_by' => $admin],
            ['name' => 'BedSheet', 'created_by' => $admin],
            ['name' => 'Pillow', 'created_by' => $admin],
        ];

        $headMap = [];

        foreach ($accessoryHeads as $head) {
            $created = AccessoryHead::firstOrCreate(['name' => $head['name']], $head);
            $headMap[$created->name] = $created->id;
        }

        // Create accessories with name included
        $accessories = [
            ['name' => 'Study Table', 'price' => 0, 'is_default' => true],
            ['name' => 'Chair', 'price' => 500, 'is_default' => false],
            ['name' => 'Almira', 'price' => 0, 'is_default' => true],
            ['name' => 'Locker', 'price' => 300, 'is_default' => true],
            ['name' => 'Welcome Kit', 'price' => 0, 'is_default' => true],
            ['name' => 'Studt Lamp', 'price' => 300, 'is_default' => false],
            ['name' => 'Mattress', 'price' => 1000, 'is_default' => false],
            ['name' => 'Bookshelf', 'price' => 500, 'is_default' => false],
            ['name' => 'BedSheet', 'price' => 500, 'is_default' => false],
            ['name' => 'Pillow', 'price' => 400, 'is_default' => false],
        ];

        foreach ($accessories as $item) {
            $headId = $headMap[$item['name']];
            Accessory::where('accessory_head_id', $headId)->update(['is_active' => 0]);

            Accessory::create([
                'accessory_head_id' => $headId,
                'name' => $item['name'],
                'price' => $item['price'],
                'is_default' => $item['is_default'],
                'from_date' => $item['is_default'],
                'is_active' => true,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'created_by' => $admin,
            ]);
        }

        echo "Accessory Head and Accessories created successfully! \n";
    }
}
