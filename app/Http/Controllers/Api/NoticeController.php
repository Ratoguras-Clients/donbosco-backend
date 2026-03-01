<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class NoticeController extends Controller
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

        $perPage = $request->query('per_page', 9);
        $page = $request->query('page', 1);

        $notices = Notice::where('organization_id', $organization_id)
            ->where('is_published', true)
            ->orderBy('notice_date', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($notices->currentPage() - 1) * $notices->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($notices->items())->map(function ($notice) {
                return [
                    'id' => $notice->id,
                    'title' => $notice->title,
                    'description' => $notice->description,
                    'attachment' => $notice->attachment
                        ? Storage::disk('public')->url($notice->attachment)
                        : null,
                    'priority' => $notice->priority,
                    'date' => $notice->notice_date
                        ? Carbon::parse($notice->notice_date)->format('j M, Y')
                        : null,
                    'slug' => $notice->slug,
                ];
            }),
            'total' => $notices->total(),
            'per_page' => $notices->perPage(),
            'current_page' => $notices->currentPage(),
            'last_page' => $notices->lastPage(),
            'start' => $start,
            'offset' => $offset,
            'count' => count($notices->items()),
        ]);
    }
}