<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OrganizationController extends Controller
{
    // Only child organizations (have a parent)
    public function index()
    {
        $organizations = Organization::where('status', 'active')
            ->whereNotNull('parent_organization_id')
            ->with(['logoMedia', 'imageMedia'])
            ->get();

        return response()->json([
            'organizations' => $this->formatOrganizations($organizations),
        ]);
    }

    // All active organizations
    public function all()
    {
        $organizations = Organization::where('status', 'active')
            ->with(['logoMedia', 'imageMedia'])
            ->get();

        return response()->json([
            'organizations' => $this->formatOrganizations($organizations),
        ]);
    }

    private function formatOrganizations($organizations): array
    {
        return $organizations->map(function ($org) {
            return [
                'id'               => $org->id,
                'name'             => $org->name,
                'short_name'       => $org->short_name,
                'slug'             => $org->slug,
                'mission'          => $org->mission,
                'description'      => $org->description,
                'logo'             => $org->logoMedia?->url,
                'image'            => $org->imageMedia?->url,
                'established_date' => $org->established_date
                    ? Carbon::parse($org->established_date)->format('j M, Y')
                    : null,
                'status'           => $org->status,
            ];
        })->toArray();
    }
}