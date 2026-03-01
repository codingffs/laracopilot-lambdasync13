<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $companies = [
            ['name' => 'TechCorp Solutions', 'company' => 'TechCorp Solutions', 'industry' => 'Technology', 'status' => 'Active'],
            ['name' => 'Global Traders Ltd', 'company' => 'Global Traders Ltd', 'industry' => 'Trading', 'status' => 'Active'],
            ['name' => 'Apex Manufacturing', 'company' => 'Apex Manufacturing', 'industry' => 'Manufacturing', 'status' => 'Active'],
            ['name' => 'HealthFirst Clinic', 'company' => 'HealthFirst Clinic', 'industry' => 'Healthcare', 'status' => 'Active'],
            ['name' => 'NovaStar Media', 'company' => 'NovaStar Media', 'industry' => 'Media', 'status' => 'Active'],
            ['name' => 'BlueSky Finance', 'company' => 'BlueSky Finance', 'industry' => 'Finance', 'status' => 'Active'],
            ['name' => 'GreenLeaf Farms', 'company' => 'GreenLeaf Farms', 'industry' => 'Agriculture', 'status' => 'Inactive'],
            ['name' => 'Vertex Logistics', 'company' => 'Vertex Logistics', 'industry' => 'Logistics', 'status' => 'Active'],
            ['name' => 'PrimeDesign Studio', 'company' => 'PrimeDesign Studio', 'industry' => 'Design', 'status' => 'Active'],
            ['name' => 'DataStream Corp', 'company' => 'DataStream Corp', 'industry' => 'Technology', 'status' => 'Churned'],
            ['name' => 'SkyLimit Travel', 'company' => 'SkyLimit Travel', 'industry' => 'Travel', 'status' => 'Active'],
            ['name' => 'IronWorks Construction', 'company' => 'IronWorks Construction', 'industry' => 'Construction', 'status' => 'Active'],
            ['name' => 'SwiftPay Fintech', 'company' => 'SwiftPay Fintech', 'industry' => 'Fintech', 'status' => 'Active'],
            ['name' => 'MindBridge Consulting', 'company' => 'MindBridge Consulting', 'industry' => 'Consulting', 'status' => 'Active'],
            ['name' => 'ClearVision Optics', 'company' => 'ClearVision Optics', 'industry' => 'Healthcare', 'status' => 'Active'],
        ];

        foreach ($companies as $i => $data) {
            Customer::create(array_merge($data, [
                'email'   => 'contact@' . strtolower(str_replace(' ', '', $data['company'])) . '.com',
                'phone'   => '+1-555-' . rand(1000, 9999),
                'website' => 'https://www.' . strtolower(str_replace(' ', '', $data['company'])) . '.com',
                'address' => rand(100, 999) . ' Business Ave, Suite ' . rand(100, 500) . ', New York, NY',
                'source'  => ['Website', 'Referral', 'Cold Call'][array_rand(['Website', 'Referral', 'Cold Call'])],
                'notes'   => 'Key account. Regular check-ins required.',
            ]));
        }
    }
}