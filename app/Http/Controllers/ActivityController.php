<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['creator', 'assignee', 'updates.updater']);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // dd($request->category);
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('assigned_to')) {
            $query->byAssignee($request->assigned_to);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        $activities = $query->orderBy('activity_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        $categories = Category::all();

        return view('activities.index', compact('activities', 'teamMembers', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        $categories = Category::all();

        return view('activities.create', compact('teamMembers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|uuid|exists:categories,category_id',
            'priority' => 'required|in:low,medium,high,critical',
            'activity_date' => 'required|date',
            'estimated_duration' => 'nullable|date_format:H:i',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $activity = Activity::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category,
            'priority' => $request->priority,
            'status' => 'pending',
            'activity_date' => $request->activity_date,
            'estimated_duration' => $request->estimated_duration,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
        ]);

        // Create initial activity update
        ActivityUpdate::create([
            'activity_id' => $activity->id,
            'updated_by' => Auth::id(),
            'previous_status' => null,
            'new_status' => 'pending',
            'remark' => 'Activity created',
            'user_bio_details' => Auth::user()->getBioDetails(),
            'update_time' => now(),
        ]);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load(['creator', 'assignee', 'updates.updater']);
        
        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        return view('activities.show', compact('activity', 'teamMembers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $teamMembers = User::where('is_active', true)
            ->where('role', 'support_team')
            ->orderBy('name')
            ->get();

        $categories = Category::all();

        return view('activities.edit', compact('activity', 'teamMembers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|uuid|exists:categories,category_id',
            'priority' => 'required|in:low,medium,high,critical',
            'activity_date' => 'required|date',
            'estimated_duration' => 'nullable|date_format:H:i',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $activity->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category,
            'priority' => $request->priority,
            'activity_date' => $request->activity_date,
            'estimated_duration' => $request->estimated_duration,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully!');
    }

    /**
     * Update activity status.
     */
    public function updateStatus(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,done,cancelled',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $previousStatus = $activity->status;
        $newStatus = $request->status;

        // Update activity status
        $activity->update(['status' => $newStatus]);

        // Create activity update record
        ActivityUpdate::create([
            'activity_id' => $activity->id,
            'updated_by' => Auth::id(),
            'previous_status' => $previousStatus,
            'new_status' => $newStatus,
            'remark' => $request->remark,
            'user_bio_details' => Auth::user()->getBioDetails(),
            'update_time' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Activity status updated successfully!');
    }

    /**
     * Get activity updates for a specific activity.
     */
    public function getUpdates(Activity $activity)
    {
        $updates = $activity->updates()->with('updater')->get();
        
        return response()->json($updates);
    }
}
