<?php

namespace Database\Seeders;

use App\Models\AccountParent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akunUtama = [
            [
                "id" => 1,
                "name" => "Aset",
                "code" => "1.0.00.00",
            ],
            [
                "id" => 2,
                "name" => "Liabilitas",
                "code" => "2.0.00.00",
            ],
            [
                "id" => 3,
                "name" => "Ekuitas",
                "code" => "3.0.00.00",
            ],
            [
                "id" => 4,
                "name" => "Pendapatan",
                "code" => "4",
            ],
            [
                "id" => 5,
                "name" => "Biaya",
                "code" => "5",
            ],

        ];

        foreach ($akunUtama as $key => $value) {
            AccountParent::create([
                'parent_code' => $value['code'],
                'parent_name' => $value['name'],
            ]);
        }
    }
}
