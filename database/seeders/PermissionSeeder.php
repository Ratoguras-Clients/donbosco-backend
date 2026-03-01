<?php

namespace Database\Seeders;

use App\Models\CMS\Page;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'group' => 'user-management',
                'name' => ['view', 'create', 'update', 'delete', 'dashboard', 'permission-view', 'activity-logs', 'status', 'view-all'],
            ],
            [
                'group' => 'role-management',
                'name' => ['view', 'create', 'update', 'delete', 'dashboard', 'permissions-view', 'users', 'permissions-manage'],
            ],
            [
                'group' => 'section-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'sectionType-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'fileEditor-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'siteSetting-management',
                'name' => ['view', 'create', 'update', 'delete', 'change'],
            ],
            [
                'group' => 'products-management',
                'name' => ['view', 'create', 'update', 'delete', 'price'],
            ],
            [
                'group' => 'token-management',
                'name' => ['view', 'create', 'update', 'delete', 'change-status'],
            ],
            [
                'group' => 'supportEmail-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'job-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'applicant-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'contact-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'quotation-management',
                'name' => ['view', 'create', 'update', 'delete', 'change-status'],
            ],
            [
                'group' => 'media-management',
                'name' => ['view', 'create', 'update', 'delete'],
            ],
            [
                'group' => 'page-management',
                'name' => ['create'],
            ],
        ];

        // PostgreSQL: temporarily disable foreign key constraints
        DB::statement('SET session_replication_role = replica;');

        // Truncate pivot and permissions tables
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('permissions')->truncate();

        // Restore foreign key enforcement
        DB::statement('SET session_replication_role = DEFAULT;');

        // Create permissions
        foreach ($data as $item) {
            foreach ($item['name'] as $action) {
                Permission::firstOrCreate([
                    'name' => $item['group'] . '-' . $action,
                    'group' => $item['group'],
                ]);
            }
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        // Super Admin → all permissions
        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole) {
            $allPermissions = Permission::all();
            $superAdminRole->syncPermissions($allPermissions);
            echo 'Assigned ALL (' . $allPermissions->count() . ") permissions to super-admin.\n";
        } else {
            echo "Role super-admin not found.\n";
        }

        // Admin → limited + all page permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminPermissions = [
                'role-management-view','role-management-create','role-management-update','role-management-delete',
                'role-management-dashboard','role-management-permissions-view','role-management-permissions-manage',
                'role-management-users','user-management-view','user-management-create','user-management-update',
                'user-management-delete','user-management-dashboard','user-management-permission-view',
                'user-management-activity-logs','user-management-status','user-management-view-all',
                'products-management-view','products-management-update','section-management-view',
                'section-management-update','token-management-view','token-management-create',
                'token-management-update','token-management-delete'
            ];

            $pagePermissions = Permission::where('group', 'page-management')->pluck('name')->toArray();

            $allAdminPermissions = array_unique(array_merge($adminPermissions, $pagePermissions));

            $adminRole->syncPermissions($allAdminPermissions);
            echo 'Assigned ' . count($allAdminPermissions) . " permissions (including page permissions) to admin.\n";
        } else {
            echo "Role admin not found.\n";
        }
    }
}
