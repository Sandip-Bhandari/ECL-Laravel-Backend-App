<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments Create Karein
        $departmentsData = [
            ['Manpower', 'MAN'],
            ['HRD Office', 'HRD'],
            ['Finance', 'FIN'],
            ['Safety', 'SAFE'],
            ['Operations', 'OPS'],
            ['AI & Tech', 'TECH']
        ];

        $depts = [];
        foreach ($departmentsData as [$name, $code]) {
            $depts[$name] = Department::firstOrCreate(
                ['name' => $name], 
                ['code' => $code]
            );
        }

        // 2. Admin User Create Karein
        $admin = User::updateOrCreate(
            ['email' => 'admin@ecl.gov.in'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                'department_id' => $depts['HRD Office']->id,
                'designation' => 'Management Admin',
                'status' => 'active'
            ]
        );

        // 3. Employees Create Karein
        $employees = [
            ['Rahul Sharma', 'rahul@ecl.com', 'Manpower', 'Employee', 'AI & Automation Developer'],
            ['Amit Kumar', 'amit@ecl.com', 'Finance', 'Admin', 'Finance Administrator'],
            ['Priya Sen', 'priya@ecl.com', 'AI & Tech', 'Employee', 'Software Developer'],
            ['Sandip Bhandari', 'sandip@ecl.gov.in', 'Operations', 'employee', 'AI & Jetpack Compose Developer'],
        ];

        $users = [];
        foreach ($employees as [$name, $email, $dept, $role, $designation]) {
            $users[$email] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('ChangeMe@123'),
                    'role' => strtolower($role),
                    'department_id' => $depts[$dept]->id,
                    'designation' => $designation,
                    'phone' => $email === 'sandip@ecl.gov.in' ? '+91 9876543210' : null,
                    'branch' => $email === 'sandip@ecl.gov.in' ? 'Asansol Corporate HQ' : null,
                    'status' => 'active'
                ]
            );
        }

        // 4. Sample Tasks Create Karein
        Task::updateOrCreate(
            ['title' => 'Database Setup', 'assigned_to' => $users['sandip@ecl.gov.in']->id],
            [
                'description' => 'Configure MySQL and connect with Laravel backend.',
                'department_id' => $depts['Operations']->id,
                'created_by' => $admin->id,
                'status' => 'Pending',
                'due_date' => '2026-08-05'
            ]
        );

        Task::updateOrCreate(
            ['title' => 'UI Bug Fixing', 'assigned_to' => $users['sandip@ecl.gov.in']->id],
            [
                'description' => 'Fix alignment issues on login & dashboard screens.',
                'department_id' => $depts['AI & Tech']->id,
                'created_by' => $admin->id,
                'status' => 'Pending',
                'due_date' => '2026-08-18'
            ]
        );

        Task::updateOrCreate(
            ['title' => 'API Integration', 'assigned_to' => $users['sandip@ecl.gov.in']->id],
            [
                'description' => 'Connect REST endpoints with Jetpack Compose UI.',
                'department_id' => $depts['AI & Tech']->id,
                'created_by' => $admin->id,
                'status' => 'Pending',
                'due_date' => '2026-08-22'
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Safety Audit Report', 'assigned_to' => $users['rahul@ecl.com']->id],
            [
                'description' => 'Prepare manpower safety audit report.',
                'department_id' => $depts['Manpower']->id,
                'created_by' => $admin->id,
                'status' => 'In Progress',
                'due_date' => '2026-08-20'
            ]
        );

        Task::updateOrCreate(
            ['title' => 'Payroll Verification', 'assigned_to' => $users['amit@ecl.com']->id],
            [
                'description' => 'Verify monthly payroll records.',
                'department_id' => $depts['Finance']->id,
                'created_by' => $admin->id,
                'status' => 'Completed',
                'due_date' => '2026-08-01',
                'completed_at' => now()
            ]
        );
    }
}