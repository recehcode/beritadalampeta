<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayCount = Event::whereDate('created_at', Carbon::today())->count();
        $weekCount = Event::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $totalCount = Event::count();

        $categoryCounts = Category::withCount('events')->get();

        return view('admin.dashboard', compact('todayCount', 'weekCount', 'totalCount', 'categoryCounts'));
    }
}
