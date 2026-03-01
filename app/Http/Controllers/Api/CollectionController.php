<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Organization;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request, $organization_id = null)
    {
        if (!$organization_id) {
            $organization = Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json([
                    'data'         => [],
                    'total'        => 0,
                    'per_page'     => 0,
                    'current_page' => 0,
                    'last_page'    => 0,
                    'start'        => 0,
                    'offset'       => 0,
                ], 404);
            }
            $organization_id = $organization->id;
        }

        $perPage = $request->query('per_page', 4);
        $page    = $request->query('page', 1);

        $collections = Collection::with(['galleryItems.media'])
            ->where('organization_id', $organization_id)
            ->where('is_active', 1)
            ->orderBy('order_index', 'asc')
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start  = ($collections->currentPage() - 1) * $collections->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($collections->items())->map(function ($collection) {
                return [
                    'id'          => $collection->id,
                    'title'       => $collection->title,
                    'description' => $collection->description,
                    'order_index' => $collection->order_index,
                    'totalimage' => $collection->galleryItems->count(),
                    'cover_images' => $collection->galleryItems
                        ->where('is_cover', true)
                        ->take(5)
                        ->map(fn($g) => $g->media?->url)
                        ->filter()
                        ->values(),
                ];
            }),
            'total'        => $collections->total(),
            'per_page'     => $collections->perPage(),
            'current_page' => $collections->currentPage(),
            'last_page'    => $collections->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($collections->items()),
        ]);
    }
}