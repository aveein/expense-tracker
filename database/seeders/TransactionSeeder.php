<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Transaction::truncate();
        $faker = \Faker\Factory::create();

        foreach (range(1, 50) as $index) {
            DB::table('transactions')->insert([
                'category_id' => Category::inRandomOrder()->first()->id,
                'amount' => rand(500,10000),
                'created_by' => 1,
                'type' => rand(0, 1) ? 'income' : 'expense',
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),

            ]);
        }

    }
}
