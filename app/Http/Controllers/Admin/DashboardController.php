<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, $slug = null)
    {
        if ($slug) {
            $activeOrganization = Organization::where('slug', $slug)->firstOrFail();
        } else {
            $activeOrganization = Organization::whereNull('parent_organization_id')->firstOrFail();
        }

        // Determine parent correctly
        $parentOrganization = $activeOrganization->parentOrganization
            ?? $activeOrganization;

        // Determine sister correctly (THIS IS THE KEY FIX)
        $sister = $activeOrganization->parent_organization_id !== null;

        $sisterOrganizations = Organization::where(
            'parent_organization_id',
            $parentOrganization->id
        )->get();

        return view('admin.dashboard', compact(
            'parentOrganization',
            'activeOrganization',
            'sisterOrganizations',
            'sister'
        ));
    }
}
