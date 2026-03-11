<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherOrganization;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OtherOrganizationController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $otherOrganization = OtherOrganization::find($request->input('id'));
            $otherOrganization->status = $otherOrganization->status === 'active' ? 'inactive' : 'active';
            $otherOrganization->save();

             $this->revalidatePaths([
            '/organizations/other'
        ]);

            return response()->json([
                'status' => true,
                'message' => 'The organization is ' . ($otherOrganization->status === 'active' ? 'Active' : 'Inactive') . ' successfully.',
            ]);
        }

        if ($request->ajax() && $request->has('getData')) {

            $query = OtherOrganization::with(['creator', 'updater', 'imageMedia'])
                ->where('organization_id', $organization->id)
                ->latest();

            $total = $query->count();

            $otherOrganizations = $query
                ->skip($request->start)
                ->take($request->length)
                ->get();

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $otherOrganizations->map(function ($item, $index) use ($request) {
                    return [
                        'sn' => $request->start + $index + 1,
                        'id' => $item->id,
                        'slug' => $item->slug,
                        'name' => $item->name,
                        'short_name' => $item->short_name,
                        'mission' => \Str::limit(strip_tags($item->mission), 50),
                        'description' => \Str::limit(strip_tags($item->description), 50),
                        'image' => $item->imageMedia?->url,
                        'url' => $item->url,
                        'established_date' => $item->established_date?->format('Y-m-d'),
                        'status' => $item->status,
                        'created_by' => $item->creator?->name,
                        'updated_by' => $item->updater?->name,
                    ];
                }),
            ]);
        }

        return view('admin.otherorganization.index', compact('organization'));
    }

    public function create($slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.otherorganization.create', compact('organization'));
    }

    public function edit(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $otherOrganization = OtherOrganization::where('id', $id)
            ->where('organization_id', $organization->id)
            ->with('imageMedia')
            ->firstOrFail();

        return view('admin.otherorganization.edit', compact('organization', 'otherOrganization'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:other_organizations,slug',
            'short_name' => 'nullable|string|max:255',
            'mission' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|integer',
            'url' => 'nullable|url|max:255',
            'established_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
        ]);

        $otherOrganization = OtherOrganization::create([
            'organization_id' => $organization->id,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'short_name' => $validated['short_name'] ?? null,
            'mission' => $validated['mission'] ?? null,
            'description' => $validated['description'] ?? null,
            'logo' => $validated['image'] ?? null,
            'url' => $validated['url'] ?? null,
            'established_date' => $validated['established_date'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'created_by' => Auth::id(),
        ]);

         $this->revalidatePaths([
            '/organizations/other'
        ]);

        return redirect()->route('otherorganizations.index', $organization->slug)
            ->with('success', $otherOrganization->name . ' created successfully.');
    }

    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $otherOrganization = OtherOrganization::where('id', $id)
            ->where('organization_id', $organization->id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:other_organizations,slug,' . $otherOrganization->id,
            'short_name' => 'nullable|string|max:255',
            'mission' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|integer',
            'url' => 'nullable|url|max:255',
            'established_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive',
        ]);

        $otherOrganization->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? $otherOrganization->slug,
            'short_name' => $validated['short_name'] ?? null,
            'mission' => $validated['mission'] ?? null,
            'description' => $validated['description'] ?? null,
            'logo' => $validated['image'] ?? $otherOrganization->logo,
            'url' => $validated['url'] ?? null,
            'established_date' => $validated['established_date'] ?? $otherOrganization->established_date,
            'status' => $validated['status'] ?? $otherOrganization->status,
            'updated_by' => Auth::id(),
        ]);

        $message = 'Organization updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

         $this->revalidatePaths([
            '/organizations/other'
        ]);

        return redirect()
            ->route('otherorganizations.index', $organization->slug)
            ->with('success', $message);
    }

    public function destroy(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $otherOrganization = OtherOrganization::where('id', $id)
            ->where('organization_id', $organization->id)
            ->firstOrFail();

        if ($otherOrganization->imageMedia) {
            $otherOrganization->imageMedia->delete();
        }

        $otherOrganization->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Organization deleted successfully.'
            ]);
        }

         $this->revalidatePaths([
            '/organizations/other'
        ]);

        return redirect()
            ->route('otherorganizations.index', $organization->slug)
            ->with('success', 'Organization deleted successfully.');
    }
}
