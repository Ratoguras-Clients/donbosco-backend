<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;

use Auth;
use Illuminate\Http\Request;

class EventsController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        if ($request->has('status')) {
            $event = Events::find($request->input('id'));
            $event->is_published = !$event->is_published;
            $event->save();
            $this->revalidatePaths([
                '/about/events'
            ]);
            return response()->json([
                'status' => true,
                'message' => 'The Events is ' . ($event->is_published ? 'activated' : 'deactivated') . ' successfully.',
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

            $data = Events::where('organization_id', $organization->id)
                ->orderBy('id', 'DESC');

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "title" => $item->title,
                        "description" => $item->description,
                        "location" => $item->location,
                        "start_date" => $item->start_date ? $item->start_date->format('Y-m-d') : 'N/A',
                        "end_date" => $item->end_date ? $item->end_date->format('Y-m-d') : 'N/A',
                        "is_published" => $item->is_published,
                        "created_by" => $item->getCreatedBy?->name ?? 'N/A',
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                })->toArray(),
                "recordsFiltered" => $filtered_count,
                "recordsTotal" => $count
            ]);
        }

        return view('admin.events.index', compact('organization'));
    }
    public function create(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $this->revalidatePaths([
            '/about/events'
        ]);
        return view('admin.events.create', compact('organization'));
    }
    public function edit(Request $request, $id)
    {
        $event = Events::with('media')->findOrFail($id);

        $organization = Organization::findOrFail($event->organization_id);

        return view('admin.events.edit', compact('organization', 'event'));
    }
    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'media_id' => 'nullable|exists:medias,id',
            'is_published' => 'sometimes|boolean',
            'is_home' => 'sometimes|boolean',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['created_by'] = auth()->id();


        $validated['is_published'] = $request->has('is_published');
        $validated['is_home'] = $request->has('is_home');
        $validated['media_id'] = $request->input('media_id');

        Events::create($validated);
        $this->revalidatePaths([
            '/about/events'
        ]);
        return redirect()
            ->route('events.index', $organization->slug)
            ->with('success', 'Event created successfully!');
    }
    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $event = Events::where('organization_id', $organization->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'media_id' => 'nullable|integer',
            'is_published' => 'sometimes|boolean',
            'is_home' => 'sometimes|boolean',
        ]);



        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'media_id' => $validated['media_id'] ?? null,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'location' => $validated['location'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Events updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
        $this->revalidatePaths([
            '/about/events'
        ]);
        return redirect()
            ->route('events.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $data = Events::find($id);
        $data->delete();
        $this->revalidatePaths([
            '/about/events'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Events deleted successfully.',
        ]);
    }
}
