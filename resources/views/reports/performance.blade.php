@extends('layouts.app')

@section('title', 'User Performance Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Performance Report</h1>
            <p class="text-gray-600">Team member performance metrics and completion rates</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.reports.export-performance', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.reports.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow">
        <form method="GET" action="{{ route('admin.reports.performance') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="md:col-span-2 flex space-x-3">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Generate Report
                </button>
                <a href="{{ route('admin.reports.performance') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Team Members</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $users->count() }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Completed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $users->sum('completed') }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $users->sum('pending') }}</dd>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg Completion Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ round($users->avg('completion_rate'), 1) }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status Distribution Pie Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Activity Status Distribution</h3>
                <p class="text-sm text-gray-600">Overall team activity status breakdown</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-center h-64">
                    <div class="relative w-48 h-48">
                        @php
                            $total = array_sum($statusDistribution);
                            $completedAngle = $total > 0 ? ($statusDistribution['Completed'] / $total) * 360 : 0;
                            $pendingAngle = $total > 0 ? ($statusDistribution['Pending'] / $total) * 360 : 0;
                            $cancelledAngle = $total > 0 ? ($statusDistribution['Cancelled'] / $total) * 360 : 0;
                        @endphp
                        
                        <!-- Pie Chart -->
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            @if($total > 0)
                                <!-- Completed -->
                                <circle cx="50" cy="50" r="40" fill="none" stroke="#10B981" stroke-width="20" 
                                        stroke-dasharray="{{ $completedAngle * 1.256 }} 628.32" stroke-dashoffset="0"/>
                                
                                <!-- Pending -->
                                <circle cx="50" cy="50" r="40" fill="none" stroke="#F59E0B" stroke-width="20" 
                                        stroke-dasharray="{{ $pendingAngle * 1.256 }} 628.32" 
                                        stroke-dashoffset="{{ -$completedAngle * 1.256 }}"/>
                                
                                <!-- Cancelled -->
                                <circle cx="50" cy="50" r="40" fill="none" stroke="#EF4444" stroke-width="20" 
                                        stroke-dasharray="{{ $cancelledAngle * 1.256 }} 628.32" 
                                        stroke-dashoffset="{{ -($completedAngle + $pendingAngle) * 1.256 }}"/>
                            @else
                                <circle cx="50" cy="50" r="40" fill="none" stroke="#E5E7EB" stroke-width="20"/>
                            @endif
                        </svg>
                        
                        <!-- Center text -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ $total }}</div>
                                <div class="text-sm text-gray-600">Total</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Legend -->
                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-700">Completed</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['Completed'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-700">Pending</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['Pending'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-700">Cancelled</span>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $statusDistribution['Cancelled'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Insights -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Team Insights</h3>
                <p class="text-sm text-gray-600">Key performance indicators and highlights</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- Top Performer -->
                @if($topPerformer && $topPerformer['completion_rate'] > 0)
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-green-900">Top Performer</h4>
                            <p class="text-sm text-green-700">{{ $topPerformer['user']->name }} - {{ $topPerformer['completion_rate'] }}% completion rate</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Most Active -->
                @if($mostActive && $mostActive['total_assigned'] > 0)
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-sm font-medium text-blue-900">Most Active</h4>
                            <p class="text-sm text-blue-700">{{ $mostActive['user']->name }} - {{ $mostActive['total_assigned'] }} activities assigned</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Team Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($avgTeamCompletionRate, 2) }}%</div>
                        <div class="text-sm text-gray-600">Team Avg Rate</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900">{{ $totalTeamActivities }}</div>
                        <div class="text-sm text-gray-600">Total Activities</div>
                    </div>
                </div>

                <!-- Department Performance -->
                @if($departmentPerformance->count() > 0)
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Department Performance</h4>
                    <div class="space-y-2">
                        @foreach($departmentPerformance as $dept)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-700">{{ $dept['department'] }}</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $dept['completion_rate'] }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $dept['completion_rate'] }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Team Performance</h3>
            <p class="text-sm text-gray-600">Performance metrics from {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
        </div>
        
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Assigned</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updates Made</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $userData)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-indigo-600">
                                                    {{ strtoupper(substr($userData['user']->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $userData['user']->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $userData['user']->position }}</div>
                                            @if($userData['user']->department)
                                                <div class="text-xs text-gray-400">{{ $userData['user']->department->name }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $userData['total_assigned'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-900">{{ $userData['completed'] }}</span>
                                        @if($userData['total_assigned'] > 0)
                                            <span class="ml-2 text-xs text-green-600">
                                                ({{ round(($userData['completed'] / $userData['total_assigned']) * 100, 1) }}%)
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $userData['pending'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $userData['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-900">{{ $userData['completion_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $userData['updates_count'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.reports.activity', ['user_id' => $userData['user']->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                       class="text-indigo-600 hover:text-indigo-900">
                                        View Activities
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No team members found</h3>
                <p class="mt-1 text-sm text-gray-500">No active team members found for the selected date range.</p>
            </div>
        @endif
    </div>

    <!-- Performance Charts (Optional) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Completion Rate Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Completion Rates</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($users->sortByDesc('completion_rate') as $userData)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-indigo-600">
                                        {{ strtoupper(substr($userData['user']->name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $userData['user']->name }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $userData['completion_rate'] }}%"></div>
                                </div>
                                <span class="text-sm text-gray-900 w-12 text-right">{{ $userData['completion_rate'] }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Activity Distribution -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Activity Distribution</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($users->sortByDesc('total_assigned') as $userData)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-xs font-medium text-blue-600">
                                        {{ strtoupper(substr($userData['user']->name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $userData['user']->name }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-green-600">{{ $userData['completed'] }} completed</span>
                                <span class="text-sm text-yellow-600">{{ $userData['pending'] }} pending</span>
                                <span class="text-sm text-gray-900 font-medium">{{ $userData['total_assigned'] }} total</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 