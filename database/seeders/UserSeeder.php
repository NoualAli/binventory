<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = array(
            array(
                "name" => "admin",
                "email" => "admin@admin.com",
                "is_active" => 1,
                "email_verified_at" => null,
                "password" => Hash::make('123456'),
                "remember_token" => null,
                "created_at" => now(),
                "updated_at" => now(),
                "permissions" => "{\"platform.systems.roles\":true,\"platform.systems.users\":true,\"platform.categories\":true,\"platform.agencies\":true,\"platform.equipments\":true,\"platform.systems.attachment\":true,\"platform.index\":true}"
            ),
            array(
                "name" => "Technicien 1",
                "email" => "tech-1@badr.dz",
                "is_active" => 1,
                "email_verified_at" => null,
                "password" => Hash::make('123456'),
                "remember_token" => null,
                "created_at" => now(),
                "updated_at" => now(),
                "permissions" => "{\"platform.agencies\":\"0\",\"platform.categories\":\"0\",\"platform.equipments\":\"0\",\"platform.systems.attachment\":\"0\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":true}"
            ),
            array(
                "name" => "Technicien 2",
                "email" => "tech-2@badr.dz",
                "is_active" => 1,
                "email_verified_at" => null,
                "password" => Hash::make('123456'),
                "remember_token" => null,
                "created_at" => now(),
                "updated_at" => now(),
                "permissions" => "{\"platform.agencies\":\"0\",\"platform.categories\":\"0\",\"platform.equipments\":\"0\",\"platform.systems.attachment\":\"0\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":true}"
            ),
            array(
                "name" => "CDS",
                "email" => "cds@badr.dz",
                "is_active" => 1,
                "email_verified_at" => null,
                "password" => Hash::make('123456'),
                "remember_token" => null,
                "created_at" => now(),
                "updated_at" => now(),
                "permissions" => "{\"platform.agencies\":\"0\",\"platform.categories\":\"0\",\"platform.equipments\":\"0\",\"platform.systems.attachment\":\"0\",\"platform.systems.roles\":\"0\",\"platform.systems.users\":\"0\",\"platform.index\":true}"
            )
        );

        DB::table('users')->insert($users);

        $role_users = array(
            array(
                "user_id" => 1,
                "role_id" => 1
            ),
            array(
                "user_id" => 2,
                "role_id" => 3
            ),
            array(
                "user_id" => 3,
                "role_id" => 3
            ),
            array(
                "user_id" => 4,
                "role_id" => 2
            )
        );


        DB::table('role_users')->insert($role_users);
    }
}