<?php

namespace App\Http\Controllers;

use App\Models\Journeys;
use App\Models\Notice;
use App\Models\Organization;
use App\Models\Service;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {

        $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

        //    $notices = Notice::with('media')
        //         ->where('organization_id', $organization->id)
        //         ->where('is_published', true)
        //         ->orderBy('notice_date', 'desc')
        //         ->take(6)
        //         ->get();

        //     $notices = $notices->map(function ($item) {
        //         return [
        //             'id' => $item->id,
        //             'title' => $item->title,
        //             'description' => $item->description,
        //             'priority' => $item->priority ?? 'low',
        //             'image' => $item->attachment
        //                 ? $item->attachment->url
        //                 : null,
        //             'notice_date' => $item->notice_date,
        //         ];
        //     });

        $journeys = Journeys::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index', 'asc')
            ->get();

        $journeys = $journeys->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'start_date' => $item->start_date,
            ];
        });
        $services = Service::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index', 'desc')
            ->get();

        $services = $services->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'icon' => $item->icon,
            ];
        });




        $data = [


            'journeys' => $journeys,
            'services'=>$services,


        ];


        return view('guest.about', compact('data'));
    }
}
