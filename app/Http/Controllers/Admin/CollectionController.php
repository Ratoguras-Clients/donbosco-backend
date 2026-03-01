<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Collection;
use App\Traits\RevalidatesNextJs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->ajax() && $request->has('getData')) {

            $query = Collection::with(['creator', 'updater', 'galleryItems.media'])
                ->where('organization_id', $organization->id)
                ->latest();

            $total = $query->count();

            $collections = $query
                ->skip($request->start)
                ->take($request->length)
                ->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $collections->map(function ($item, $index) use ($request) {
                    return [
                        'sn' => $request->start + $index + 1,
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'cover_images' => $item->galleryItems
                            ->where('is_cover', true)
                            ->take(5)
                            ->map(fn($g) => $g->media?->url)
                            ->filter()
                            ->values(),
                        'total' => $item->galleryItems->count(),
                        'order_index' => $item->order_index,
                        'is_active' => $item->is_active,
                        'created_by' => $item->creator?->name,
                        'updated_by' => $item->updater?->name,
                        'created_at' => $item->created_at->format('Y-m-d'),
                    ];
                }),
            ]);
        }

        return view('admin.collections.index', compact('organization'));
    }

    public function create(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.collections.create', compact('organization'));
    }

    public function edit($id)
    {
        $collection = Collection::with(['creator', 'updater'])->findOrFail($id);
        $organization = $collection->organization;

        return view('admin.collections.edit', compact('collection', 'organization'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Collection::create([
            'organization_id' => $organization->id,
            'title' => $request->title,
            'description' => $request->description,
            'is_active' => 1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/media'
        ]);

        return redirect()
            ->route('collections.index', $organization->slug)
            ->with('success', 'Collection created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $collection = Collection::findOrFail($id);
        $organization = Organization::findOrFail($collection->organization_id);

        $collection->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'],
            'updated_by' => Auth::id(),
        ]);

        $message = 'Collection updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $this->revalidatePaths([
            '/media'
        ]);

        return redirect()
            ->route('collections.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $collection = Collection::where('organization_id', $organization->id)
            ->where('id', $id)
            ->firstOrFail();

        $collection->delete();

        $this->revalidatePaths([
            '/media'
        ]);

        return redirect()
            ->route('collections.index', $organization->slug)
            ->with('success', 'Collection deleted successfully.');
    }

    public function toggleStatus(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $collection = Collection::where('organization_id', $organization->id)
            ->where('id', $request->id)
            ->firstOrFail();

        $collection->is_active = $collection->is_active ? 0 : 1;
        $collection->updated_by = Auth::id();
        $collection->save();

        $this->revalidatePaths([
            '/media'
        ]);

        return response()->json([
            'message' => 'Collection status updated successfully.',
            'new_status' => $collection->is_active,
        ]);
    }
}