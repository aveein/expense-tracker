<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Category::truncate();
        $categories = ['food', 'grocery', 'salary', 'etc'];

        foreach($categories as $category){
            Category::create([
                'title'=> $category,
                'status'=>1
            ]);
        }


    }
}
