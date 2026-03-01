<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationStaff;
use App\Models\OrganizationStaffRole;
use App\Models\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $cni = Organization::where('slug', 'cni')->first();
            $cniyef = Organization::where('slug', 'cniyef')->first();

            if (!$cni || !$cniyef) {
                $this->command->warn('Organizations not found. Seeder skipped.');
                return;
            }

            // Create staff safely
            $staffMembers = [
                [
                    'organization_id' => $cni->id,
                    'name' => 'President CNI',
                    'email' => 'president@cni.org',
                    'phone' => '9800000001',
                    'bio' => 'President of Confederation of Nepalese Industries.',
                    'order_index' => 1,
                    'created_by'  => 1,
                ],
                [
                    'organization_id' => $cni->id,
                    'name' => 'General Secretary CNI',
                    'email' => 'secretary@cni.org',
                    'phone' => '9800000002',
                    'bio' => 'General Secretary of CNI.',
                    'order_index' => 2,
                ],
                [
                    'organization_id' => $cniyef->id,
                    'name' => 'Chairperson CNIYEF',
                    'email' => 'chairperson@cniyef.org',
                    'phone' => '9800000003',
                    'bio' => 'Chairperson of CNI Young Entrepreneurs Forum.',
                    'order_index' => 1,
                    'created_by'  => 1,
                ],
                [
                    'organization_id' => $cniyef->id,
                    'name' => 'Vice Chairperson CNIYEF',
                    'email' => 'vice@cniyef.org',
                    'phone' => '9800000004',
                    'bio' => 'Vice Chairperson of CNIYEF.',
                    'order_index' => 2,
                    'created_by'  => 1,
                ],
            ];

            foreach ($staffMembers as $staff) {
                OrganizationStaff::updateOrCreate(
                    [
                        'organization_id' => $staff['organization_id'],
                        'name' => $staff['name'],
                    ],
                    array_merge($staff, [
                        'is_active' => true,
                        'created_by' => 1,
                    ])
                );
            }

            // Fetch roles
            $director = StaffRole::where('slug', 'director')->first();
            $coordinator = StaffRole::where('slug', 'cni-coordinator')->first();

            if (!$director || !$coordinator) {
                $this->command->warn('Required staff roles not found.');
                return;
            }

            // Assign roles
            $roleMap = [
                'President CNI' => $director,
                'General Secretary CNI' => $coordinator,
                'Chairperson CNIYEF' => $director,
                'Vice Chairperson CNIYEF' => $coordinator,
            ];

            foreach ($roleMap as $staffName => $role) {
                $staff = OrganizationStaff::where('name', $staffName)->first();

                if (!$staff) {
                    continue;
                }

                OrganizationStaffRole::updateOrCreate(
                    [
                        'organization_staff_id' => $staff->id,
                        'staff_role_id' => $role->id,
                    ],
                    [
                        'is_active' => true,
                        'created_by' => 1,
                    ]
                );
            }
        });
    }
}
