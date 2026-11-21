<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('The development database seeder cannot run in production.');
        }

        // Foreign key checks disable kar dete hain taaki truncate mein koi error na aaye
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Task::truncate();
        User::truncate();
        Department::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Official ECL Departments Hierarchy Create Karein
        $departmentsData = [
            [1, null, 'Chairman-cum-Managing Director', 'CCMD'],
            
            // Level 2 (Under CCMD)
            [2, 1, 'GM (T&MS)/TS to CMD', 'GM/TS'],
            [3, 1, 'CVO', 'CVO'],
            [4, 1, 'HoD (Internal Audit)', 'HOD'],
            [5, 1, 'Company Secretary', 'CS'],
            [6, 1, 'Director (Technical) Operations', 'DTO'],
            [7, 1, 'Director (Technical) Project & Planning', 'DTPP'],
            [8, 1, 'Director (HR)', 'DHR'],
            [9, 1, 'Director (Finance)', 'DOF'],
            
            // Level 3 (Under Director HR - id: 8)
            [10, 8, 'IR', 'IR'],
            [11, 8, 'Executive Establishment', 'EE'],
            [12, 8, 'Security', 'SEC'],
            [13, 8, 'Manpower', 'MP'],
            [14, 8, 'Legal', 'LEG'],
            [15, 8, 'Administration', 'ADM'],
            [16, 8, 'Welfare and CSR', 'WCSR'],
            [17, 8, 'RTI Cell', 'RTI'],
            [18, 8, 'NIDAN Cell', 'NIDAN'],
            [19, 8, 'CMS', 'CMS'],
            [20, 8, 'HRD', 'HRD'],
            [21, 8, 'Pension', 'PEN'],
            [22, 8, 'PR', 'PR'],
            [23, 8, 'Rajbhasha', 'RJB'],
            [24, 8, 'WBE', 'WBE'],
            [25, 8, 'Employment', 'EMP'],

            // Level 3 (Under Director Finance - id: 9)
            [26, 9, 'Finance', 'FIN'],
            [27, 9, 'Systems', 'STM'],
            [28, 9, 'PAD', 'PAD'],
            [29, 9, 'Electronics and Telecommunication', 'E&T'],

            // Director (Technical) Operations (id: 6) ke under aane wale departments
            [30, 6, 'Salanpur', 'SAL'],
            [31, 6, 'Satgram - Sripur', 'STS'],
            [32, 6, 'Sodepur', 'SOD'],
            [33, 6, 'Mugma', 'MUG'],
            [34, 6, 'S.P. Mines', 'SPM'],
            [35, 6, 'Rajmahal', 'RJM'],
            [36, 6, 'Kajora', 'KAJ'],
            [37, 6, 'Production / Coal Stock Management / Blasting', 'PRD'],
            [38, 6, 'E&M', 'ENM'],
            [39, 6, 'MM/Purchase/Stores', 'MMS'],
            [40, 6, 'Excavation', 'EXC'],
            [41, 6, 'Safety & Rescue', 'SAF'],
            [42, 6, 'QC', 'QC'],
            [43, 6, 'M&S', 'MNS'],
            [44, 6, 'Survey', 'SRV'],

            // Director (Technical) Project & Planning (id: 7) ke under aane wale departments
            [45, 7, 'Bankola', 'BNK'],
            [46, 7, 'Jhanjra', 'JHJ'],
            [47, 7, 'Kenda', 'KEN'],
            [48, 7, 'Kunustoria', 'KUN'],
            [49, 7, 'Pandaveswar', 'PAN'],
            [50, 7, 'Sonepur Bazari', 'SPB'],
            [51, 7, 'Project & Planning', 'PNP'],
            [52, 7, 'CMC', 'CMC'],
            [53, 7, 'Geology & New Initiatives', 'GNI'],
            [54, 7, 'Environment & Forest', 'ENF'],
            [55, 7, 'Civil', 'CVL'],
            [56, 7, 'LRE', 'LRE'],
            [57, 7, 'Industrial Engineering', 'INE'],
            [58, 7, 'New Coal Block', 'NCB']
        ];

        $depts = [];
        foreach ($departmentsData as [$id, $parentId, $name, $code]) {
            $depts[$name] = Department::updateOrCreate(
                ['id' => $id],
                [
                    'parent_id' => $parentId,
                    'name' => $name,
                    'code' => $code,
                    'is_active' => 1
                ]
            );
        }

        // 2. Fixed Master Admin User Create Karein
        User::updateOrCreate(
            ['email' => 'masteradmin@coalindia.in'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make(env('SEED_ADMIN_PASSWORD', str()->password(24))),
                'phone' => '+91 8670676756',
                'role' => 'admin',
                'department' => 'CCMD', // Yeh field add kar di hai
                'department_id' => $depts['CCMD']->id ?? 1,
                'designation' => 'CCMD',
                'branch' => 'CMD Office',
                'status' => 'active'
            ]
        );

        
       $additionalUsers = [
            [
                'name' => 'Vivek Kumar',
                'password' => env('SEED_USER_PASSWORD', str()->password(24)),
                'role' => 'admin',
                'is_dual_role' => 0,
                'phone' => '+91 9876543210',
                'email' => 'tsdp.ecl.cil@coalindia.in',
                'designation' => 'Chief Manager(HR)/TS to D(HR)',
                'department' => 'DHR', 
                'department_id' => $depts['DHR']->id ?? 8, // DHR department ki ID map hogi
                'branch' => 'CMD Office', 
                'status' => 'active'
            ],
        ];

        foreach ($additionalUsers as $uData) {
            $isDualRole = str_contains($uData['role'], ',') ? 1 : 0;

            User::updateOrCreate(
                ['email' => $uData['email']],
                [
                    'name' => $uData['name'],
                    'password' => Hash::make($uData['password']),
                    'role' => $uData['role'],
                    'is_dual_role' => $isDualRole,
                    'phone' => $uData['phone'] ?? '+91 8670670001',
                    'department' => $uData['department'],
                    'department_id' => $uData['department_id'] ?? null, // Yeh add kiya hai
                    'branch' => $uData['branch'] ?? null, // Yeh branch save karne ke liye zaroori hai
                    'designation' => $uData['designation'],
                    'status' => 'active'
                ]
            );
        }
    }
}