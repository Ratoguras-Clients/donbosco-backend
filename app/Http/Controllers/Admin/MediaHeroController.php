<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaHero;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaHeroController extends Controller
{
    use RevalidatesNextJs;

    // Decide create or edit automatically (1 hero per organization)
    public function mediahero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $mediahero = MediaHero::where('organization_id', $organization->id)->first();

        if ($mediahero) {
            return redirect()->route('mediahero.edit', [
                'slug' => $slug,
                'id'   => $mediahero->id,
            ]);
        }

        return redirect()->route('mediahero.create', $slug);
    }

    public function createmediahero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.mediahero.mediahero', compact('organization'));
    }

    public function storemediahero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        MediaHero::create([
            'organization_id' => $organization->id,
            'title'           => $validated['title'],
            'content'         => $validated['content'] ?? null,
            'is_home'         => $request->boolean('is_home', false),
            'created_by'      => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/media',
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', 'Media Hero created successfully.');
    }

    public function editmediahero($slug, $id)
    {
        $mediahero = MediaHero::findOrFail($id);
        $organization = Organization::findOrFail($mediahero->organization_id);

        return view('admin.mediahero.mediaheroedit', compact('organization', 'mediahero'));
    }

    public function updatemediahero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $mediahero = MediaHero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $mediahero->update([
            'title'      => $validated['title'],
            'content'    => $validated['content'] ?? null,
            'is_home'    => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $this->revalidatePaths([
            '/media',
        ]);

        $message = 'Media Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
