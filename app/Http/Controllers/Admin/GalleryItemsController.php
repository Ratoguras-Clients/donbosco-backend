<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Organization;
use App\Models\GalleryItem;
use Illuminate\Http\Request;

class GalleryItemsController extends Controller
{

    public function index($id)
    {
        $collection = Collection::findOrFail($id);

        return view('admin.gallery-items.index', compact('collection'));
    }

    public function galleryItems($id)
    {
        $collection = Collection::with(['creator'])->findOrFail($id);

        $galleryItems = GalleryItem::with(['creator', 'media'])
            ->where('collection_id', $id)
            ->orderBy('order_index')
            ->get();

        return response()->json([
            'data' => $galleryItems->map(function ($item) {
                return [
                    'id'              => $item->id,
                    'organization_id' => $item->organization_id,
                    'collection_id'   => $item->collection_id,
                    'title'           => $item->title,
                    'image'           => $item->media?->url,
                    'media_id'        => $item->media_id,
                    'order_index'     => $item->order_index,
                    'is_active'       => $item->is_active,
                    'is_cover'        => (bool) $item->is_cover,
                    'created_by'      => $item->creator?->name,
                    'created_at'      => $item->created_at->format('Y-m-d'),
                ];
            }),
        ]);
    }

    public function store(Request $request, $collectionId)
    {
        $collection = Collection::findOrFail($collectionId);
        $organization = Organization::findOrFail($collection->organization_id);

        $validated = $request->validate([
            'media_id'    => 'required|exists:medias,id',
            'title'       => 'nullable|string|max:255',
            'order_index' => 'nullable|integer|min:0',
        ]);

        GalleryItem::create([
            'organization_id' => $organization->id,
            'collection_id'   => $collection->id,
            'media_id'        => $validated['media_id'],
            'title'           => $validated['title'] ?? null,
            'order_index'     => $validated['order_index']
                ?? GalleryItem::where('collection_id', $collection->id)->max('order_index') + 10,
            'created_by'      => auth()->id(),
            'updated_by'      => auth()->id(),
            'is_active'       => true,
            'is_cover'        => false,
        ]);

        return redirect()
            ->route('gallery-items.index', $collection->id)
            ->with('success', 'Gallery item added successfully!');
    }

    public function setCover(Request $request, $collectionId, $itemId)
    {
        $collection = Collection::findOrFail($collectionId);

        $item = GalleryItem::where('collection_id', $collectionId)
            ->findOrFail($itemId);

        // If already a cover, unset it
        if ($item->is_cover) {
            $item->is_cover = false;
            $item->save();

            // Set collection cover_image to the first remaining cover, or null
            $firstCover = GalleryItem::where('collection_id', $collectionId)
                ->where('is_cover', true)
                ->orderBy('order_index')
                ->first();

            $collection->cover_image = $firstCover?->media_id ?? null;
            $collection->updated_by  = auth()->id();
            $collection->save();

            return response()->json([
                'status'   => true,
                'is_cover' => false,
                'message'  => 'Removed from cover images.',
            ]);
        }

        // Check limit before setting
        $coverCount = GalleryItem::where('collection_id', $collectionId)
            ->where('is_cover', true)
            ->count();

        if ($coverCount >= 5) {
            return response()->json([
                'status'  => false,
                'message' => 'A collection can have a maximum of 5 cover images.',
            ], 422);
        }

        $item->is_cover = true;
        $item->save();

        // Set collection cover_image to the first cover by order
        $firstCover = GalleryItem::where('collection_id', $collectionId)
            ->where('is_cover', true)
            ->orderBy('order_index')
            ->first();

        $collection->cover_image = $firstCover?->media_id ?? null;
        $collection->updated_by  = auth()->id();
        $collection->save();

        return response()->json([
            'status'   => true,
            'is_cover' => true,
            'message'  => 'Added to cover images.',
        ]);
    }

    public function destroy(Request $request, $collectionId, $itemId)
    {
        $collection = Collection::findOrFail($collectionId);

        $item = GalleryItem::where('collection_id', $collectionId)
            ->findOrFail($itemId);

        $wasCover = $item->is_cover;

        $item->delete();

        // If the deleted item was a cover, re-sync collection cover_image
        if ($wasCover) {
            $firstCover = GalleryItem::where('collection_id', $collectionId)
                ->where('is_cover', true)
                ->orderBy('order_index')
                ->first();

            $collection->cover_image = $firstCover?->media_id ?? null;
            $collection->updated_by  = auth()->id();
            $collection->save();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Gallery item deleted successfully.',
            ]);
        }

        return redirect()
            ->route('gallery-items.index', $collection->id)
            ->with('success', 'Gallery item deleted successfully.');
    }

    public function toggleStatus(Request $request, $collectionId, $itemId)
    {
        $item = GalleryItem::where('collection_id', $collectionId)
            ->findOrFail($itemId);

        $item->is_active  = !$item->is_active;
        $item->updated_by = auth()->id();
        $item->save();

        return response()->json([
            'status'     => true,
            'is_active'  => $item->is_active,
            'message'    => 'Gallery item status updated successfully.',
        ]);
    }
}