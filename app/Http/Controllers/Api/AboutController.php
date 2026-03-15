<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abouthero;
use App\Models\Aboutstory;
use App\Models\Organization;
use Illuminate\Http\Request;

class AboutController extends Controller
{

    public function abouthero(Request $request, $organization_id = null)
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

        $aboutheros = Abouthero::where('organization_id', $organization_id)
            ->where('is_home', true)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($aboutheros->currentPage() - 1) * $aboutheros->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($aboutheros->items())->map(function ($abouthero) {
                return [
                    'id' => $abouthero->id,
                    'title' => $abouthero->title,
                    'content' => $abouthero->content,
                    'is_home' => $abouthero->is_home,
                ];
            }),
            // 'total'        => $aboutheros->total(),
            // 'per_page'     => $aboutheros->perPage(),
            // 'current_page' => $aboutheros->currentPage(),
            // 'last_page'    => $aboutheros->lastPage(),
            // 'start'        => $start,
            // 'offset'       => $offset,
            // 'count'        => count($aboutheros->items()),
        ]);
    }


    public function aboutstory(Request $request, $organization_id = null)
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

        $aboutstories = Aboutstory::with(['media1', 'media2', 'media3'])
            ->where('organization_id', $organization_id)
            ->where('is_home', 1)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($aboutstories->currentPage() - 1) * $aboutstories->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($aboutstories->items())->map(function ($aboutstory) {
                return [
                    'id' => $aboutstory->id,
                    'title' => $aboutstory->title,
                    'content' => $aboutstory->content,
                    'features' => $aboutstory->features,
                    'image_1' => $aboutstory->media1?->url,
                    'image_2' => $aboutstory->media2?->url,
                    'image_3' => $aboutstory->media3?->url,
                    'is_home' => $aboutstory->is_home,
                ];
            }),
            // 'total'        => $aboutstories->total(),
            // 'per_page'     => $aboutstories->perPage(),
            // 'current_page' => $aboutstories->currentPage(),
            // 'last_page'    => $aboutstories->lastPage(),
            // 'start'        => $start,
            // 'offset'       => $offset,
            // 'count'        => count($aboutstories->items()),
        ]);
    }
}