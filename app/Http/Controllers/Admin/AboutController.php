<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abouthero;
use App\Models\Aboutstory;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{
    // ==================== ABOUT HERO METHODS ====================
    use RevalidatesNextJs;

    public function abouthero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $abouthero = Abouthero::where('organization_id', $organization->id)->first();

        if ($abouthero) {
            return redirect()->route('abouthero.edit', [
                'slug' => $slug,
                'id' => $abouthero->id,
            ]);
        }

        return redirect()->route('abouthero.create', $slug);
    }

    public function createAbouthero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.about.abouthero', compact('organization'));
    }

    public function storeAbouthero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        Abouthero::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            '/about'
        ]);

        return redirect()->route('dashboard', $organization->slug)
            ->with('success', 'About Hero created successfully.');
    }

    public function editAbouthero($slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $abouthero = Abouthero::where('organization_id', $organization->id)
            ->findOrFail($id);

        return view('admin.about.aboutheroedit', compact('organization', 'abouthero'));
    }

    public function updateAbouthero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $abouthero = Abouthero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $abouthero->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            '/about'
        ]);

        $message = 'About Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('dashboard', $organization->slug)
            ->with('success', $message);
    }

    // ==================== ABOUT STORY METHODS ====================

    public function aboutstory($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $aboutstory = Aboutstory::where('organization_id', $organization->id)->first();

        if ($aboutstory) {
            return redirect()->route('aboutstory.edit', [
                'slug' => $slug,
                'id' => $aboutstory->id,
            ]);
        }

        return redirect()->route('aboutstory.create', $slug);
    }

    public function createAboutstory($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.about.aboutstory', compact('organization'));
    }

    public function storeAboutstory(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'features' => 'nullable|string',
            'image_1' => 'nullable|integer',
            'image_2' => 'nullable|integer',
            'image_3' => 'nullable|integer',
            'is_home' => 'sometimes|boolean',
        ]);

        Aboutstory::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'features' => $validated['features'] ?? null,
            'media_id_1' => $validated['image_1'] ?? null,
            'media_id_2' => $validated['image_2'] ?? null,
            'media_id_3' => $validated['image_3'] ?? null,
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            '/about'
        ]);

        return redirect()->route('dashboard', $organization->slug)
            ->with('success', 'Our Story created successfully.');
    }

    public function editAboutstory($slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $aboutstory = Aboutstory::with(['media1', 'media2', 'media3'])
            ->where('organization_id', $organization->id)
            ->findOrFail($id);

        return view('admin.about.aboutstoryedit', compact('organization', 'aboutstory'));
    }

    public function updateAboutstory(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $aboutstory = Aboutstory::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'features' => 'nullable|string',
            'image_1' => 'nullable|integer',
            'image_2' => 'nullable|integer',
            'image_3' => 'nullable|integer',
            'is_home' => 'sometimes|boolean',
        ]);

        $aboutstory->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'features' => $validated['features'] ?? null,
            'media_id_1' => $validated['image_1'] ?? null,
            'media_id_2' => $validated['image_2'] ?? null,
            'media_id_3' => $validated['image_3'] ?? null,
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/',
            '/about'
        ]);

        $message = 'Our Story updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}