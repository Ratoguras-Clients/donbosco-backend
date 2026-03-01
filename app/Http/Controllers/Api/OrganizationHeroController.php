<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Organizationhero;

class OrganizationHeroController extends Controller
{
    public function show($organization_id = null)
    {
        if (!$organization_id) {
            $organization = Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json(['data' => null, 'message' => 'Organization not found.'], 404);
            }
            $organization_id = $organization->id;
        }

        $hero = Organizationhero::where('organization_id', $organization_id)
            ->where('is_active', true)
            ->first();

        if (!$hero) {
            return response()->json(['data' => null, 'message' => 'Hero not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id'        => $hero->id,
                'title'     => $hero->title,
                'subtitle'  => $hero->subtitle,
                'content'   => $hero->content,
                'image'     => $hero->image,
                'is_active' => $hero->is_active,
            ],
        ]);
    }
}