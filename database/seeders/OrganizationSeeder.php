<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cni = Organization::updateOrCreate(
            ['slug' => 'cni'],
            [
                'parent_organization_id' => null,
                'name' => 'Confederation of Nepalese Industries',
                'short_name' => 'CNI',
                'description' => 'CNI is the umbrella organization representing industries and businesses of Nepal.',
                'logo' => null,
                'established_date' => '2015-01-01',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );

        /**
         * Sister Organization: CNIYEF
         */
        Organization::updateOrCreate(
            ['slug' => 'cniyef'],
            [
                'parent_organization_id' => $cni->id,
                'name' => 'CNI Young Entrepreneurs Forum',
                'short_name' => 'CNIYEF',
                'description' => 'CNIYEF is a platform under CNI that empowers young entrepreneurs through leadership, innovation, and business networking.',
                'logo' => null,
                'established_date' => '2016-01-01',
                'status' => 'active',
                'created_by' => 1,
                'updated_by' => 1,
            ]
        );
    }
}
