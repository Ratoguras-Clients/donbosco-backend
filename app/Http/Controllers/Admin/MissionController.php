<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\RevalidatesNextJs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MissionController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $service = Mission::where('organization_id', $organization->id)
                ->findOrFail($request->input('id'));

            if (!$request->has('is_active')) {
                $serviceCount = Mission::where('organization_id', $organization->id)
                    ->where('is_active', true)
                    ->count();


                if ($serviceCount >= 3) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'You can only have 3 active missions. Please deactivate one before activating another.',
                    ]);
                }
            }

            $service->is_active = !$service->is_active;
            $service->save();

            $this->revalidatePaths([
                '/', 
                '/about' 
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'The mission is ' . ($service->is_active ? 'activated' : 'deactivated') . ' successfully.',
            ]);
        }

        if ($request->has('getData')) {
            $start  = max(0, intval($request->input('start', 0)));
            $length = min(100, max(5, intval($request->input('length', 10))));

            $query = Mission::with(['getCreatedBy', 'getUpdatedBy'])
                ->where('organization_id', $organization->id)
                ->orderBy('id', 'DESC');

            $count = $query->count();

            $data = $query->offset($start)->limit($length)->get();

            return response()->json([
                'data' => $data->map(function ($item, $i) use ($start) {
                    return [
                        'sn'          => $start + $i + 1,
                        'id'          => $item->id,
                        'title'       => $item->title,
                        'icon'        => $item->icon,
                        'description' => $item->description,
                        'is_active'   => $item->is_active,
                        'created_by'  => $item->getCreatedBy?->name ?? 'N/A',
                        'updated_by'  => $item->getUpdatedBy?->name ?? 'N/A',
                        'created_at'  => $item->created_at,
                        'updated_at'  => $item->updated_at,
                    ];
                })->toArray(),
                'recordsFiltered' => $count,
                'recordsTotal'    => $count,
            ]);
        }

        return view('admin.mission.index', compact('organization'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title'       => 'required|string|max:255|unique:missions,title,' . $request->input('id'),
            'icon'        => 'required|string',
            'description' => 'required|string|max:255',
        ]);

        $isUpdate = $request->filled('id');

        if ($isUpdate) {
            $service = Mission::where('organization_id', $organization->id)
                ->findOrFail($request->input('id'));

            $service->update([
                'title'       => $validated['title'],
                'description' => $validated['description'],
                'icon'        => $validated['icon'],
                'updated_by'  => Auth::id(),
            ]);
        } else {
            Mission::create([
                'title'           => $validated['title'],
                'description'     => $validated['description'],
                'icon'            => $validated['icon'],
                'organization_id' => $organization->id,
                'created_by'      => Auth::id(),
            ]);
        }

         $this->revalidatePaths([
                '/', 
                '/about' 
            ]);

        return response()->json([
            'status'  => true,
            'message' => $isUpdate
                ? 'Mission updated successfully.'
                : 'Mission created successfully.',
        ]);
    }

    public function destroy(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $service = Mission::where('organization_id', $organization->id)
            ->findOrFail($id);

        $service->delete();

         $this->revalidatePaths([
                '/', 
                '/about' 
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Mission deleted successfully.',
        ]);
    }
}