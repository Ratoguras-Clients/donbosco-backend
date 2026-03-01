<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\Collection;
use Illuminate\Http\Request;

class GalleryItemController extends Controller
{
    public function index(Request $request, $collection_id)
    {
        $collection = Collection::find($collection_id);

        if (!$collection) {
            return response()->json([
                'data'         => [],
                'total'        => 0,
                'per_page'     => 0,
                'current_page' => 0,
                'last_page'    => 0,
                'start'        => 0,
                'offset'       => 0,
                'count'        => 0,
            ], 404);
        }

        $perPage = $request->query('per_page', 16);
        $page    = $request->query('page', 1);

        $collection = Collection::find($collection_id);

        $galleryItems = GalleryItem::with(['media'])
            ->where('collection_id', $collection_id)
            ->where('is_active', 1)
            ->orderBy('order_index', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start  = ($galleryItems->currentPage() - 1) * $galleryItems->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($galleryItems->items())->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'title'       => $item->title,
                    'image'       => $item->media?->url,
                    'order_index' => $item->order_index,
                    'is_cover'    => (bool) $item->is_cover,
                ];
            }),
            'details'=>$collection,
            'total'        => $galleryItems->total(),
            'per_page'     => $galleryItems->perPage(),
            'current_page' => $galleryItems->currentPage(),
            'last_page'    => $galleryItems->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($galleryItems->items()),
        ]);
    }
}