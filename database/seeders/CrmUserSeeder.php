<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CrmUser;
use Illuminate\Support\Facades\Hash;

class CrmUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@crm.com', 'role' => 'Super Admin', 'department' => 'Management'],
            ['name' => 'Admin User', 'email' => 'admin@crm.com', 'role' => 'Admin', 'department' => 'Operations'],
            ['name' => 'Sarah Manager', 'email' => 'manager@crm.com', 'role' => 'Manager', 'department' => 'Sales'],
            ['name' => 'John Executive', 'email' => 'executive@crm.com', 'role' => 'Executive', 'department' => 'Sales'],
            ['name' => 'Alice Johnson', 'email' => 'alice@crm.com', 'role' => 'Executive', 'department' => 'Sales'],
            ['name' => 'Bob Williams', 'email' => 'bob@crm.com', 'role' => 'Executive', 'department' => 'Business Development'],
            ['name' => 'Carol Davis', 'email' => 'carol@crm.com', 'role' => 'Manager', 'department' => 'Enterprise Sales'],
            ['name' => 'David Brown', 'email' => 'david@crm.com', 'role' => 'Executive', 'department' => 'Inside Sales'],
        ];

        foreach ($users as $data) {
            CrmUser::create(array_merge($data, [
                'phone'  => '+1-555-' . rand(1000, 9999),
                'password' => Hash::make('password'),
                'status' => 'active',
            ]));
        }
    }
}