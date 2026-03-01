<?php

namespace App\Http\Controllers;

use App\Models\Events;
use App\Models\Organization;
use App\Models\OrganizationStaff;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

        $events = Events::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        $events = $events->map(function ($item) {
            return [
                'id' => $item->id,
                'summary' => $item->description,
                'location'=>$item->location,
                'image' => $item->media ? $item->media->url : null,
                'start_date' => $item->start_date,
            ];
        });

        return view('guest.medias.events', compact('events'));
    }
}
