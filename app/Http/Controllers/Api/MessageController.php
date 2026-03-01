<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageController extends Controller
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

        $perPage = $request->query('per_page', 8);
        $page = $request->query('page', 1);

        $messages = Message::with(['organizationStaff.media', 'organizationStaff.organizationStaffRole.staffRole'])
            ->where('organization_id', $organization_id)
            ->orderBy('id', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);

        $start = ($messages->currentPage() - 1) * $messages->perPage() + 1;
        $offset = $start - 1;

        return response()->json([
            'data' => collect($messages->items())->map(function ($message) {
                return [
                    'id'          => $message->id,
                    'title'       => $message->title,
                    'content'     => $message->content,
                    'staff_name'  => $message->organizationStaff?->name ?? null,
                    'designation' => $message->organizationStaff?->organizationStaffRole?->staffRole?->name ?? null,
                    'image'       => $message->organizationStaff?->media?->url ?? null,
                    'date'        => $message->date ? Carbon::parse($message->date)->format('F j, Y') : null,
                    'tenure'      => $message->tenure,
                ];
            }),
            'total'        => $messages->total(),
            'per_page'     => $messages->perPage(),
            'current_page' => $messages->currentPage(),
            'last_page'    => $messages->lastPage(),
            'start'        => $start,
            'offset'       => $offset,
            'count'        => count($messages->items()),
        ]);
    }
}