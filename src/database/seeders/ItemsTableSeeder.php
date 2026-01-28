<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\Condition;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'user_id' => 1,
                'image_path' => 'items/Armani+Mens+Clock.jpg',
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => Condition::LikeNew->value,
                'sold' => false,
            ],
            [
                'user_id' => 1,
                'image_path' => 'items/HDD+Hard+Disk.jpg',
                'name' => 'HDD',
                'brand_name' => '西芝',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => Condition::VeryGood->value,
                'sold' => false,
            ],
            [
                'user_id' => 1,
                'image_path' => 'items/iLoveIMG+d.jpg',
                'name' => '玉ねぎ3束',
                'brand_name' => 'なし',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => Condition::Good->value,
                'sold' => false,
            ],
            [
                'user_id' => 1,
                'image_path' => 'items/Leather+Shoes+Product+Photo.jpg',
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'condition' => Condition::Poor->value,
                'sold' => false,
            ],
            [
                'user_id' => 1,
                'image_path' => 'items/Living+Room+Laptop.jpg',
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'condition' => Condition::LikeNew->value,
                'sold' => false,
            ],
            [
                'user_id' => 2,
                'image_path' => 'items/Music+Mic+4632231.jpg',
                'name' => 'マイク',
                'brand_name' => 'なし',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'condition' => Condition::VeryGood->value,
                'sold' => false,
            ],
            [
                'user_id' => 2,
                'image_path' => 'items/Purse+fashion+pocket.jpg',
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => Condition::Good->value,
                'sold' => false,
            ],
            [
                'user_id' => 2,
                'image_path' => 'items/Tumbler+souvenir.jpg',
                'name' => 'タンブラー',
                'brand_name' => 'なし',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'condition' => Condition::Poor->value,
                'sold' => false,
            ],
            [
                'user_id' => 2,
                'image_path' => 'items/Waitress+with+Coffee+Grinder.jpg',
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'condition' => Condition::LikeNew->value,
                'sold' => false,
            ],
            [
                'user_id' => 2,
                'image_path' => 'items/makeup-kit.jpg',
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'condition' => Condition::VeryGood->value,
                'sold' => false,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
