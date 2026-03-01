<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MediaHero;
use App\Models\Organization;

class MediaHeroController extends Controller
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

        $mediahero = MediaHero::where('organization_id', $organization_id)->first();

        if (!$mediahero) {
            return response()->json(['data' => null, 'message' => 'Media Hero not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id'      => $mediahero->id,
                'title'   => $mediahero->title,
                'content' => $mediahero->content,
                'is_home' => $mediahero->is_home,
            ],
        ]);
    }
}