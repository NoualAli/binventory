<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ["created_by_id" => 1,'name' => 'PC', 'created_at' => now(), "updated_at" => now()],
            ["created_by_id" => 1,'name' => 'Desktop', 'created_at' => now(), "updated_at" => now()],
            ["created_by_id" => 1,'name' => 'Imprimante', 'created_at' => now(), "updated_at" => now()],
            ["created_by_id" => 1,'name' => 'Onduleur', 'created_at' => now(), "updated_at" => now()]
        ]);
    }
}
