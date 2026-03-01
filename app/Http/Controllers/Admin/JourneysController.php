<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Journeys;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JourneysController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $Journey = Journeys::find($request->input('id'));
            $Journey->is_active = !$Journey->is_active;
            $Journey->save();
                $this->revalidatePaths([
                    '/', 
                    '/about' 
                ]);
    
                return response()->json([
                    'status' => true,
                    'message' => 'The Journey is ' . ($Journey->is_active ? 'activated' : 'deactivated') . ' successfully.',
                ]);
        }

        if ($request->has('getData')) {
            $start = 0;
            $length = 10;

            if (request()->has('start') && request('start') >= 0) {
                $start = intval(request('start'));
            }

            if (request()->has('length') && request('length') >= 5 && request('length') <= 100) {
                $length = intval(request('length'));
            }

            $data = Journeys::where('organization_id', $organization->id)
                ->orderBy('start_date', 'DESC');

            $count = $data->count();
            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn"          => $start + $i + 1,
                        "id"          => $item->id,
                        "title"       => $item->title,
                        "description" => $item->description,
                        "start_date"  => $item->start_date ? $item->start_date->format('Y-m-d') : 'N/A',
                        "end_date"    => $item->end_date ? $item->end_date->format('Y-m-d') : 'N/A',
                        "is_active"   => $item->is_active,
                        "created_by"  => $item->getCreatedBy?->name ?? 'N/A',
                        "created_at"  => $item->created_at,
                        "updated_at"  => $item->updated_at,
                    ];
                })->toArray(),
                "recordsFiltered" => $filtered_count,
                "recordsTotal"    => $count,
            ]);
        }

        return view('admin.journeys.index', compact('organization'));
    }

    public function create(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $this->revalidatePaths([
                    '/', 
                    '/about' 
                ]);

        return view('admin.journeys.create', compact('organization'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'media_id'    => 'nullable|exists:medias,id',
            'media_id_2'  => 'nullable|exists:medias,id',
            'is_active'   => 'sometimes|boolean',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['created_by']      = Auth::id();
        $validated['is_active']       = $request->has('is_active');

        Journeys::create($validated);

         $this->revalidatePaths([
                '/', 
                '/about' 
            ]);

        return redirect()
            ->route('journeys.index', $organization->slug)
            ->with('success', 'Journey created successfully!');
    }

    public function edit(Request $request, $id)
    {
        $journey = Journeys::with('media', 'media2')->findOrFail($id);
        $organization = Organization::findOrFail($journey->organization_id);

        return view('admin.journeys.edit', compact('organization', 'journey'));
    }

    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $journey = Journeys::where('organization_id', $organization->id)->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'media_id'    => 'nullable|exists:medias,id',
            'media_id_2'  => 'nullable|exists:medias,id',
            'is_active'   => 'sometimes|boolean',
        ]);

        $journey->update([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'media_id'    => $validated['media_id'] ?? null,
            'media_id_2'  => $validated['media_id_2'] ?? null,
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'] ?? null,
            'is_active'   => $request->boolean('is_active'),
            'updated_by'  => Auth::id(),
        ]);

     $this->revalidatePaths([
                '/', 
                '/about' 
            ]);

        $message = 'Journey updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('journeys.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $data = Journeys::find($id);
        $data->delete();

         $this->revalidatePaths([
                '/', 
                '/about' 
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Journey deleted successfully.',
        ]);
    }

    
}