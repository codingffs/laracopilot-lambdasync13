<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\CrmUser;
use App\Models\LeadHistory;
use Illuminate\Support\Str;

class LeadSeeder extends Seeder
{
    public function run()
    {
        $users    = CrmUser::all();
        $statuses = ['New', 'Contacted', 'Qualified', 'Lost', 'Converted'];
        $sources  = ['Website', 'Referral', 'Cold Call', 'LinkedIn', 'Trade Show', 'Email Campaign', 'Google Ads', 'Partner'];
        $companies = ['TechCorp Inc', 'Global Solutions', 'Apex Industries', 'NovaSystems', 'BlueSky Tech', 'Vertex Partners', 'StreamLine Co', 'DataPro LLC', 'CloudBase Inc', 'PrimePath Group'];
        $campaigns = ['Q1 Outreach', 'Summer Sale', 'Enterprise Push', 'SMB Drive', 'Digital 2024', null];
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];

        for ($i = 1; $i <= 50; $i++) {
            $first  = ['James', 'Emily', 'Michael', 'Sophia', 'David', 'Olivia', 'Chris', 'Ava', 'Daniel', 'Isabella'];
            $last   = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Martinez', 'Davis', 'Wilson', 'Taylor'];
            $name   = $first[array_rand($first)] . ' ' . $last[array_rand($last)];
            $status = $statuses[array_rand($statuses)];

            $lead = Lead::create([
                'lead_number'     => 'LD-' . strtoupper(Str::random(6)),
                'name'            => $name,
                'email'           => strtolower(str_replace(' ', '.', $name)) . $i . '@example.com',
                'phone'           => '+1-555-' . rand(1000, 9999),
                'company'         => $companies[array_rand($companies)],
                'source'          => $sources[array_rand($sources)],
                'campaign'        => $campaigns[array_rand($campaigns)],
                'status'          => $status,
                'priority'        => $priorities[array_rand($priorities)],
                'assigned_to'     => $users->random()->id,
                'created_by'      => $users->random()->id,
                'estimated_value' => rand(1000, 100000),
                'follow_up_date'  => now()->addDays(rand(-5, 30)),
                'notes'           => 'Initial contact made. Interested in enterprise solution.',
                'tags'            => collect(['hot', 'enterprise', 'sme', 'referral', 'renewal'])->random(2)->implode(','),
            ]);

            LeadHistory::create([
                'lead_id'     => $lead->id,
                'user_id'     => $lead->created_by,
                'action'      => 'created',
                'description' => 'Lead created with status: ' . $status,
            ]);
        }
    }
}