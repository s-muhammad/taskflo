<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comments;
use App\Models\Projects;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::count();
        $comments = Comments::where('approved',0)->count();
        $tickets = Ticket::where('parent_id',null)->where('status', 'open')->count();
        $blogs = Blog::latest()->take(5)->get();
        $userChart = $this->userChart();
        return view('admin.index',compact('comments','tickets','users','blogs','userChart'));
    }

    /**
     * تعداد کاربران جدید در ۶ ماه شمسی اخیر
     */
    private function userChart(): array
    {
        $labels = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = $i > 0 ? Jalalian::now()->subMonths($i) : Jalalian::now();
            $firstDay = new Jalalian($month->getYear(), $month->getMonth(), 1);

            $start = $firstDay->toCarbon()->startOfDay();
            $end = (new Jalalian($month->getYear(), $month->getMonth(), $firstDay->getMonthDays()))
                ->toCarbon()->endOfDay();

            $labels[] = $firstDay->format('%B');
            $counts[] = User::whereBetween('created_at', [$start, $end])->count();
        }

        return ['labels' => $labels, 'counts' => $counts];
    }
}
