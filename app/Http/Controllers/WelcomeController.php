<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;
use App\Models\Faq;
use App\Models\HomeCarousel;
use App\Models\Mission;
use App\Models\News;
use App\Models\Notice;
use App\Models\Service;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

        $sisterOrganizations = Organization::where('parent_organization_id', $organization->id)
            ->where('status', 'active')
            ->get();

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
            ->orderBy('published_date', 'desc')
            ->get();

        $news = $news->map(function ($item) {
            return [
                'id' => $item->id,
                'uuid' => $item->uuid,
                'title' => $item->title,
                'summary' => $item->summary,
                'image' => $item->media
                    ? $item->media->url
                    : null,
                'published_date' => $item->published_date,
            ];
        });

        $notices = Notice::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->orderBy('notice_date', 'desc')
            ->take(4)
            ->get();

        $notices = $notices->map(function ($item) {
            return [
                'id' => $item->id,
                'uuid' => $item->uuid,
                'title' => $item->title,
                'description' => $item->description,
                'priority' => $item->priority ?? 'low',
                'image' => $item->attachment
                    ? $item->attachment->url
                    : null,
                'notice_date' => $item->notice_date,
            ];
        });

        $messages = Message::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->where('is_home', true)
            ->take(1)
            ->get();

        $messages = $messages->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'content' => $item->content,
                'image' => $item->attachment
                    ? $item->attachment->url
                    : null,
                'staff_name' => $item->organizationStaff ? $item->organizationStaff->name : 'Unknown',
            ];
        });

        $faqs = Faq::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->take(3)
            ->get();
        $missions = Mission::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();

        $data = [
            'organization' => $organization,
            'faqs' => $faqs,
            'news' => $news,
            'sisterOrganizations' => $sisterOrganizations,
            'notices'=>$notices,
            'messages'=>$messages,
            'homecarousel' => $homecarousel,
            'missions'=>$missions,
        ];

        return view('guest.welcome', compact('data'));
    }
}
