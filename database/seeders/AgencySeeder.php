<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('agencies')->insert([
            ["created_by_id" => 1,'name' => 'La concorde', 'code' => 604, 'created_at' => now(), "updated_at" => now()],
            ["created_by_id" => 1,'name' => 'Birkhadem', 'code' => 633, 'created_at' => now(), "updated_at" => now()],
            ["created_by_id" => 1,'name' => 'El Biar', 'code' => 601, 'created_at' => now(), "updated_at" => now()],
        ]);
    }
}
