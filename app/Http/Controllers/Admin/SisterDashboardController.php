<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class SisterDashboardController extends Controller
{
    public function index(Request $request, $sisterSlug)
    {
        $parentOrganization = Organization::whereNull('parent_organization_id')->first();

        if ($parentOrganization === null) {
            abort(404, 'Parent organization not found.');
        }

        $sisterOrganizations = Organization::where('parent_organization_id', $parentOrganization->id)->get();
        $sister = true;
        $currentOrganization = Organization::where('slug', $sisterSlug)->first();
        return view('dashboard', compact('parentOrganization', 'sisterOrganizations', 'sister', 'sisterSlug', 'currentOrganization'));
    }
}
