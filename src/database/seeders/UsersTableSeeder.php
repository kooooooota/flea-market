<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Laravel\Fortify\RecoveryCode;
use Illuminate\Support\Str;
use App\Models\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'さんぷる たろう',
                'email' => 'taro@example.com',
                'password' => Hash::make('exampletaro'),
                'two_factor_secret' => encrypt('secret-key-value'),
                'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                    return RecoveryCode::generate();
                }))),
                'two_factor_confirmed_at' => now(),
            ],
            [
                'name' => 'さんぷる じろう',
                'email' => 'jiro@example.com',
                'password' => Hash::make('examplejiro'),
                'two_factor_secret' => encrypt('secret-key-value'),
                'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                    return RecoveryCode::generate();
                })->all())),
                'two_factor_confirmed_at' => now(),
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
