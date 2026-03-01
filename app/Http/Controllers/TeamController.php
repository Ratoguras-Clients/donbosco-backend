<?php

namespace App\Http\Controllers;

use App\Models\Journeys;
use App\Models\Organization;
use App\Models\OrganizationStaff;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamController extends Controller
{
    public function index()
    {
        $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

        $staffs = OrganizationStaff::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index', 'desc')
            ->get();
        $activeCount = $staffs->count();

        $staffs = $staffs->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'phone' => $item->phone,
                'image' => $item->attachment
                    ? $item->attachment->url
                    : null,
                'bio' => $item->bio,
            ];
        });

         $oldestStartDate = Journeys::where('organization_id', $organization->id)
                ->where('is_active', true)
                ->min('start_date');

            // Calculate years of experience from oldest date to now
            $experienceYears = $oldestStartDate
                ? Carbon::parse($oldestStartDate)->diffInYears(Carbon::now())
                : 0;
        



        $data = [
            'staffs' => $staffs,
            'count' => $activeCount,
            'experience_years' => $experienceYears,

        ];

        return view('guest.team', compact('data'));
    }
}
