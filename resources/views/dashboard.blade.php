@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('activities.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Activity
            </a>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-4">
            <label for="date" class="text-sm font-medium text-gray-700">Select Date:</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                   class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" 
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                View
            </button>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Today</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['pending_today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">In Progress</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['in_progress_today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['done_today'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_pending'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Activities -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Today's Activities ({{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }})
                </h3>
                
                @if($activities->count() > 0)
                    <div class="space-y-3">
                        @foreach($activities as $activity)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($activity->description, 100) }}</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getStatusColor() }}">
                                                {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getPriorityColor() }}">
                                                {{ ucfirst($activity->priority) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('activities.show', $activity) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            View
                                        </a>
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No activities</h3>
                        <p class="mt-1 text-sm text-gray-500">No activities scheduled for today.</p>
                        <div class="mt-6">
                            <a href="{{ route('activities.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Create Activity
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Pending Activities for Handover -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    Pending Activities (Handover)
                </h3>
                
                @if($pendingActivities->count() > 0)
                    <div class="space-y-3">
                        @foreach($pendingActivities->take(5) as $activity)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $activity->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($activity->description, 80) }}</p>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getStatusColor() }}">
                                                {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getPriorityColor() }}">
                                                {{ ucfirst($activity->priority) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Due: {{ $activity->activity_date->format('M d, Y') }}
                                            @if($activity->assignee)
                                                • Assigned to: {{ $activity->assignee->name }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('activities.show', $activity) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Update
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($pendingActivities->count() > 5)
                            <div class="text-center pt-4">
                                <a href="{{ route('activities.index', ['status' => 'pending']) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View all {{ $pendingActivities->count() }} pending activities
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">All caught up!</h3>
                        <p class="mt-1 text-sm text-gray-500">No pending activities for handover.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Updates -->
    @if($recentUpdates->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Updates</h3>
            <div class="space-y-3">
                @foreach($recentUpdates as $update)
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
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $update->getUserName() }}</span>
                                    {{ $update->getStatusChangeDescription() }}
                                </p>
                                @if($update->remark)
                                    <p class="text-sm text-gray-600 mt-1">{{ $update->remark }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $update->getFormattedUpdateTime() }} • {{ $update->activity->title }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 