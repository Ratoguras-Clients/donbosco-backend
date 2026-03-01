<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NewController extends Controller
{
    public function index(Request $request, $organization_id = null)
    {
        if (!$organization_id) {
            $organization = Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'per_page' => 0,
                    'current_page' => 0,
                    'last_page' => 0,
                    'start' => 0,
                    'offset' => 0,
                ], 404);
            }
            $organization_id = $organization->id;
        }

        $perPage = $request->query('per_page', 9);
        $page = $request->query('page', 1);

        $news = News::with('media')
            ->where('organization_id', $organization_id)
            ->where('is_published', true)
            ->when($request->query('is_home'), fn($q) => $q->where('is_home', true))
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($news->currentPage() - 1) * $news->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($news->items())->map(function ($new) {
                return [
                    'id' => $new->id,
                    'title' => $new->title,
                    'summary' => $new->summary,
                    'content' => $new->content,
                    'image' => $new->media?->url,
                    'date' => $new->published_date ? Carbon::parse($new->published_date)->format('j M, Y') : null,
                    'slug' => $new->slug,
                ];
            }),
            'total' => $news->total(),
            'per_page' => $news->perPage(),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'start' => $start,
            'offset' => $offset,
            'count' => count($news->items()),
        ]);
    }
}