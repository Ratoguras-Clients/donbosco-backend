<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Journeys;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JourneyController extends Controller
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

        $journeys = Journeys::with('media','media2')
            ->where('organization_id', $organization_id)
            ->where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($journeys->currentPage() - 1) * $journeys->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($journeys->items())->map(function ($journey) {
                return [
                    'id'          => $journey->id,
                    'title'       => $journey->title,
                    'description' => $journey->description,
                    'image'       => $journey->media?->url,
                    'image2'      => $journey->media2?->url,
                    'start_date'  => $journey->start_date ? Carbon::parse($journey->start_date)->format('j M, Y') : null,
                ];
            }),
            'total'        => $journeys->total(),
            'per_page'     => $journeys->perPage(),
            'current_page' => $journeys->currentPage(),
            'last_page'    => $journeys->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($journeys->items()),
        ]);
    }
}