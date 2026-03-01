<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SisterHero;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SisterHeroController extends Controller
{
    // Decide create or edit automatically
    public function sisterhero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $sisterhero = SisterHero::where('organization_id', $organization->id)->first();

        if ($sisterhero) {
            return redirect()->route('sisterhero.edit', [
                'slug' => $slug,
                'id'   => $sisterhero->id
            ]);
        }

        return redirect()->route('sisterhero.create', $slug);
    }


    // Show create form
    public function createsisterhero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.sisterhero.sisterhero', compact('organization'));
    }


    // Store hero
    public function storesisterhero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        SisterHero::create([
            'organization_id' => $organization->id,
            'title'           => $validated['title'],
            'content'         => $validated['content'] ?? null,
            'is_home'         => $request->boolean('is_home', false),
            'created_by'      => Auth::id(),
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', 'Sister Hero created successfully.');
    }


    // Show edit form
    public function editsisterhero($slug, $id)
    {
        $sisterhero = SisterHero::findOrFail($id);

        $organization = Organization::findOrFail($sisterhero->organization_id);

        return view('admin.sisterhero.sisterheroedit', compact('organization', 'sisterhero'));
    }


    // Update hero
    public function updatesisterhero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $sisterhero = SisterHero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $sisterhero->update([
            'title'      => $validated['title'],
            'content'    => $validated['content'] ?? null,
            'is_home'    => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Sister Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
