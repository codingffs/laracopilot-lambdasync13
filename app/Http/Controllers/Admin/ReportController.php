<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Customer;
use App\Models\CrmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function auth()
    {
        if (!session('crm_logged_in')) return redirect()->route('admin.login');
        if (!in_array(session('crm_user_role'), ['Super Admin', 'Admin', 'Manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied.');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->auth()) return $r;
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $wonDeals    = Deal::where('stage', 'Won')->whereBetween('updated_at', [$from, $to])->get();
        $totalRevenue = $wonDeals->sum('value');
        $avgDealSize  = $wonDeals->avg('value');

        $byStage = Deal::select('stage', DB::raw('count(*) as total'), DB::raw('SUM(value) as total_value'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('stage')->get();

        $byUser = Deal::with('assignedTo')
            ->select('assigned_to', DB::raw('count(*) as total'), DB::raw('SUM(value) as total_value'))
            ->where('stage', 'Won')
            ->whereBetween('updated_at', [$from, $to])
            ->groupBy('assigned_to')->get();

        $monthlyTrend = Deal::where('stage', 'Won')
            ->select(DB::raw('DATE_FORMAT(updated_at,\'%Y-%m\') as month'), DB::raw('SUM(value) as revenue'), DB::raw('count(*) as count'))
            ->whereBetween('updated_at', [now()->subMonths(12), now()])
            ->groupBy('month')->orderBy('month')->get();

        return view('admin.reports.sales', compact('wonDeals', 'totalRevenue', 'avgDealSize', 'byStage', 'byUser', 'monthlyTrend', 'from', 'to'));
    }

    public function leads(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $totalLeads    = Lead::whereBetween('created_at', [$from, $to])->count();
        $byStatus      = Lead::select('status', DB::raw('count(*) as total'))->whereBetween('created_at', [$from, $to])->groupBy('status')->pluck('total', 'status');
        $bySource      = Lead::select('source', DB::raw('count(*) as total'))->whereBetween('created_at', [$from, $to])->groupBy('source')->get();
        $byPriority    = Lead::select('priority', DB::raw('count(*) as total'))->whereBetween('created_at', [$from, $to])->groupBy('priority')->pluck('total', 'priority');
        $byAssignee    = Lead::with('assignedTo')->select('assigned_to', DB::raw('count(*) as total'))->whereBetween('created_at', [$from, $to])->groupBy('assigned_to')->get();

        return view('admin.reports.leads', compact('totalLeads', 'byStatus', 'bySource', 'byPriority', 'byAssignee', 'from', 'to'));
    }

    public function conversion(Request $request)
    {
        if ($r = $this->auth()) return $r;

        $from = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $to   = $request->get('to', now()->endOfMonth()->format('Y-m-d'));

        $totalLeads     = Lead::whereBetween('created_at', [$from, $to])->count();
        $convertedLeads = Lead::where('status', 'Converted')->whereBetween('created_at', [$from, $to])->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

        $totalDeals     = Deal::whereBetween('created_at', [$from, $to])->count();
        $wonDeals       = Deal::where('stage', 'Won')->whereBetween('created_at', [$from, $to])->count();
        $dealWinRate    = $totalDeals > 0 ? round(($wonDeals / $totalDeals) * 100, 2) : 0;

        $bySource = Lead::select('source', DB::raw('count(*) as total'), DB::raw('SUM(CASE WHEN status="Converted" THEN 1 ELSE 0 END) as converted'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('source')->get();

        return view('admin.reports.conversion', compact('totalLeads', 'convertedLeads', 'conversionRate', 'totalDeals', 'wonDeals', 'dealWinRate', 'bySource', 'from', 'to'));
    }
}