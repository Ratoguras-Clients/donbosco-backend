<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqHero;
use App\Models\Organization;

class FaqHeroController extends Controller
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

        $faqhero = FaqHero::where('organization_id', $organization_id)->where('is_home', true)
            ->first();

        if (!$faqhero) {
            return response()->json(['data' => null, 'message' => 'FAQ Hero not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $faqhero->id,
                'title' => $faqhero->title,
                'content' => $faqhero->content,
                'is_home' => $faqhero->is_home,
            ],
        ]);
    }
}
