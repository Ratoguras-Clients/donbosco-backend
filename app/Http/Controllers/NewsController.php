<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Notice;
use App\Models\Organization;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $organization = Organization::whereNull('parent_organization_id')
            ->where('status', 'active')
            ->firstOrFail();

        $news = News::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->orderBy('published_date', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'slug' => $item->slug, // Changed from uuid to slug
                    'title' => $item->title,
                    'summary' => $item->summary,
                    'image' => optional($item->media)->url,
                    'published_date' => $item->published_date,
                ];
            });

       

        return view('guest.medias.news', [
            'data' => [
                'organization' => $organization,
                'news' => $news,
                
            ]
        ]);
    }

    public function show(News $news)   
    {
        // Parent organization (unchanged)
        $organization = Organization::whereNull('parent_organization_id')
            ->where('status', 'active')
            ->firstOrFail();

        // $news is already loaded by slug – no need for query
        // But keep your is_published check if you want extra safety
        if (!$news->is_published) {
            abort(404);
        }

        $mainNews = [
            'id' => $news->id,
            'slug' => $news->slug, // Changed from uuid to slug
            'title' => $news->title,
            'summary' => $news->summary,
            'content' => $news->content,
            'image' => optional($news->media)->url,
            'published_date' => $news->published_date,
        ];

        // Decide target organizations (use $news->organization_id now)
        if ($news->organization_id === $organization->id) {
            $targetOrganizationIds = [$organization->id];
        } else {
            $targetOrganizationIds = Organization::where('parent_organization_id', $organization->id)
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();
        }

        // Other news – exclude current record by id
        $otherNews = News::with('media')
            ->whereIn('organization_id', $targetOrganizationIds)
            ->where('is_published', true)
            ->where('id', '!=', $news->id)
            ->orderBy('published_date', 'desc')
            ->take(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'slug' => $item->slug, // Changed from uuid to slug
                    'title' => $item->title,
                    'summary' => $item->summary,
                    'image' => optional($item->media)->url,
                    'published_date' => $item->published_date,
                ];
            });

        return view('guest.medias.news_show', [
            'organization' => $organization,
            'news' => $mainNews,
            'otherNews' => $otherNews,
        ]);
    }

}