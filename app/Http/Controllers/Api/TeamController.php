<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganizationStaff;
use Illuminate\Http\Request;

class TeamController extends Controller
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

        $teams = OrganizationStaff::with(['media', 'organizationStaffRole.staffRole'])
            ->where('organization_id', $organization_id)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($teams->currentPage() - 1) * $teams->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($teams->items())->map(function ($team) {
                return [
                    'id'          => $team->id,
                    'name'        => $team->name,
                    'email'       => $team->email,
                    'phone'       => $team->phone,
                    'designation' => $team->organizationStaffRole?->staffRole?->name ?? null,
                    'image'       => $team->media?->url,
                    'bio'         => $team->bio,
                ];
            }),
            'total'        => $teams->total(),
            'per_page'     => $teams->perPage(),
            'current_page' => $teams->currentPage(),
            'last_page'    => $teams->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($teams->items()),
        ]);
    }
}