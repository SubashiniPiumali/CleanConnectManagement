<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\TeamMember;
use App\Models\Request as AdminRequest;

class DashboardController extends Controller
{
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalCategories = Category::count();
        $totalTeamMembers = TeamMember::count();
        $totalRequests = AdminRequest::count();
        $newRequests = AdminRequest::where('status', 'pending')->count();
        $assignedRequests = AdminRequest::where('status', 'assigned')->count();
        $canceledRequests = AdminRequest::where('status', 'canceled')->count();
    
        return view('admin.dashboard', compact(
            'totalCategories',
            'totalTeamMembers',
            'totalRequests',
            'newRequests',
            'assignedRequests',
            'canceledRequests'
        ));
    }
}
