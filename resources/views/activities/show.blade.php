@extends('layouts.app')

@section('title', $activity->title)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $activity->title }}</h1>
            <p class="text-gray-600">Activity Details</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('activities.edit', $activity) }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <a href="{{ route('activities.index') }}" 
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Activity Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Main Activity Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Activity Information</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Description</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->description }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Category</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $activity->category ? $activity->category->name : 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Priority</h4>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getPriorityColor() }}">
                                {{ ucfirst($activity->priority) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Activity Date</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $activity->activity_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Estimated Duration</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $activity->estimated_duration ? $activity->estimated_duration->format('H:i') : 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Updates -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Activity Updates</h3>
                </div>
                <div class="px-6 py-4">
                    @if($activity->updates->count() > 0)
                        <div class="space-y-4">
                            @foreach($activity->updates as $update)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm text-gray-900">
                                                    <span class="font-medium">{{ $update->getUserName() }}</span>
                                                    {{ $update->getStatusChangeDescription() }}
                                                </p>
                                                <span class="text-xs text-gray-500">{{ $update->getFormattedUpdateTime() }}</span>
                                            </div>
                                            
                                            @if($update->remark)
                                                <p class="text-sm text-gray-600 mt-1">{{ $update->remark }}</p>
                                            @endif
                                            
                                            <!-- User Bio Details -->
                                            <div class="mt-2 text-xs text-gray-500">
                                                <p><strong>Employee ID:</strong> {{ $update->getUserEmployeeId() }}</p>
                                                <p><strong>Department:</strong> {{ $update->getUserDepartment() }}</p>
                                                <p><strong>Position:</strong> {{ $update->user_bio_details['position'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No updates yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Be the first to update this activity.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Status</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $activity->getStatusColor() }}">
                            {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Assignment Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Assignment</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700">Created By</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $activity->creator->name }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->creator->position }} - {{ $activity->creator->department ? $activity->creator->department->name : 'N/A' }}</p>
                    </div>
                    
                    @if($activity->assignee)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Assigned To</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $activity->assignee->name }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->assignee->position }} - {{ $activity->assignee->department ? $activity->assignee->department->name : 'N/A' }}</p>
                        </div>
                    @else
                        <div>
                            <h4 class="text-sm font-medium text-gray-700">Assigned To</h4>
                            <p class="mt-1 text-sm text-gray-500">Unassigned</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Update Status</h3>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('activities.update-status', $activity) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">New Status</label>
                            <select id="status" name="status" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Status</option>
                                <option value="pending" {{ $activity->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $activity->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $activity->status == 'done' ? 'selected' : '' }}>Done</option>
                                <option value="cancelled" {{ $activity->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="remark" class="block text-sm font-medium text-gray-700">Remark (Optional)</label>
                            <textarea id="remark" name="remark" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Add any additional notes..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 