<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Journeys;
use App\Models\Organization;
use App\Models\OrganizationStaff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatController extends Controller
{
    private function getMainOrganization()
    {
        return Organization::whereNull('parent_organization_id')->first();
    }

    public function stats()
    {
        $organization = $this->getMainOrganization();

        if (!$organization) {
            return response()->json(['data' => []], 404);
        }

        $oldestStartDate = Journeys::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->min('start_date');

        $yearsOfService = $oldestStartDate
            ? Carbon::parse($oldestStartDate)->diffInYears(Carbon::now())
            : 0;

        $memberCount = OrganizationStaff::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->count();

        $eventsCount = Events::where('organization_id', $organization->id)
            ->where('is_published', true)
            ->count();

        $sisterOrganizationCount = Organization::where('parent_organization_id', $organization->id)
            ->count();

        return response()->json([
            'data' => [
                ['label' => 'Students Enrolled', 'value' => '2500+'],
                ['label' => 'Awards Won', 'value' =>'33+'],
                ['label' => 'Programs Offered', 'value' => '4+'],
                ['label' => 'Countries Represented', 'value' => '4+'],

            ],
        ]);
    }
}