<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::Create([
            'name'=>'Luis Mayta',
            'email'=>'luis.mayta@gmail.com',
            'password' => Hash::make('41887192')
        ])->assignRole('sudo');
        User::Create([
            'name'=>'Vannya Cristobal',
            'email'=>'vannya@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Secretary');
        User::Create([
            'name'=>'Carlos Ponce',
            'email'=>'carlos@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Administrator');
        User::Create([
            'name'=>'Amparito Huaman',
            'email'=>'amparito@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Secretary');
        User::Create([
            'name'=>'Azucena Calderon',
            'email'=>'azucena@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Secretary');
        User::Create([
            'name'=>'Teacher',
            'email'=>'teacher@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Teacher');
        User::Create([
            'name'=>'Student',
            'email'=>'student@gmail.com',
            'password' => Hash::make('123456789')
        ])->assignRole('Student');




    }
}
