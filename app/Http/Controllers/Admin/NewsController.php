<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\RevalidatesNextJs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $news = News::where('organization_id', $organization->id)
                ->findOrFail($request->input('id'));

            $news->is_published = !$news->is_published;
            $news->save();
            $this->revalidatePaths([
                '/',
                'news-notices'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'The news is ' . ($news->is_published ? 'Published' : 'Unpublished') . ' successfully.',
            ]);
        }

        if ($request->ajax() && $request->has('getData')) {
            $query = News::with(['creator', 'updater', 'media'])
                ->where('organization_id', $organization->id)
                ->latest();

            $total = $query->count();

            $news = $query
                ->skip($request->start)
                ->take($request->length)
                ->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $news->map(function ($item, $index) use ($request) {
                    return [
                        'sn' => $request->start + $index + 1,
                        'id' => $item->id,
                        'title' => $item->title,
                        'content' => $item->content,
                        'summary' => $item->summary,
                        'image' => $item->media?->url,
                        'published_date' => $item->published_date?->format('Y-m-d'),
                        'is_published' => $item->is_published,
                        'is_home' => $item->is_home,
                        'created_by' => $item->creator?->name,
                        'updated_by' => $item->updater?->name,
                    ];
                }),
            ]);
        }

        return view('admin.news.index', compact('organization'));
    }

    public function create($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        return view('admin.news.create', compact('organization'));
    }

    public function edit($id)
    {
        $news = News::with('media')->findOrFail($id);
        $organization = Organization::findOrFail($news->organization_id);

        return view('admin.news.edit', compact('organization', 'news'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug',
            'summary' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'image' => 'nullable|integer',
            'published_date' => 'nullable|date',
            'is_published' => 'sometimes|boolean',
            'is_home' => 'sometimes|boolean',
        ]);

        $news = News::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? Str::slug($validated['title']),
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? null,
            'media_id' => $validated['image'] ?? null,
            'published_date' => $validated['published_date'] ?? null,
            'is_published' => $request->boolean('is_published', false),
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        return redirect()->route('news.index', $organization->slug)
            ->with('success', $news->title . ' created successfully.');
    }

    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $news = News::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug,' . $news->id,
            'summary' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'image' => 'nullable|integer',
            'published_date' => 'nullable|date',
            'is_published' => 'sometimes|boolean',
            'is_home' => 'sometimes|boolean',
        ]);

        $news->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $news->slug,
            'summary' => $validated['summary'] ?? null,
            'content' => $validated['content'] ?? null,
            'media_id' => $validated['image'] ?? null,
            'published_date' => $validated['published_date'] ?? $news->published_date,
            'is_published' => $request->boolean('is_published'),
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        $message = 'News updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('news.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $newsItem = News::where('organization_id', $organization->id)
            ->findOrFail($id);

        if ($newsItem->media) {
            $newsItem->media->delete();
        }

        $newsItem->delete();

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'News deleted successfully.',
            ]);
        }

        return redirect()->route('news.index', $organization->slug)
            ->with('success', 'News deleted successfully.');
    }


}