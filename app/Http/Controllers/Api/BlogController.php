<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BlogController extends Controller
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

        $blogs = Blog::with('media')
            ->where('organization_id', $organization_id)
            ->where('is_published', true)
            ->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start  = ($blogs->currentPage() - 1) * $blogs->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($blogs->items())->map(function ($blog) {
                return [
                    'id'          => $blog->id,
                    'title'       => $blog->title,
                    'description' => $blog->description,
                    'name'        => $blog->name,
                    'image'       => $blog->media?->url,
                    'date'        => $blog->start_date ? Carbon::parse($blog->start_date)->format('j M, Y') : null,
                    'is_home'     => $blog->is_home,
                ];
            }),
            'total'        => $blogs->total(),
            'per_page'     => $blogs->perPage(),
            'current_page' => $blogs->currentPage(),
            'last_page'    => $blogs->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($blogs->items()),
        ]);
    }
}