<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeCarousel;
use App\Models\Organization;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    public function index(Request $request, $organization_id = null)
    {
        if (!$organization_id) {
            $organization = Organization::where('parent_organization_id', null)->first();
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

        $heros = HomeCarousel::with('media')
            ->where('organization_id', $organization_id)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($heros->currentPage() - 1) * $heros->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($heros->items())->map(function ($hero) {
                return [
                    'id'       => $hero->id,
                    'title'    => $hero->title,
                    'subtitle' => $hero->subtitle,
                    'image'    => $hero->media?->url,
                ];
            }),
            'total'        => $heros->total(),
            'per_page'     => $heros->perPage(),
            'current_page' => $heros->currentPage(),
            'last_page'    => $heros->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($heros->items()),
        ]);
    }
}