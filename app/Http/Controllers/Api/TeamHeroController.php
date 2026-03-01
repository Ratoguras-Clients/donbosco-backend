<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamHero;
use App\Models\Organization;

class TeamHeroController extends Controller
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

        $teamhero = TeamHero::where('organization_id', $organization_id)->first();

        if (!$teamhero) {
            return response()->json(['data' => null, 'message' => 'Team Hero not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id'      => $teamhero->id,
                'title'   => $teamhero->title,
                'content' => $teamhero->content,
                'is_home' => $teamhero->is_home,
            ],
        ]);
    }
}