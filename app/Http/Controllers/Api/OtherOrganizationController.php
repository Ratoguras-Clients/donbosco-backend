<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OtherOrganizationController extends Controller
{
    public function index(Request $request, $organization_id = null)
    {
        if (!$organization_id) {
            $organization = \App\Models\Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'per_page' => 0,
                    'current_page' => 0,
                    'last_page' => 0,
                    'start' => 0,
                    'offset' => 0,
                ], 404);
            }
            $organization_id = $organization->id;
        }

        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $otherOrganizations = \App\Models\OtherOrganization::where('organization_id', $organization_id)
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($otherOrganizations->currentPage() - 1) * $otherOrganizations->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($otherOrganizations->items())->map(function ($org) {
                return [
                    'id' => $org->id,
                    'name' => $org->name,
                    'short_name' => $org->short_name,
                    'mission' => $org->mission,
                    'description' => $org->description,
                    'image' => $org->imageMedia?->url,
                    'url' => $org->url,
                    'established_date' => $org->established_date ? Carbon::parse($org->established_date)->format('j M, Y') : null,
                ];
            }),
            'total' => $otherOrganizations->total(),
            'per_page' => $otherOrganizations->perPage(),
            'current_page' => $otherOrganizations->currentPage(),
            'last_page' => $otherOrganizations->lastPage(),
            'start' => $start,
            'offset' => $offset,
            'count' => count($otherOrganizations->items()),
        ]);
    }
}