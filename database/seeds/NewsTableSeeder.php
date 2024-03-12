<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $news = [
            [
                'text' => 'My wife and I became parents of beatiful daughter Nicole!',
                'date' => Carbon::createFromDate(2017, 3, 23, 'America/Chicago'),
            ],
            [
                'text' => 'My gourgeous wife had 25th Birthday. Love you, Booji!!!',
                'date' => Carbon::createFromDate(2017, 5, 21, 'America/Chicago'),
            ],
        ];

        foreach ($news as $newsItem) {
            DB::table('news')->insert([
                'text' => $newsItem['text'],
                'created_at' => $newsItem['date'],
                'updated_at' => $newsItem['date'],
            ]);
        }
    }
}
