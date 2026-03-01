<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deal;
use App\Models\Customer;
use App\Models\CrmUser;

class DealSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::all();
        $users = CrmUser::all();
        $stages = ['Prospecting', 'Qualification', 'Proposal', 'Negotiation', 'Won', 'Lost'];
        $probabilities = ['Prospecting' => 10, 'Qualification' => 25, 'Proposal' => 50, 'Negotiation' => 75, 'Won' => 100, 'Lost' => 0];

        $titles = [
            'Enterprise License Agreement', 'SaaS Platform Subscription', 'Annual Support Contract',
            'Cloud Migration Project', 'Digital Transformation Deal', 'Custom Software Development',
            'Data Analytics Platform', 'Security Audit & Compliance', 'Training & Onboarding Package',
            'Premium Consulting Retainer', 'API Integration Services', 'Infrastructure Upgrade',
            'Mobile App Development', 'CRM Implementation', 'Marketing Automation Setup',
            'Business Intelligence Suite', 'E-commerce Platform', 'ERP System Integration',
            'Managed Services Agreement', 'Performance Optimization Package',
        ];

        foreach ($titles as $i => $title) {
            $stage = $stages[array_rand($stages)];
            Deal::create([
                'title'               => $title,
                'customer_id'         => $customers->random()->id,
                'assigned_to'         => $users->random()->id,
                'stage'               => $stage,
                'value'               => rand(5000, 500000),
                'probability'         => $probabilities[$stage],
                'expected_close_date' => now()->addDays(rand(7, 90)),
                'description'         => 'High-priority deal. Decision maker engaged. Proposal submitted.',
            ]);
        }
    }
}