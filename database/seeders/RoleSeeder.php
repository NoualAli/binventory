<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $roles = array(
	array(
		"id" => 1,
		"slug" => "admin",
		"name" => "admin",
		"permissions" => "{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"1\",\"platform.systems.users\":\"1\",\"platform.categories.edit\":\"1\",\"platform.categories.create\":\"1\",\"platform.categories.delete\":\"1\",\"platform.categories.show\":\"1\",\"platform.agencies.edit\":\"1\",\"platform.agencies.create\":\"1\",\"platform.agencies.delete\":\"1\",\"platform.agencies.show\":\"1\",\"platform.equipments.edit\":\"1\",\"platform.equipments.create\":\"1\",\"platform.equipments.delete\":\"1\",\"platform.equipments.show\":\"1\",\"platform.index\":\"1\"}",
		"created_at" => "2024-10-07 20:57:15",
		"updated_at" => "2024-10-08 19:35:15"
	),
	array(
		"id" => 2,
		"slug" => "chef-de-service",
		"name" => "Chef de service",
		"permissions" => "{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.categories.edit\":\"1\",\"platform.categories.create\":\"1\",\"platform.categories.delete\":\"1\",\"platform.categories.show\":\"1\",\"platform.agencies.edit\":\"1\",\"platform.agencies.create\":\"1\",\"platform.agencies.delete\":\"1\",\"platform.agencies.show\":\"1\",\"platform.equipments.edit\":\"1\",\"platform.equipments.create\":\"1\",\"platform.equipments.delete\":\"1\",\"platform.equipments.show\":\"1\",\"platform.index\":\"1\"}",
		"created_at" => "2024-10-07 20:57:15",
		"updated_at" => "2024-10-08 19:35:07"
	),
	array(
		"id" => 3,
		"slug" => "technicien",
		"name" => "Technicien",
		"permissions" => "{\"platform.systems.attachment\":\"1\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.categories.edit\":\"1\",\"platform.categories.create\":\"1\",\"platform.categories.delete\":\"1\",\"platform.categories.show\":\"1\",\"platform.agencies.edit\":\"1\",\"platform.agencies.create\":\"1\",\"platform.agencies.delete\":\"0\",\"platform.agencies.show\":\"1\",\"platform.equipments.edit\":\"1\",\"platform.equipments.create\":\"1\",\"platform.equipments.delete\":\"1\",\"platform.equipments.show\":\"1\",\"platform.index\":\"1\"}",
		"created_at" => "2024-10-07 20:57:15",
		"updated_at" => "2024-10-08 19:35:00"
	)
);

        DB::table('roles')->insert($roles);
    }
}
