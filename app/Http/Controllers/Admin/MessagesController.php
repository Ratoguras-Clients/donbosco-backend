<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Traits\RevalidatesNextJs;
use App\Models\Organization;
use App\Models\OrganizationStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $message = Message::find($request->input('id'));
            $message->is_active = !$message->is_active;
            $message->save();

            $this->revalidatePaths([
                '/',
                '/messages'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'The Message is ' . ($message->is_active ? 'activated' : 'deactivated') . ' successfully.',
            ]);
        }
        if ($request->has('getData')) {
            $start = 0;
            $length = 10;

            if (request()->has('start') && request('start') >= 0) {
                $start = intval(request('start'));
            }

            if (request()->has('length') && request('length') >= 5 && request('length') <= 100) {
                $length = intval(request('length'));
            }

            $data = Message::where('organization_id', $organization->id)
                ->orderBy('id', 'DESC');

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "title" => $item->title,
                        "content" => $item->content ?: '—',
                        "date" => $item->date,
                        "tenure" => $item->tenure,
                        "is_active" => $item->is_active,
                        "is_home" => $item->is_home,
                        "created_by" => $item->getCreatedBy?->name ?? 'N/A',
                        "updated_by" => $item->getUpdatedBy?->name ?? 'N/A',
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                })->toArray(),
                "recordsFiltered" => $filtered_count,
                "recordsTotal" => $count
            ]);
        }

        return view('admin.messages.index', compact('organization'));
    }
    public function create(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        return view('admin.messages.create', compact('organization'));
    }
    public function getOrganizationStaff(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $staff = \DB::table('organization_staff')
            ->join('organization_staff_roles', 'organization_staff_roles.organization_staff_id', '=', 'organization_staff.id')
            ->join('staff_roles', 'staff_roles.id', '=', 'organization_staff_roles.staff_role_id')
            ->where('organization_staff.organization_id', $organization->id)
            ->where('staff_roles.is_messageable', 1)
            ->select('organization_staff.id', 'organization_staff.name')
            ->when($request->search, function ($q, $search) {
                $q->where('organization_staff.name', 'like', "%{$search}%");
            })
            ->distinct()
            ->orderBy('organization_staff.name')
            ->get();

        return response()->json($staff);
    }


    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'date' => 'nullable|date',
            'tenure' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'organization_staff_id' => 'required|exists:organization_staff,id',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['organization_staff_id'] = $request->organization_staff_id;
        $validated['created_by'] = auth()->id();


        $validated['is_home'] = $request->has('is_home');
        $validated['is_active'] = $request->has('is_active');

        Message::create($validated);

        $this->revalidatePaths([
            '/',
            '/messages'
        ]);

        return redirect()
            ->route('messages.index', $organization->slug)
            ->with('success', 'Message created successfully!');
    }
    public function edit(Request $request, $id)
    {
        $message = Message::with('organization')->findOrFail($id);

        $organization = Organization::findOrFail($message->organization_id);

        return view('admin.messages.edit', compact('organization', 'message'));
    }

    public function update(Request $request, $slug, $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $message = Message::where('organization_id', $organization->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'date' => 'nullable|date',
            'tenure' => 'nullable|string',
            'is_home' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ]);

        $mediaId = $message->media_id;

        // Replace image if new one uploaded
        if ($request->hasFile('image')) {
            // Delete old media if exists
            if ($message->media) {
                $message->media->delete();
            }

            $media = $organization->addMediaFromRequest('image')
                ->toMediaCollection('news');

            $mediaId = $media->id;
        }

        $message->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'date' => $validated['date'] ?? null,
            'tenure' => $validated['tenure'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'is_home' => $request->boolean('is_home'),
            'updated_by' => Auth::id(),
        ]);

        $message = 'Message updated successfully.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        }

        $this->revalidatePaths([
            '/',
            '/messages'
        ]);

        return redirect()
            ->route('messages.index', $organization->slug)
            ->with('success', $message);
    }
    public function destroy($id)
    {
        $data = Message::find($id);
        $data->delete();
        $this->revalidatePaths([
            '/',
            '/messages'
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Message deleted successfully.',
        ]);
    }
}
