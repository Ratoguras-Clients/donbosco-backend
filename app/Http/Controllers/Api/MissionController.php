<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
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

        $missions = Mission::where('organization_id', $organization_id)
        ->where('is_active', true)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($missions->currentPage() - 1) * $missions->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($missions->items())->map(function ($mission) {
                return [
                    'id' => $mission->id,
                     'icon' => $mission->icon,
                     'title'=>$mission->title,
                     'description' => $mission->description,
                ];
            }),
            'total' => $missions->total(),
            'per_page' => $missions->perPage(),
            'current_page' => $missions->currentPage(),
            'last_page' => $missions->lastPage(),
            'start' => $start,
            'offset' => $offset,
            'count' => count($missions->items()),
        ]);
    }
}
