<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
{
   User::updateOrCreate(
    ['email' => 'admin@maindealerhonda.com'],
    [
        'name' => 'Admin Main Dealer',
        'password' => Hash::make('Honda@2026!!'),
        'user_type' => 'admin',
    ]
);
}
}