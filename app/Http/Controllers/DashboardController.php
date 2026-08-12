<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $activityLogService;

    public function __construct(DashboardService $dashboardService, ActivityLogService $activityLogService)
    {
        $this->dashboardService = $dashboardService;
        $this->activityLogService = $activityLogService;
    }

    public function index()
    {
        $summary = $this->dashboardService->getSummary();
        $lowStockProducts = $this->dashboardService->getLowStockProducts();
        $outOfStockProducts = $this->dashboardService->getOutOfStockProducts();
        $chart = $this->dashboardService->getTransactionsLastDays(14);
        $stockByCategory = $this->dashboardService->getStockByCategory();
        $recentActivities = $this->activityLogService->latest(8);

        $user = auth()->user();

        return view('dashboard.index', compact(
            'summary',
            'lowStockProducts',
            'outOfStockProducts',
            'chart',
            'stockByCategory',
            'recentActivities',
            'user'
        ));
    }
}
