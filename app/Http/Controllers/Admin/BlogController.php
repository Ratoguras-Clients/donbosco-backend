<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Auth;
use App\Traits\RevalidatesNextJs;

use App\Models\Organization;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        if ($request->has('status')) {
            $blog = Blog::find($request->input('id'));
            $blog->is_published = !$blog->is_published;
            $blog->save();

            $this->revalidatePaths([
            '/about/blog'
        ]);

            return response()->json([
                'status' => true,
                'message' => 'The blog is ' . ($blog->is_published? 'activated' : 'deactivated') . ' successfully.',
            ]);
        }
        if ($request->has('getData')) {
            $start = 0;
            $length = 10;

            if (request()->has('start')  >= 0) {
                $start = intval(request('start'));
            }

            if (request()->has('length') && request('length') >= 5 && request('length') <= 100) {
                $length = intval(request('length'));
            }

            $data = blog::where('organization_id', $organization->id)
                ->orderBy('id', 'DESC');

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "title" => $item->title,
                        "description" => $item->description,
                        "name" => $item->name,
                        "start_date" => $item->start_date ? $item->start_date->format('Y-m-d') : 'N/A',
                        "is_published" => $item->is_published,
                        "created_by" => $item->getCreatedBy?->name ?? 'N/A',
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                })->toArray(),
                "recordsFiltered" => $filtered_count,
                "recordsTotal" => $count
            ]);
        }

        return view('admin.blog.index', compact('organization'));
    }
    public function create(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.blog.create', compact('organization'));
    }
    public function edit(Request $request, $id)
    {
        $blog = blog::with('media')->findOrFail($id);

        $organization = Organization::findOrFail($blog->organization_id);

        return view('admin.blog.edit', compact('organization', 'blog'));
    }
    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'name' => 'required|string|max:255',
            'media_id' => 'required|exists:medias,id',
            'is_published' => 'sometimes|boolean',
            'is_home' => 'sometimes|boolean',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['created_by'] = auth()->id();


        $validated['is_published'] = $request->has('is_published');
        $validated['is_home'] = $request->has('is_home');
        $validated['media_id'] = $request->input('media_id');

        blog::create($validated);

        $this->revalidatePaths([
            '/about/blog'
        ]);

        return redirect()
            ->route('blog.index', $organization->slug)
            ->with('success', 'blog created successfully!');
    }
    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $blog = blog::where('organization_id', $organization->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'name' => 'nullable|string|max:255',
           'media_id' => 'nullable|integer',
            'is_published' => 'sometimes|boolean',
            'is_home' => 'sometimes|boolean',
        ]);

        

        $blog->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'media_id' => $validated['media_id'] ?? null,
            'start_date' => $validated['start_date'],
            'name' => $validated['name'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'blog updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $this->revalidatePaths([
            '/about/blog'
        ]);

        return redirect()
            ->route('blog.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $data = blog::find($id);
        $data->delete();

        $this->revalidatePaths([
            '/about/blog'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Blog deleted successfully.',
        ]);
    }
}
