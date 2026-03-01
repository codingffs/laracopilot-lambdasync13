<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CrmTask;
use App\Models\CrmUser;
use App\Models\Lead;
use App\Models\Customer;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $users = CrmUser::all();
        $leads = Lead::limit(10)->get();
        $customers = Customer::limit(10)->get();
        $priorities = ['Low', 'Medium', 'High', 'Urgent'];
        $statuses = ['Pending', 'In Progress', 'Completed', 'Cancelled'];
        $titles = [
            'Follow up call with prospect', 'Send product demo video', 'Prepare proposal document',
            'Schedule discovery meeting', 'Review contract terms', 'Send pricing quotation',
            'Update CRM records', 'Conduct needs assessment', 'Arrange site visit',
            'Submit quarterly report', 'Check in with key account', 'Process renewal documentation',
            'Attend trade show briefing', 'Complete customer onboarding', 'Resolve support escalation',
            'Draft partnership agreement', 'Coordinate with technical team', 'Prepare competitive analysis',
            'Send thank you note', 'Confirm meeting attendance',
        ];

        foreach ($titles as $i => $title) {
            $status = $statuses[array_rand($statuses)];
            CrmTask::create([
                'title'        => $title,
                'description'  => 'Action required: ' . $title . '. Ensure timely completion per client SLA.',
                'assigned_to'  => $users->random()->id,
                'created_by'   => $users->random()->id,
                'priority'     => $priorities[array_rand($priorities)],
                'status'       => $status,
                'due_date'     => now()->addDays(rand(-3, 14)),
                'completed_at' => $status === 'Completed' ? now()->subDays(rand(1, 5)) : null,
                'related_type' => $i % 2 === 0 ? 'Lead' : 'Customer',
                'related_id'   => $i % 2 === 0 ? ($leads->random()->id ?? null) : ($customers->random()->id ?? null),
            ]);
        }
    }
}