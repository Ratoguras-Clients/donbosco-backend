<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Message;
use App\Models\News;
use App\Models\Notice;
use App\Models\Organization;
use App\Models\Journeys;
use App\Models\Events;
use App\Models\Collection;
use App\Models\HomeCarousel;
use Illuminate\Http\Request;

class SisterOrganizationController extends Controller
{
    public function index($slug)
    {
        $organization = Organization::with('media')
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();


        $homecarousel = HomeCarousel::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->latest('order_index')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'image' => optional($item->media)->url,
                    'created_at' => $item->created_at,
                ];
            });


        $news = News::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->latest('published_date')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'uuid' => $item->uuid,
                    'title' => $item->title,
                    'summary' => $item->summary,
                    'image' => optional($item->media)->url,
                    'published_date' => $item->published_date,
                ];
            });


        $notices = Notice::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->latest('notice_date')
            ->take(4)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'priority' => $item->priority ?? 'low',
                    'image' => optional($item->attachment)->url,
                    'notice_date' => $item->notice_date,
                ];
            });

        // Active home messages
        $messages = Message::with(['media', 'organizationStaff'])
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->where('is_home', true)
            ->take(1)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->content,
                    'image' => optional($item->attachment)->url,
                    'staff_name' => optional($item->organizationStaff)->name ?? 'Unknown',
                ];
            });

        // FAQs
        $faqs = Faq::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->take(3)
            ->get();

        $journeys = Journeys::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index', 'desc')
            ->get();

        $journeys = $journeys->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'start_date' => $item->start_date,
            ];
        });
        //events
        $events = Events::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->get();

        $events = $events->map(function ($item) {
            return [
                'id' => $item->id,
                'summary' => $item->description,
                'image' => $item->media ? $item->media->url : null,
                'start_date' => $item->start_date,
            ];
        });

        //collections

        $collections = Collection::with('coverMedia')
            ->where('organization_id', $organization->id)
            ->orderBy('created_at', 'asc')
            ->get();

        $collections = $collections->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'cover_image' => $item->cover_image ? $item->coverMedia->url : null,
                'created_at' => $item->created_at->format('Y-m-d'),
            ];
        });

        // Pass everything to the view
        $data = [
            'organization' => $organization,
            'news' => $news,
            'notices' => $notices,
            'messages' => $messages,
            'faqs' => $faqs,
            'journeys' => $journeys,
            'collections' => $collections,
            'events' => $events,
            'homecarousel' => $homecarousel,

        ];

        return view('guest.sister_organization_show', compact('data'));
    }

    public function show()
    {
        $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();


        $sisterOrganizations = Organization::where('parent_organization_id', $organization->id)
            ->where('status', 'active')
            ->get();

        $data = [
            'organization' => $organization,
            'sisterOrganizations' => $sisterOrganizations,

        ];

        // Pass it to the view
        return view('guest.medias.sister_organization', compact('data'));
    }

    public function SisterNews($slug)
    {
        // 1. Get the main organization by slug
        $organization = Organization::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // 2. Get all sister organizations
        $sisterOrganizationIds = Organization::where('parent_organization_id', $organization->id)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        // Include main organization as well
        $organizationIds = array_merge([$organization->id], $sisterOrganizationIds);

        // 3. Fetch all news from main + sister organizations
        $news = News::with('media')
            ->whereIn('organization_id', $organizationIds)
            ->where('is_published', true)
            ->latest('published_date')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'uuid' => $item->uuid,
                    'title' => $item->title,
                    'summary' => $item->summary,
                    'image' => optional($item->media)->url,
                    'published_date' => $item->published_date,
                ];
            });

        
        // 5. Pass only news and notices to the view
        $data = [
            'organization' => $organization,
            'news' => $news,
           
        ];

        return view('guest.medias.news', compact('data'));
    }
    public function faqs($slug)
    {
        $organization = Organization::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // 2. Get all sister organizations
        $sisterOrganizationIds = Organization::where('parent_organization_id', $organization->id)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        // Include main organization as well
        $organizationIds = array_merge([$organization->id], $sisterOrganizationIds);

        // 3. Fetch FAQs from main + sister organizations
        $faqs = Faq::whereIn('organization_id', $organizationIds)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();


        // 4. Prepare data array for view
        $data = [
            'organization' => $organization,
            'faqs' => $faqs,
        ];

        // 5. Return view with FAQs
        return view('guest.faq', compact('data'));
    }
}
