<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'CS-Salesman 1', 'type' => 'question', 'order' => 1],
            ['name' => 'CS-Salesman 2', 'type' => 'question', 'order' => 2],
            ['name' => 'Admin STNK', 'type' => 'question', 'order' => 3],
            ['name' => 'Admin Claim KPB', 'type' => 'question', 'order' => 4],
            ['name' => 'User SA UNIT', 'type' => 'question', 'order' => 5],
            ['name' => 'User Kasir UNIT', 'type' => 'question', 'order' => 6],
            ['name' => 'User Kasir Bengkel', 'type' => 'program', 'order' => 7],
            ['name' => 'User SA Bengkel', 'type' => 'program', 'order' => 8],
            ['name' => 'CRM', 'type' => 'question', 'order' => 9],
            ['name' => 'User Admin Part', 'type' => 'program', 'order' => 10],
            ['name' => 'MOSKI', 'type' => 'question', 'order' => 11],
            ['name' => 'Admin Tagihan (B2B Phase 2)', 'type' => 'program', 'order' => 12],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}