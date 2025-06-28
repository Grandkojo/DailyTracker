<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Category;
class ReportController extends Controller
{
    /**
     * Show the reports page.
     */
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $userId = $request->get('user_id');
        $category = $request->get('category');
        $status = $request->get('status');

        // Build query
        $query = Activity::with(['creator', 'assignee', 'category', 'updates.updater'])
            ->whereBetween('activity_date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $activities = $query->orderBy('activity_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get statistics
        $stats = $this->getReportStats($startDate, $endDate, $userId, $category, $status);

        // Get team members for filter
        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        // Get activity updates for the period
        $activityUpdates = ActivityUpdate::with(['activity', 'updater'])
            ->whereHas('activity', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('activity_date', [$startDate, $endDate]);
            })
            ->orderBy('update_time', 'desc')
            ->get();

        $categories = Category::all();

        return view('reports.index', compact(
            'activities',
            'activityUpdates',
            'stats',
            'teamMembers',
            'startDate',
            'endDate',
            'userId',
            'category',
            'status',
            'categories'
        ));
    }

    /**
     * Get detailed activity report.
     */
    public function activityReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $userId = $request->get('user_id');

        $query = Activity::with(['creator', 'assignee', 'category', 'updates.updater'])
            ->whereBetween('activity_date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        $activities = $query->orderBy('activity_date', 'desc')->get();

        // Group activities by date
        $activitiesByDate = $activities->groupBy('activity_date');

        // Get team members
        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        return view('reports.activity', compact(
            'activities',
            'activitiesByDate',
            'teamMembers',
            'startDate',
            'endDate',
            'userId'
        ));
    }

    /**
     * Get user performance report.
     */
    public function userPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $users = User::where('is_active', true)
            ->where('role', 'support_team')
            ->with(['assignedActivities' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('activity_date', [$startDate, $endDate]);
            }, 'activityUpdates' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('update_time', [$startDate, $endDate]);
            }])
            ->get()
            ->map(function ($user) {
                $totalAssigned = $user->assignedActivities->count();
                $completed = $user->assignedActivities->where('status', 'done')->count();
                $pending = $user->assignedActivities->whereIn('status', ['pending', 'in_progress'])->count();
                $cancelled = $user->assignedActivities->where('status', 'cancelled')->count();
                $updatesCount = $user->activityUpdates->count();

                // Calculate average completion time for completed activities
                $completedActivities = $user->assignedActivities->where('status', 'done');
                $avgCompletionTime = 0;
                if ($completedActivities->count() > 0) {
                    $totalDays = $completedActivities->sum(function ($activity) {
                        $created = Carbon::parse($activity->created_at);
                        $updated = $activity->updated_at;
                        return $created->diffInDays($updated);
                    });
                    $avgCompletionTime = round($totalDays / $completedActivities->count(), 1);
                }

                return [
                    'user' => $user,
                    'total_assigned' => $totalAssigned,
                    'completed' => $completed,
                    'pending' => $pending,
                    'cancelled' => $cancelled,
                    'completion_rate' => $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100, 2) : 0,
                    'updates_count' => $updatesCount,
                    'avg_completion_time' => $avgCompletionTime,
                ];
            });

        // Calculate team insights
        $totalTeamActivities = $users->sum('total_assigned');
        $totalTeamCompleted = $users->sum('completed');
        $totalTeamPending = $users->sum('pending');
        $avgTeamCompletionRate = $users->avg('completion_rate');
        $topPerformer = $users->sortByDesc('completion_rate')->first();
        $mostActive = $users->sortByDesc('total_assigned')->first();

        // Status distribution for pie chart
        $statusDistribution = [
            'Completed' => $totalTeamCompleted,
            'Pending' => $totalTeamPending,
            'Cancelled' => $users->sum('cancelled'),
        ];

        // Department performance
        $departmentPerformance = $users->groupBy('user.department.name')
            ->map(function ($deptUsers) {
                $totalAssigned = $deptUsers->sum('total_assigned');
                $totalCompleted = $deptUsers->sum('completed');
                return [
                    'department' => $deptUsers->first()['user']->department ? $deptUsers->first()['user']->department->name : 'No Department',
                    'total_assigned' => $totalAssigned,
                    'total_completed' => $totalCompleted,
                    'completion_rate' => $totalAssigned > 0 ? round(($totalCompleted / $totalAssigned) * 100, 2) : 0,
                ];
            })
            ->values();

        return view('reports.performance', compact(
            'users', 
            'startDate', 
            'endDate',
            'statusDistribution',
            'departmentPerformance',
            'totalTeamActivities',
            'totalTeamCompleted',
            'totalTeamPending',
            'avgTeamCompletionRate',
            'topPerformer',
            'mostActive'
        ));
    }

    /**
     * Export performance report data.
     */
    public function exportPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $users = User::where('is_active', true)
            ->where('role', 'support_team')
            ->with(['assignedActivities' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('activity_date', [$startDate, $endDate]);
            }, 'activityUpdates' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('update_time', [$startDate, $endDate]);
            }, 'department'])
            ->get()
            ->map(function ($user) {
                $totalAssigned = $user->assignedActivities->count();
                $completed = $user->assignedActivities->where('status', 'done')->count();
                $pending = $user->assignedActivities->whereIn('status', ['pending', 'in_progress'])->count();
                $cancelled = $user->assignedActivities->where('status', 'cancelled')->count();
                $updatesCount = $user->activityUpdates->count();

                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'position' => $user->position,
                    'department' => $user->department ? $user->department->name : 'No Department',
                    'total_assigned' => $totalAssigned,
                    'completed' => $completed,
                    'pending' => $pending,
                    'cancelled' => $cancelled,
                    'completion_rate' => $totalAssigned > 0 ? round(($completed / $totalAssigned) * 100, 2) : 0,
                    'updates_count' => $updatesCount,
                ];
            });

        // Generate CSV content
        $csvContent = "Name,Email,Position,Department,Total Assigned,Completed,Pending,Cancelled,Completion Rate (%),Updates Made\n";
        
        foreach ($users as $userData) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%d,%d,%d,%d,%.2f,%d\n",
                $userData['name'],
                $userData['email'],
                $userData['position'],
                $userData['department'],
                $userData['total_assigned'],
                $userData['completed'],
                $userData['pending'],
                $userData['cancelled'],
                $userData['completion_rate'],
                $userData['updates_count']
            );
        }

        $filename = "performance_report_{$startDate}_to_{$endDate}.csv";

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Export report data.
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $userId = $request->get('user_id');
        $category = $request->get('category');
        $status = $request->get('status');

        $query = Activity::with(['creator', 'assignee', 'category', 'updates.updater'])
            ->whereBetween('activity_date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $activities = $query->orderBy('activity_date', 'desc')->get();

        // Generate CSV content
        $csvContent = "Date,Title,Category,Priority,Status,Assigned To,Created By,Description\n";
        
        foreach ($activities as $activity) {
            $csvContent .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $activity->activity_date,
                $activity->title,
                $activity->category ? $activity->category->name : 'N/A',
                $activity->priority,
                $activity->status,
                $activity->assignee ? $activity->assignee->name : 'Unassigned',
                $activity->creator->name,
                str_replace(',', ';', $activity->description)
            );
        }

        $filename = "activity_report_{$startDate}_to_{$endDate}.csv";

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    /**
     * Get report statistics.
     */
    private function getReportStats($startDate, $endDate, $userId = null, $category = null, $status = null)
    {
        $query = Activity::whereBetween('activity_date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('assigned_to', $userId);
        }

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $totalActivities = $query->count();
        $completedActivities = (clone $query)->where('status', 'done')->count();
        $pendingActivities = (clone $query)->whereIn('status', ['pending', 'in_progress'])->count();
        $cancelledActivities = (clone $query)->where('status', 'cancelled')->count();

        // Category breakdown
        $categoryBreakdown = (clone $query)
            ->select('category_id', DB::raw('count(*) as count'))
            ->groupBy('category_id')
            ->pluck('count', 'category_id')
            ->toArray();

        // Status breakdown
        $statusBreakdown = (clone $query)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Daily activity count
        $dailyActivityCount = (clone $query)
            ->select('activity_date', DB::raw('count(*) as count'))
            ->groupBy('activity_date')
            ->orderBy('activity_date')
            ->pluck('count', 'activity_date')
            ->toArray();

        return [
            'total_activities' => $totalActivities,
            'completed_activities' => $completedActivities,
            'pending_activities' => $pendingActivities,
            'cancelled_activities' => $cancelledActivities,
            'completion_rate' => $totalActivities > 0 ? round(($completedActivities / $totalActivities) * 100, 2) : 0,
            'category_breakdown' => $categoryBreakdown,
            'status_breakdown' => $statusBreakdown,
            'daily_activity_count' => $dailyActivityCount,
        ];
    }
}
