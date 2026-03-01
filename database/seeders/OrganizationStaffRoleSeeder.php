<?php

namespace Database\Seeders;

use App\Models\OrganizationStaff;
use App\Models\OrganizationStaffRole;
use App\Models\StaffRole;
use Illuminate\Database\Seeder;

class OrganizationStaffRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get staff members
        $presidentStaff = OrganizationStaff::where('name', 'President CNI')->first();
        $secretaryStaff = OrganizationStaff::where('name', 'General Secretary CNI')->first();

        // Get roles
        $directorRole = StaffRole::where('slug', 'director')->first();
        $coordinatorRole = StaffRole::where('slug', 'cni-coordinator')->first();

        // Create role assignments
        if ($presidentStaff && $directorRole) {
            OrganizationStaffRole::updateOrCreate(
                ['organization_staff_id' => $presidentStaff->id],
                [
                    'staff_role_id' => $directorRole->id,
                    'is_active' => true,
                    'created_by' => 1,
                ]
            );
        }

        if ($secretaryStaff && $coordinatorRole) {
            OrganizationStaffRole::updateOrCreate(
                ['organization_staff_id' => $secretaryStaff->id],
                [
                    'staff_role_id' => $coordinatorRole->id,
                    'is_active' => true,
                    'created_by' => 1,
                ]
            );
        }
    }
}