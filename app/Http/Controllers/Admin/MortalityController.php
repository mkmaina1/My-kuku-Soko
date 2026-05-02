<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MortalityReport;
use App\Models\TransportMortality;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MortalityController extends Controller
{
    /**
     * Display Transport Mortality Overview
     */
    public function transportMortality()
    {
        // Get transport mortality statistics
        $stats = [
            'total_cases' => TransportMortality::count(),
            'today_cases' => TransportMortality::whereDate('created_at', Carbon::today())->count(),
            'weekly_cases' => TransportMortality::whereBetween('created_at',
                [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()])->count(),
            'monthly_cases' => TransportMortality::whereBetween('created_at',
                [Carbon::today()->startOfMonth(), Carbon::today()->endOfMonth()])->count(),
            'mortality_rate' => $this->calculateMortalityRate(),
        ];

        // Get recent transport mortality cases
        $transportCases = TransportMortality::with(['order', 'agent'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get mortality by transport type
        $byTransportType = TransportMortality::select('transport_type', DB::raw('COUNT(*) as count'))
            ->groupBy('transport_type')
            ->orderBy('count', 'desc')
            ->get();

        // Get mortality by agent
        $byAgent = User::where('role', 'agent')
            ->withCount(['transportMortalityCases'])
            ->having('transport_mortality_cases_count', '>', 0)
            ->orderBy('transport_mortality_cases_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.mortality.transport', compact(
            'stats',
            'transportCases',
            'byTransportType',
            'byAgent'
        ));
    }

    /**
     * Display Expectation Flagged Cases
     */
    public function expectationFlagged()
    {
        // Get expectation flagged statistics
        $stats = [
            'total_flagged' => Order::where('mortality_expectation_flag', true)->count(),
            'high_risk' => Order::where('mortality_risk_level', 'high')->count(),
            'medium_risk' => Order::where('mortality_risk_level', 'medium')->count(),
            'low_risk' => Order::where('mortality_risk_level', 'low')->count(),
            'resolved_cases' => Order::where('mortality_expectation_flag', true)
                ->where('mortality_resolved', true)
                ->count(),
        ];

        // Get flagged orders
        $flaggedOrders = Order::where('mortality_expectation_flag', true)
            ->with(['user', 'agent'])
            ->orderBy('mortality_risk_level', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get risk level distribution
        $riskDistribution = Order::where('mortality_expectation_flag', true)
            ->select('mortality_risk_level', DB::raw('COUNT(*) as count'))
            ->groupBy('mortality_risk_level')
            ->get();

        // Get common risk factors
        $riskFactors = $this->getCommonRiskFactors();

        return view('admin.mortality.expectation', compact(
            'stats',
            'flaggedOrders',
            'riskDistribution',
            'riskFactors'
        ));
    }

    /**
     * Display Reports & Complaints
     */
    public function reportsComplaints()
    {
        // Get reports statistics
        $stats = [
            'total_reports' => MortalityReport::count(),
            'open_reports' => MortalityReport::where('status', 'open')->count(),
            'investigating' => MortalityReport::where('status', 'investigating')->count(),
            'resolved' => MortalityReport::where('status', 'resolved')->count(),
            'urgent' => MortalityReport::where('priority', 'urgent')->count(),
        ];

        // Get all reports
        $reports = MortalityReport::with(['order', 'user', 'agent'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get report types distribution
        $reportTypes = MortalityReport::select('report_type', DB::raw('COUNT(*) as count'))
            ->groupBy('report_type')
            ->orderBy('count', 'desc')
            ->get();

        // Get reports by status
        $byStatus = MortalityReport::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.mortality.reports', compact(
            'stats',
            'reports',
            'reportTypes',
            'byStatus'
        ));
    }

    /**
     * Calculate overall mortality rate
     */
    private function calculateMortalityRate()
    {
        $totalOrders = Order::where('status', 'delivered')->count();
        $totalMortality = TransportMortality::sum('quantity');

        if ($totalOrders > 0) {
            // Assuming each order has average 100 items for calculation
            $averageItemsPerOrder = 100;
            $totalItems = $totalOrders * $averageItemsPerOrder;

            return $totalItems > 0 ? round(($totalMortality / $totalItems) * 100, 2) : 0;
        }

        return 0;
    }

    /**
     * Get common risk factors for flagged orders
     */
    private function getCommonRiskFactors()
    {
        return [
            'Long Distance Transport' => 45,
            'Extreme Weather Conditions' => 30,
            'Poor Vehicle Conditions' => 15,
            'Overcrowding' => 10,
            'Unskilled Handler' => 8,
            'Late Night Transport' => 7,
            'Poor Loading/Unloading' => 5,
        ];
    }
}
