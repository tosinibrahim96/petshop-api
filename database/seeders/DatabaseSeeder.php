<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            AdminUserSeeder::class,
            ShopUserSeeder::class,
            UserOrdersSeeder::class
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
