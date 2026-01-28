<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@shopeasy.com'],
            [
                'name' => 'Administrateur',
                'surname' => 'ShopEasy',
                'password' => Hash::make('password'),
                'telephone' => '0140924050',
                'role' => 'admin',
            ]
        );
    }
}
