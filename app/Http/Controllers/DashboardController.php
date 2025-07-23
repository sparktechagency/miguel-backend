<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Song;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            $currentYear = Carbon::now()->year;
            $lastYear = Carbon::now()->subYear()->year;

            // Base counts
            $total_users = User::count();
            $total_song = Song::count();
            $total_artist = Artist::count();
            $total_revenue = Transaction::where('status', 'success')->sum('amount');

            // Month names
            $monthNames = [
                1 => 'January', 2 => 'February', 3 => 'March',
                4 => 'April', 5 => 'May', 6 => 'June',
                7 => 'July', 8 => 'August', 9 => 'September',
                10 => 'October', 11 => 'November', 12 => 'December'
            ];

            // Monthly Revenue This Year
            $monthlyThisYear = Transaction::where('status', 'success')
                ->whereYear('created_at', $currentYear)
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month');

            // Monthly Revenue Last Year
            $monthlyLastYear = Transaction::where('status', 'success')
                ->whereYear('created_at', $lastYear)
                ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('month')
                ->pluck('total', 'month');

            // Prepare data
            $thisYearData = [];
            $lastYearData = [];

            foreach ($monthNames as $month => $name) {
                $thisYearData[] = [
                    'month' => $name,
                    'data' => (float)($monthlyThisYear[$month] ?? 0),
                ];
                $lastYearData[] = [
                    'month' => $name,
                    'data' => (float)($monthlyLastYear[$month] ?? 0),
                ];
            }
            $dashboardData = [
                'total_users'   => $total_users,
                'total_song'    => $total_song,
                'total_artist'  => $total_artist,
                'total_revenue' => $total_revenue,
                'monthly_revenue_this_year' => $thisYearData,
                'monthly_revenue_last_year' => $lastYearData,
            ];

            return $this->sendResponse($dashboardData, 'Dashboard data retrieved successfully.');

        } catch (Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }

}
