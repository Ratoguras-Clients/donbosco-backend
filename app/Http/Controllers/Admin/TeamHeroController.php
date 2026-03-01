<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamHero;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamHeroController extends Controller
{
    use RevalidatesNextJs;
    // Decide whether to create or edit
    public function teamhero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $teamhero = TeamHero::where('organization_id', $organization->id)->first();

        if ($teamhero) {
            return redirect()->route('teamhero.edit', [
                'slug' => $slug,
                'id' => $teamhero->id
            ]);
        }

        return redirect()->route('teamhero.create', $slug);
    }


    // Show create form
    public function createteamhero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.teamhero.teamhero', compact('organization'));
    }


    // Store hero
    public function storeteamhero(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        TeamHero::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home', false),
            'created_by' => Auth::id(),
        ]);
        $this->revalidatePaths([
            '/about/team'
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', 'Team Hero created successfully.');
    }


    // Show edit form
    public function editteamhero($slug, $id)
    {
        $teamhero = TeamHero::findOrFail($id);

        $organization = Organization::findOrFail($teamhero->organization_id);

        return view('admin.teamhero.teamheroedit', compact('organization', 'teamhero'));
    }


    // Update hero
    public function updateteamhero(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $teamhero = TeamHero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
        ]);

        $teamhero->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Team Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }
        $this->revalidatePaths([
            '/about/team'
        ]);
        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
