<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class EventsController extends Controller
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

        $perPage = $request->query('per_page', 9);
        $page    = $request->query('page', 1);

        $events = Events::with('media')
            ->where('organization_id', $organization_id)
            ->where('is_published', true)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start  = ($events->currentPage() - 1) * $events->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($events->items())->map(function ($event) {
                return [
                    'id'          => $event->id,
                    'title'       => $event->title,
                    'description' => $event->description,
                    'location'    => $event->location,
                    'image'       => $event->media?->url,
                    'start_date'  => $event->start_date ? Carbon::parse($event->start_date)->format('j M, Y') : null,
                    'end_date'    => $event->end_date ? Carbon::parse($event->end_date)->format('j M, Y') : null,
                    'is_home'     => $event->is_home,
                ];
            }),
            'total'        => $events->total(),
            'per_page'     => $events->perPage(),
            'current_page' => $events->currentPage(),
            'last_page'    => $events->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($events->items()),
        ]);
    }
}