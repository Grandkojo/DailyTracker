<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        
        // Get activities for the selected date
        $activitiesQuery = Activity::with(['creator', 'assignee', 'updates.updater'])
            ->whereDate('activity_date', $selectedDate)
            ->orderBy('created_at', 'desc');
        
        // If user is admin, show all activities. Otherwise, show only assigned activities
        if (!$user->isAdmin()) {
            $activitiesQuery->where('assigned_to', $user->id);
        }
        
        $activities = $activitiesQuery->get();

        // Get pending activities for handover
        $pendingQuery = Activity::with(['creator', 'assignee'])
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('activity_date', '<=', $selectedDate)
            ->orderBy('priority', 'desc')
            ->orderBy('activity_date', 'asc');
        
        // If user is admin, show all pending activities. Otherwise, show only assigned pending activities
        if (!$user->isAdmin()) {
            $pendingQuery->where('assigned_to', $user->id);
        }
        
        $pendingActivities = $pendingQuery->get();

        // Get statistics
        $stats = [
            'total_today' => $activities->count(),
            'pending_today' => $activities->where('status', 'pending')->count(),
            'in_progress_today' => $activities->where('status', 'in_progress')->count(),
            'done_today' => $activities->where('status', 'done')->count(),
            'total_pending' => $pendingActivities->count(),
        ];

        // Get recent updates
        $recentUpdatesQuery = \App\Models\ActivityUpdate::with(['activity', 'updater'])
            ->whereHas('activity', function ($query) use ($selectedDate) {
                $query->whereDate('activity_date', $selectedDate);
            })
            ->orderBy('update_time', 'desc')
            ->limit(10);
        
        
        $recentUpdates = $recentUpdatesQuery->get();

        // Get team members
        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        return view('dashboard', compact(
            'activities',
            'pendingActivities',
            'stats',
            'recentUpdates',
            'teamMembers',
            'selectedDate'
        ));
    }

    /**
     * Get activities for a specific date.
     */
    public function getActivitiesByDate(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $user = Auth::user();
        
        $activitiesQuery = Activity::with(['creator', 'assignee', 'updates.updater'])
            ->whereDate('activity_date', $date)
            ->orderBy('created_at', 'desc');
        
        // If user is admin, show all activities. Otherwise, show only assigned activities
        if (!$user->isAdmin()) {
            $activitiesQuery->where('assigned_to', $user->employee_id);
        }
        
        $activities = $activitiesQuery->get();

        return response()->json($activities);
    }

    /**
     * Get dashboard statistics.
     */
    public function getStats(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfWeek());
        $endDate = $request->get('end_date', now()->endOfWeek());

        $stats = [
            'total_activities' => Activity::whereBetween('activity_date', [$startDate, $endDate])->count(),
            'completed_activities' => Activity::whereBetween('activity_date', [$startDate, $endDate])
                ->where('status', 'done')->count(),
            'pending_activities' => Activity::whereBetween('activity_date', [$startDate, $endDate])
                ->whereIn('status', ['pending', 'in_progress'])->count(),
            'team_members' => User::where('is_active', true)->where('role', 'support_team')->count(),
        ];

        return response()->json($stats);
    }
}
