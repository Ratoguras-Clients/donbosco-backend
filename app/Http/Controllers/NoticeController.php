<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Organization;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
     public function index()
    {
        $organization = Organization::whereNull('parent_organization_id')
            ->where('status', 'active')
            ->firstOrFail();

        $notices = Notice::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->orderBy('notice_date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'slug' => $item->slug, // Changed from uuid to slug
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => optional($item->attachment)->url,
                    'notice_date' => $item->notice_date,
                ];
            });

       

        return view('guest.medias.notices', [
            'data' => [
                'organization' => $organization,
                'notices' => $notices,
                
            ]
        ]);
    }

    public function show(Notice $notice) // Changed parameter name from $notices to $notice (singular)
    {
        // Parent organization (unchanged)
        $organization = Organization::whereNull('parent_organization_id')
            ->where('status', 'active')
            ->firstOrFail();

        // $notice is already loaded by slug – no need for query
        // But keep your is_published check if you want extra safety
        if (!$notice->is_published) {
            abort(404);
        }

        $mainNotice = [
            'id' => $notice->id,
            'slug' => $notice->slug, // Changed from uuid to slug
            'title' => $notice->title,
            'description' => $notice->description,
            'image' => optional($notice->attachment)->url,
            'notice_date' => $notice->notice_date,
        ];

        // Decide target organizations (use $notice->organization_id now)
        if ($notice->organization_id === $organization->id) {
            $targetOrganizationIds = [$organization->id];
        } else {
            $targetOrganizationIds = Organization::where('parent_organization_id', $organization->id)
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();
        }

        // Other notices – exclude current record by id
        $otherNotice = Notice::with('media')
            ->whereIn('organization_id', $targetOrganizationIds)
            ->where('is_published', true)
            ->where('id', '!=', $notice->id) // Changed from $notices to $notice
            ->orderBy('notice_date', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'slug' => $item->slug, // Changed from uuid to slug
                    'title' => $item->title,
                    'description' => $item->description,
                    'image' => optional($item->attachment)->url,
                    'notice_date' => $item->notice_date,
                ];
            });

        return view('guest.medias.notices_show', [
            'organization' => $organization,
            'notices' => $mainNotice,
            'otherNotice' => $otherNotice,
        ]);
    }
}