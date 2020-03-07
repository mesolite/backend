<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(CatSeeder::class);
        $this->call(LocalSeeder::class);
    }
}
