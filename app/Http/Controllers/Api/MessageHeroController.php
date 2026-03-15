<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageHero;
use App\Models\Organization;

class MessageHeroController extends Controller
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

        $messagehero = MessageHero::where('organization_id', $organization_id)
            ->where('is_home', true)
            ->first();

        if (!$messagehero) {
            return response()->json(['data' => null, 'message' => 'Message Hero not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $messagehero->id,
                'title' => $messagehero->title,
                'content' => $messagehero->content,
                'is_home' => $messagehero->is_home,
            ],
        ]);
    }
}