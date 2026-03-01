<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Database\QueryException;
use App\Traits\RevalidatesNextJs;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrganizationController extends Controller
{
    use RevalidatesNextJs;
    private function getParentOrgranization()
    {
        $parentOrganization = Organization::whereNull('parent_organization_id')->first();
        return $parentOrganization->id;
    }

    public function create(Request $request)
    {
        return view('admin.organization.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:organizations,name',
            'short_name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'established_date' => 'nullable|date',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['logo'] = $request->input('image_1');
        $data['image'] = $request->input('image_2');

        $data['parent_organization_id'] = $this->getParentOrgranization();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        Organization::create($data);
        
        $this->revalidatePaths([
            '/',
            '/organizations'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Organization created successfully.');
    }
    public function edit(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        return view('admin.organization.edit', compact('organization'));
    }

    public function update(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organizations', 'name')->ignore($organization->id),
            ],
            'short_name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'established_date' => 'nullable|date',
        ]);

        $validated['logo'] = $request->image_1;
        $validated['image'] = $request->image_2;
        $validated['parent_organization_id'] = $this->getParentOrgranization();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $organization->update($validated);
        $this->revalidatePaths([
            '/',
            '/organizations'
        ]);

        return redirect()->route('organization.dashboard', $organization->slug)
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Optional but recommended
        // $this->authorize('delete', $organization);

        try {
            $organization->delete();

            return response()->json([
                'success' => true,
                'message' => 'Organization deleted successfully.'
            ]);
        } catch (QueryException $e) {
            $message = 'Database error occurred while deleting the organization.';

            // Most common foreign key violation (MySQL / MariaDB / PostgreSQL)
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'foreign key constraint')) {
                $message = 'Cannot delete this organization because it still has associated records (projects, users, teams, invoices, etc.). Please remove or reassign related items first.';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], 422); // or 400 / 409 — 422 is common for unprocessable entity

        } catch (\Exception $e) {
            \Log::error('Organization deletion failed', [
                'slug' => $slug,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->revalidatePaths([
                '/',
                '/organizations'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again or contact support.'
            ], 500);
        }
    }
}
