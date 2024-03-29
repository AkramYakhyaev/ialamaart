<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(PaintingsTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(FeaturedTableSeeder::class);
    }
}
