<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeCarousel;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HomeCarouselController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('sortable')) {
            $rowOrder = $request->input('rowOrder', []);

            foreach ($rowOrder as $index => $rowId) {
                HomeCarousel::where('organization_id', $organization->id)
                    ->where('id', $rowId)
                    ->update(['order_index' => $index + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Row order saved successfully.',
            ]);
        }

        if ($request->has('getData')) {
            $start = max(0, intval($request->input('start', 0)));
            $length = min(100, max(5, intval($request->input('length', 10))));

            $query = HomeCarousel::with(['media', 'getCreatedBy', 'getUpdatedBy'])
                ->where('organization_id', $organization->id)
                ->orderBy('order_index', 'ASC');

            $total = $query->count();

            $data = $query->offset($start)->limit($length)->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data->map(function ($item, $i) use ($start) {
                    return [
                        'sn' => $start + $i + 1,
                        'id' => $item->id,
                        'title' => $item->title,
                        'subtitle' => $item->subtitle,
                        'media_path' => $item->media ? url($item->media->filepath) : null,
                        'media_id' => $item->media_id,
                        'is_active' => $item->is_active,
                        'created_by' => $item->getCreatedBy?->name ?? 'N/A',
                        'updated_by' => $item->getUpdatedBy?->name ?? 'N/A',
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                })->toArray(),
            ]);
        }

        return view('admin.home-carousel.index', compact('organization'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'media_id' => 'required|integer',
        ]);

        $isUpdate = $request->filled('id');

        $data = [
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'subtitle' => $validated['sub_title'],
            'media_id' => $validated['media_id'],
        ];

        if ($isUpdate) {
            $carousel = HomeCarousel::where('organization_id', $organization->id)
                ->findOrFail($request->id);

            $carousel->update(array_merge($data, ['updated_by' => Auth::id()]));
        } else {
            HomeCarousel::create(array_merge($data, ['created_by' => Auth::id()]));
        }

        $this->revalidatePaths([
            '/'
        ]);

        return response()->json([
            'success' => true,
            'message' => $isUpdate
                ? 'Home Carousel updated successfully.'
                : 'Home Carousel created successfully.',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $carousel = HomeCarousel::findOrFail($id);
        $carousel->delete();

        $this->revalidatePaths(['/']);

        return response()->json([
            'success' => true,
            'message' => 'Home Carousel deleted successfully.',
        ]);
    }

}