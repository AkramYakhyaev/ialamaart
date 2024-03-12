<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'link' => 'abstract',
                'name' => 'Abstract',
            ],
            [
                'link' => 'small',
                'name' => 'Art Deco',
            ],
            [
                'link' => 'cubism',
                'name' => 'Cubism',
            ],
            [
                'link' => 'dada',
                'name' => 'Dada',
            ],
            [
                'link' => 'expressionism',
                'name' => 'Expressionism',
            ],
            [
                'link' => 'fine-art',
                'name' => 'Fine Art',
            ],
            [
                'link' => 'illustration',
                'name' => 'Illustration',
            ],
            [
                'link' => 'impressionism',
                'name' => 'Impressionism',
            ],
            [
                'link' => 'modern',
                'name' => 'Modern',
            ],
            [
                'link' => 'pop-art',
                'name' => 'Pop Art',
            ],
            [
                'link' => 'portraiture',
                'name' => 'Portraiture',
            ],
            [
                'link' => 'surrealism',
                'name' => 'Surrealism',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'link' => $category['link'],
                'name' => $category['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
