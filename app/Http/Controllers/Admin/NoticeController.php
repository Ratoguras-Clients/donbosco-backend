<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NoticeController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $notice = Notice::where('organization_id', $organization->id)
                ->findOrFail($request->input('id'));

            $notice->is_published = !$notice->is_published;
            $notice->save();

            $this->revalidatePaths([
                '/',
                'news-notices'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'The Notice is ' . ($notice->is_published ? 'published' : 'unpublished') . ' successfully.',
            ]);
        }

        if ($request->ajax() && $request->has('getData')) {
            $query = Notice::with(['creator', 'media'])
                ->where('organization_id', $organization->id)
                ->latest();

            $total = $query->count();

            $notices = $query
                ->skip($request->start)
                ->take($request->length)
                ->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $notices->map(function ($item, $index) use ($request) {
                    return [
                        'sn' => $request->start + $index + 1,
                        'id' => $item->id,
                        'slug' => $item->slug,
                        'title' => $item->title,
                        'description' => Str::limit(strip_tags($item->description), 50),
                        'attachment' => $item->media?->url,
                        'notice_date' => $item->notice_date?->format('Y-m-d'),
                        'is_published' => $item->is_published,
                        'created_by' => $item->creator?->name,
                        'created_at' => $item->created_at->format('Y-m-d'),
                    ];
                }),
            ]);
        }

        return view('admin.notices.index', compact('organization'));
    }

    public function create($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        return view('admin.notices.create', compact('organization'));
    }

    public function edit($slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $notice = Notice::where('organization_id', $organization->id)
            ->findOrFail($id);

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        return view('admin.notices.edit', compact('organization', 'notice'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:notices,slug',
            'description' => 'nullable|string',
            'image' => 'required|integer',
            'notice_date' => 'nullable|date',
            'priority' => 'nullable|string',
            'is_published' => 'sometimes|boolean',
        ]);

        

        $notice = Notice::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? Str::slug($validated['title']),
            'description' => $validated['description'] ?? null,
            'attachment' => $validated['image'] ?? null,
            'notice_date' => $validated['notice_date'] ?? null,
            'priority' => $validated['priority'] ?? 'low',
            'is_published' => $request->boolean('is_published', false),
            'created_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        return redirect()
            ->route('notices.index', $organization->slug)
            ->with('success', $notice->title . ' created successfully.');
    }

    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $notice = Notice::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:notices,slug,' . $notice->id,
            'description' => 'nullable|string',
            'image' => 'nullable|integer',
            'notice_date' => 'nullable|date',
            'priority' => 'nullable|string',
            'is_published' => 'sometimes|boolean',
        ]);

        

        $notice->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? $notice->slug,
            'description' => $validated['description'] ?? null,
            'attachment' => $validated['image'] ?? $notice->attachment,
            'notice_date' => $validated['notice_date'] ?? $notice->notice_date,
            'priority' => $validated['priority'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'updated_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        $message = 'Notice updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('notices.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $notice = Notice::where('organization_id', $organization->id)
            ->findOrFail($id);

        if ($notice->attachment && Storage::disk('public')->exists($notice->attachment)) {
            Storage::disk('public')->delete($notice->attachment);
        }

        $notice->delete();

        $this->revalidatePaths([
            '/',
            'news-notices'
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Notice deleted successfully.',
            ]);
        }

        return redirect()
            ->route('notices.index', $organization->slug)
            ->with('success', 'Notice deleted successfully.');
    }

}