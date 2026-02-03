<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = [
            "コンビニ払い",
            "カード支払い",
        ];

        foreach ($paymentMethods as $paymentMethod) {
            DB::table('payment_methods')->insert([
                'method' => $paymentMethod,
            ]);
        }
    }
}
