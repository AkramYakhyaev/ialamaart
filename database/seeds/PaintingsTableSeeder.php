<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaintingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loremIpsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lobortis massa lobortis, molestie nisl ut, mattis sapien. Suspendisse vitae purus aliquet, venenatis libero a, accumsan ex. Aenean vel risus quis diam volutpat interdum sed ut urna.';
        
        $paintings = [
            [
                'link' => 'test-1',
                'name' => 'Test 1',
                'description' => $loremIpsum,
                'price' => 1,
            ],
            [
                'link' => 'test-2',
                'name' => 'Test 2',
                'description' => $loremIpsum,
                'price' => 300,
            ],
            [
                'link' => 'test-3',
                'name' => 'Test 3',
                'description' => $loremIpsum,
                'price' => 500,
            ],
            [
                'link' => 'test-4',
                'name' => 'Test 4',
                'description' => $loremIpsum,
                'price' => 500,
            ],
            [
                'link' => 'test-5',
                'name' => 'Test 5',
                'description' => $loremIpsum,
                'price' => 1200,
            ],
            [
                'link' => 'test-6',
                'name' => 'Test 6',
                'description' => $loremIpsum,
                'price' => 12000,
            ],
            [
                'link' => 'test-7',
                'name' => 'Test 7',
                'description' => $loremIpsum,
                'price' => 6000,
            ],
            [
                'link' => 'test-8',
                'name' => 'Test 8',
                'description' => $loremIpsum,
                'price' => 3500,
            ],
        ];

        foreach ($paintings as $painting) {
            DB::table('paintings')->insert([
                'link' => $painting['link'],
                'name' => $painting['name'],
                'description' => $painting['description'],
                'price' => $painting['price'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
