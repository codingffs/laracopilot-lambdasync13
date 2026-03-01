<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\CrmTask;
use App\Models\CrmUser;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('crm_logged_in')) {
            return redirect()->route('admin.login');
        }

        $role = session('crm_user_role');
        $userId = session('crm_user_id');

        // KPI Metrics
        $totalLeads      = Lead::count();
        $newLeads        = Lead::where('status', 'New')->count();
        $qualifiedLeads  = Lead::where('status', 'Qualified')->count();
        $convertedLeads  = Lead::where('status', 'Converted')->count();
        $conversionRate  = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;

        $totalCustomers  = Customer::count();
        $totalDeals      = Deal::count();
        $wonDeals        = Deal::where('stage', 'Won')->count();
        $totalRevenue    = Deal::where('stage', 'Won')->sum('value');
        $pipelineValue   = Deal::whereNotIn('stage', ['Won', 'Lost'])->sum('value');

        $pendingTasks    = CrmTask::where('status', 'Pending')->count();
        $overdueTasks    = CrmTask::where('status', 'Pending')->where('due_date', '<', now())->count();
        $todayTasks      = CrmTask::whereDate('due_date', today())->where('status', 'Pending')->count();

        // Lead status distribution
        $leadsByStatus = Lead::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // Monthly revenue (last 6 months)
        $monthlyRevenue = Deal::where('stage', 'Won')
            ->where('updated_at', '>=', now()->subMonths(6))
            ->select(DB::raw('MONTH(updated_at) as month'), DB::raw('SUM(value) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Deal stages
        $dealsByStage = Deal::select('stage', DB::raw('count(*) as total'), DB::raw('SUM(value) as total_value'))
            ->groupBy('stage')
            ->get();

        // Recent activity
        $recentActivity = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Upcoming tasks
        $upcomingTasks = CrmTask::with(['assignedTo'])
            ->where('status', 'Pending')
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Top performers
        $topPerformers = CrmUser::withCount(['assignedLeads as converted' => function ($q) {
            $q->where('status', 'Converted');
        }])->orderByDesc('converted')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalLeads', 'newLeads', 'qualifiedLeads', 'convertedLeads', 'conversionRate',
            'totalCustomers', 'totalDeals', 'wonDeals', 'totalRevenue', 'pipelineValue',
            'pendingTasks', 'overdueTasks', 'todayTasks',
            'leadsByStatus', 'monthlyRevenue', 'dealsByStage',
            'recentActivity', 'upcomingTasks', 'topPerformers'
        ));
    }
}