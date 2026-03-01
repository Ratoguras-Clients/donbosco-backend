<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homemission;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomemissionController extends Controller
{
    use RevalidatesNextJs;
    public function create($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.mission.homemission', compact('organization'));
    }

    public function edit($slug, $id)
    {
        $homemission = Homemission::with(['media1', 'media2', 'media3'])->findOrFail($id);

        $organization = Organization::findOrFail($homemission->organization_id);

        return view('admin.mission.homemissionedit', compact('organization', 'homemission'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_1' => 'nullable|integer',
            'image_2' => 'nullable|integer',
            'image_3' => 'nullable|integer',
            'is_home' => 'sometimes|boolean',
        ]);

        $homemission = Homemission::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'media_id_1' => $validated['image_1'] ?? null,
            'media_id_2' => $validated['image_2'] ?? null,
            'media_id_3' => $validated['image_3'] ?? null,
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);


        $this->revalidatePaths([
            '/'
        ]);

        return redirect()->route('dashboard', $organization->slug)
            ->with('success', 'Home Mission created successfully.');
    }

    public function homemission($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $homemission = Homemission::where('organization_id', $organization->id)->first();

        if ($homemission) {
            return redirect()->route('homemission.edit', [
                'slug' => $slug,
                'id' => $homemission->id
            ]);
        }

        return redirect()->route('homemission.create', $slug);
    }

    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $homemission = Homemission::where('organization_id', $organization->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image_1' => 'nullable|integer',
            'image_2' => 'nullable|integer',
            'image_3' => 'nullable|integer',
            'is_home' => 'sometimes|boolean',
        ]);

        $homemission->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'media_id_1' => $validated['image_1'] ?? null,
            'media_id_2' => $validated['image_2'] ?? null,
            'media_id_3' => $validated['image_3'] ?? null,
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Home Mission updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $this->revalidatePaths([
            '/',
        ]);
        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}