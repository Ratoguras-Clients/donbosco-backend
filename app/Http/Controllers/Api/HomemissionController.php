<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Homemission;
use App\Models\Organization;

class HomemissionController extends Controller
{
    public function index($organization_id = null)
    {
        if (!$organization_id) {
            $organization = Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json(['data' => null, 'message' => 'Organization not found.'], 404);
            }
            $organization_id = $organization->id;
        }

        $homemission = Homemission::with(['media1', 'media2', 'media3'])
            ->where('organization_id', $organization_id)
            ->first();

        if (!$homemission) {
            return response()->json(['data' => null, 'message' => 'Home Mission not found.'], 404);
        }

        return response()->json([
            'data' => [
                'id'      => $homemission->id,
                'title'   => $homemission->title,
                'content' => $homemission->content,
                'image_1' => $homemission->media1?->url,
                'image_2' => $homemission->media2?->url,
                'image_3' => $homemission->media3?->url,
                'is_home' => $homemission->is_home,
            ],
        ]);
    }
}