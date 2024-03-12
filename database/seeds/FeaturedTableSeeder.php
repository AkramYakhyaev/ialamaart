<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FeaturedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $featured = [
            [
                'current' => 1,
                'artist' => 'Galina Ialama',
            ],
        ];

        foreach ($featured as $featuredItem) {
            DB::table('featured')->insert([
                'current' => $featuredItem['current'],
                'artist' => $featuredItem['artist'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
