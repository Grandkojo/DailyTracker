<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Activity;
use App\Models\ActivityUpdate;
use App\Models\Department;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmployeeActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the existing Support department
        $department = Department::where('name', 'Support')->firstOrFail();

        // Map category names to existing categories from CategorySeeder
        $categoryMapping = [
            'sms_monitoring' => 'SMS Monitoring',
            'system_maintenance' => 'System Maintenance', 
            'user_support' => 'User Support',
            'bug_fix' => 'Bug Fix',
            'performance_monitoring' => 'Performance Monitoring',
            'other' => 'Other'
        ];

        $categoryModels = [];
        foreach ($categoryMapping as $key => $name) {
            $category = Category::where('name', $name)->first();
            if ($category) {
                $categoryModels[$key] = $category;
            } else {
                throw new \Exception("Category '{$name}' not found!");
            }
        }

        // Ensure we have all categories
        if (count($categoryModels) !== count($categoryMapping)) {
            throw new \Exception('Not all categories were found. Found: ' . count($categoryModels) . ', Expected: ' . count($categoryMapping));
        }

        // Create 3 employees
        $employees = [];
        for ($i = 1; $i <= 3; $i++) {
            $employee = User::firstOrCreate(
                ['email' => "employee{$i}@example.com"],
                [
                    'name' => "Employee {$i}",
                    'password' => bcrypt('password'),
                    'department_id' => $department->id,
                    'position' => 'Support Specialist',
                    'phone' => "+1234567890{$i}",
                    'role' => 'support_team',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
            $employees[] = $employee;
        }

        // Activity titles
        $activityTitles = [
            'Daily SMS Count Verification',
            'System Performance Check',
            'User Account Maintenance',
            'Database Backup Verification',
            'Network Connectivity Test',
            'Security Log Review',
            'Software Update Deployment',
            'Hardware Inventory Check',
            'Email System Monitoring',
            'Backup System Test',
            'Firewall Configuration Review',
            'Server Health Check',
            'Application Performance Monitoring',
            'Data Recovery Test',
            'System Documentation Update',
            'Virus Scan Execution',
            'Printer Configuration',
            'VPN Connection Test',
            'Cloud Storage Sync',
            'Mobile Device Management',
            'Website Performance Check',
            'API Endpoint Testing',
            'Database Optimization',
            'User Training Session',
            'Emergency Response Drill',
            'Compliance Audit',
            'Disaster Recovery Test',
            'System Integration Test',
            'Data Migration',
            'System Upgrade'
        ];

        // Activity descriptions
        $activityDescriptions = [
            'Verify and compare manual SMS counts with system logs to ensure accuracy.',
            'Monitor system performance metrics and identify potential bottlenecks.',
            'Perform routine maintenance on user accounts and permissions.',
            'Verify that database backups are running successfully and data integrity is maintained.',
            'Test network connectivity across all critical systems and endpoints.',
            'Review security logs for any suspicious activities or potential threats.',
            'Deploy software updates to ensure systems are running the latest versions.',
            'Conduct inventory check of all hardware assets and update records.',
            'Monitor email system performance and troubleshoot any delivery issues.',
            'Test backup systems to ensure data can be recovered in case of failure.',
            'Review and update firewall configurations for optimal security.',
            'Perform comprehensive health check on all servers.',
            'Monitor application performance and identify optimization opportunities.',
            'Test data recovery procedures to ensure business continuity.',
            'Update system documentation to reflect current configurations.',
            'Execute virus scans across all systems and quarantine any threats.',
            'Configure and test printer connections and settings.',
            'Test VPN connections for remote access capabilities.',
            'Synchronize data with cloud storage systems.',
            'Manage and configure mobile devices for team members.',
            'Check website performance and loading times.',
            'Test API endpoints for functionality and response times.',
            'Optimize database queries and performance.',
            'Conduct training session for new system features.',
            'Execute emergency response procedures and protocols.',
            'Perform compliance audit to ensure regulatory requirements are met.',
            'Test disaster recovery procedures and backup systems.',
            'Test system integration between different platforms.',
            'Execute data migration between systems.',
            'Perform system upgrade to latest version.'
        ];

        // Date ranges
        $today = Carbon::today();
        $oneDayAgo = Carbon::today()->subDays(1);
        $twoDaysAgo = Carbon::today()->subDays(2);

        // Create activities for each employee
        foreach ($employees as $index => $employee) {
            // Activity distribution: 3 today, 4 one day ago, 3 two days ago
            $dateDistribution = [
                $today->format('Y-m-d') => 3,
                $oneDayAgo->format('Y-m-d') => 4,
                $twoDaysAgo->format('Y-m-d') => 3
            ];

            $activityCount = 0;
            foreach ($dateDistribution as $date => $count) {
                for ($i = 0; $i < $count; $i++) {
                    $randomCategoryKey = array_rand($categoryMapping);
                    $activity = Activity::create([
                        'title' => $activityTitles[$activityCount],
                        'description' => $activityDescriptions[$activityCount],
                        'category_id' => $categoryModels[$randomCategoryKey]->category_id,
                        'priority' => ['low', 'medium', 'high', 'critical'][array_rand([0, 1, 2, 3])],
                        'status' => 'pending',
                        'activity_date' => $date,
                        'estimated_duration' => Carbon::createFromTime(rand(1, 8), rand(0, 59)),
                        'assigned_to' => $employee->id,
                        'created_by' => $employee->id,
                    ]);

                    // Create 8 updates for this activity (2 for each status)
                    $this->createActivityUpdates($activity, $employee);
                    $activityCount++;
                }
            }
        }
    }

    /**
     * Create 8 activity updates (2 for each status)
     */
    private function createActivityUpdates(Activity $activity, User $employee): void
    {
        // Define final statuses with realistic distribution
        $finalStatuses = ['pending', 'in_progress', 'done', 'cancelled'];
        $finalStatus = $finalStatuses[array_rand($finalStatuses)];
        
        // Create a realistic status progression based on final status
        $statusProgression = $this->getStatusProgression($finalStatus);
        
        $updateTimes = [];
        
        // Generate update times within the activity date
        $activityDate = Carbon::parse($activity->activity_date);
        for ($i = 0; $i < count($statusProgression); $i++) {
            $updateTimes[] = $activityDate->copy()->addHours(rand(1, 23))->addMinutes(rand(0, 59));
        }
        sort($updateTimes); // Sort chronologically

        $currentStatus = null;
        $updateCount = 0;

        foreach ($statusProgression as $status) {
            $previousStatus = $currentStatus;
            $currentStatus = $status;

            $remarks = [
                'pending' => ['Activity created and assigned', 'Status set to pending for review'],
                'in_progress' => ['Work started on this activity', 'Progress update - work in progress'],
                'done' => ['Activity completed successfully', 'All tasks finished and verified'],
                'cancelled' => ['Activity cancelled due to priority change', 'Cancelled - no longer required']
            ];

            ActivityUpdate::create([
                'activity_id' => $activity->id,
                'updated_by' => $employee->id,
                'previous_status' => $previousStatus,
                'new_status' => $currentStatus,
                'remark' => $remarks[$status][array_rand($remarks[$status])],
                'user_bio_details' => $employee->getBioDetails(),
                'update_time' => $updateTimes[$updateCount],
            ]);

            $updateCount++;
        }

        // Update the activity's final status
        $activity->update(['status' => $currentStatus]);
    }

    /**
     * Get realistic status progression based on final status
     */
    private function getStatusProgression(string $finalStatus): array
    {
        return match($finalStatus) {
            'pending' => ['pending', 'pending'], // Stays pending
            'in_progress' => ['pending', 'in_progress', 'in_progress'], // Progresses to in_progress
            'done' => ['pending', 'in_progress', 'done', 'done'], // Completes the cycle
            'cancelled' => ['pending', 'cancelled', 'cancelled'], // Gets cancelled early
            default => ['pending', 'pending']
        };
    }
} 