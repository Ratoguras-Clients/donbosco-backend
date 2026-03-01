<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    public function index(Request $request, $organization_id = null)
    {
        if (!$organization_id) {
            $organization = \App\Models\Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'per_page' => 0,
                    'current_page' => 0,
                    'last_page' => 0,
                    'start' => 0,
                    'offset' => 0,
                ], 404);
            }
            $organization_id = $organization->id;
        }

        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        $faqs = Faq::where('organization_id', $organization_id)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($faqs->currentPage() - 1) * $faqs->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($faqs->items())->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    // 'image' => [$faq->image1, $faq->image2, $faq->image3],
                ];
            }),
            'total' => $faqs->total(),
            'per_page' => $faqs->perPage(),
            'current_page' => $faqs->currentPage(),
            'last_page' => $faqs->lastPage(),
            'start' => $start,
            'offset' => $offset,
            'count' => count($faqs->items()),
        ]);
    }
}
