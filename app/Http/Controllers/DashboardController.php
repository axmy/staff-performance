<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $stats = $this->dashboardService->getStats($year, $month);

        return view('dashboard.index', compact('stats', 'year', 'month'));
    }

    public function export(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $format = $request->get('format', 'excel');

        return $this->dashboardService->export($year, $month, $format);
    }
}
