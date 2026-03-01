<?php

namespace Database\Seeders;

use App\Models\StaffRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Director',
                'slug' => 'director',
                'description' => 'Head of the CNI organization, responsible for strategic decisions',
            ],
            [
                'name' => 'CNI Coordinator',
                'slug' => 'cni-coordinator',
                'description' => 'Coordinates CNI programs and operations',
            ],
            [
                'name' => 'IT Manager',
                'slug' => 'it-manager',
                'description' => 'Manages IT infrastructure and technical teams',
            ],
            [
                'name' => 'Network Engineer',
                'slug' => 'network-engineer',
                'description' => 'Designs, manages, and maintains network systems',
            ],
            [
                'name' => 'System Administrator',
                'slug' => 'system-administrator',
                'description' => 'Manages servers, systems, backups, and updates',
            ],
            [
                'name' => 'Cyber Security Officer',
                'slug' => 'cyber-security-officer',
                'description' => 'Ensures cybersecurity policies and threat prevention',
            ],
            [
                'name' => 'SOC Analyst',
                'slug' => 'soc-analyst',
                'description' => 'Monitors security incidents and responds to threats',
            ],
            [
                'name' => 'Software Developer',
                'slug' => 'software-developer',
                'description' => 'Develops internal systems and applications',
            ],
            [
                'name' => 'Database Administrator',
                'slug' => 'database-administrator',
                'description' => 'Manages databases, backups, and performance',
            ],
            [
                'name' => 'IT Support Officer',
                'slug' => 'it-support-officer',
                'description' => 'Provides technical support to staff and users',
            ],
            [
                'name' => 'Helpdesk Staff',
                'slug' => 'helpdesk-staff',
                'description' => 'Handles basic IT issues and user queries',
            ],
            [
                'name' => 'Field Technician',
                'slug' => 'field-technician',
                'description' => 'Handles on-site network, hardware, and cabling tasks',
            ],
            [
                'name' => 'Intern / Trainee',
                'slug' => 'intern-trainee',
                'description' => 'Training-level role for students and freshers',
            ],
        ];

        foreach ($roles as $role) {
            StaffRole::updateOrInsert(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'created_by' => 1,
                ]
            );
        }
    }
}
