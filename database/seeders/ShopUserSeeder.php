<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ShopUserSeeder extends Seeder
{
    public function run()
    {

        User::whereIsAdmin(false)->delete();
        User::factory(5)->create();
    }
}
