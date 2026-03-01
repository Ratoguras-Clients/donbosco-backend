<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;

use App\Models\Organizationhero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationHeroController extends Controller
{
    use RevalidatesNextJs;

    // Decide create or edit automatically
    public function hero($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $hero = Organizationhero::where('organization_id', $organization->id)->first();

        if ($hero) {
            return redirect()->route('organizationhero.edit', [
                'slug' => $slug,
                'id' => $hero->id
            ]);
        }

        return redirect()->route('organizationhero.create', $slug);
    }


    // Show create page
    public function create($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.organizationhero.organizationhero', compact('organization'));
    }


    // Store hero
    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        Organizationhero::create([
            'organization_id' => $organization->id,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $validated['content'] ?? null,
            'image' => $validated['image'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id(),
        ]);
        $this->revalidatePaths([
            '/about/organizations'
        ]);
        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', 'Organization Hero created successfully.');
    }


    // Show edit page
    public function edit($slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $organizationhero = Organizationhero::where('organization_id', $organization->id)
            ->findOrFail($id);

        return view('admin.organizationhero.organizationheroedit', compact('organization', 'organizationhero'));
    }



    // Update hero
    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $hero = Organizationhero::where('organization_id', $organization->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $hero->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $validated['content'] ?? null,
            'image' => $validated['image'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Organization Hero updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

         $this->revalidatePaths([
            '/about/organizations'
        ]);

        return redirect()
            ->route('dashboard', $organization->slug)
            ->with('success', $message);
    }
}
