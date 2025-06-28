@extends('layouts.app')

@section('title', 'Activities')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Activities</h1>
            <p class="text-gray-600">Manage and track all activities</p>
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

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="GET" action="{{ route('activities.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" name="category" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ request('category') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigned To</label>
                <select id="assigned_to" name="assigned_to" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Members</option>
                    @foreach($teamMembers as $member)
                        <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="lg:col-span-5 flex space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('activities.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Activities List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @if($activities->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($activities as $activity)
                    <li class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('activities.show', $activity) }}" class="hover:text-indigo-600">
                                                {{ $activity->title }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($activity->description, 150) }}</p>
                                        
                                        <div class="flex items-center space-x-4 mt-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getStatusColor() }}">
                                                    {{ ucfirst(str_replace('_', ' ', $activity->status)) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity->getPriorityColor() }}">
                                                    {{ ucfirst($activity->priority) }}
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span>Date: {{ $activity->activity_date->format('M d, Y') }}</span>
                                                @if($activity->assignee)
                                                    <span>Assigned: {{ $activity->assignee->name }}</span>
                                                @endif
                                                <span>Created: {{ $activity->creator->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0 flex space-x-2">
                                <a href="{{ route('activities.show', $activity) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View
                                </a>
                                <a href="{{ route('activities.edit', $activity) }}" 
                                   class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $activities->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No activities found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new activity.</p>
                <div class="mt-6">
                    <a href="{{ route('activities.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Activity
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 