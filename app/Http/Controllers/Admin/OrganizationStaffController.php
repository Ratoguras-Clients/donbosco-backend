<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Organization;
use App\Models\OrganizationStaff;
use App\Models\OrganizationStaffRole;
use App\Models\StaffRole;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class OrganizationStaffController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->first();

        $staffs = OrganizationStaff::where('organization_id', $organization->id)
            ->with('getStaffRole.staffRole')
            ->get();

        return view('admin.organization-staff.index', compact('organization', 'staffs'));
    }

    public function create(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->first();

        return view('admin.organization-staff.create', compact('organization'));
    }

    public function store(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->first();

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'nullable',
            'bio' => 'nullable',
            'image' => 'nullable|exists:medias,id',
            'staff_role_id' => 'required|exists:staff_roles,id',
        ]);

        $staff = OrganizationStaff::create([
            'organization_id' => $organization->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'media_id' => $request->image,
            'bio' => $request->bio,
        ]);

        $staff_role = OrganizationStaffRole::create([
            'organization_staff_id' => $staff->id,
            'staff_role_id' => $request->staff_role_id,
        ]);

        $this->revalidatePaths([
            '/about',
            '/about/team'
        ]);

        return redirect()->route('organization-staff.index', $organization->slug)
            ->with('success', $staff->name . ' Staff created successfully.');
    }

    public function getstaffRoles(Request $request)
    {
        $search = $request->get('search');

        $roles = StaffRole::query()
            ->where('is_active', true)
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('id', 'DESC')
            // ->limit(10)
            ->get(['id', 'name']);

        return response()->json($roles);
    }

    public function delete(Request $request)
    {
        $staff = OrganizationStaff::where('id', $request->id)->firstOrFail();

        DB::transaction(function () use ($staff) {
            OrganizationStaffRole::where('organization_staff_id', $staff->id)->delete();
            Message::where('organization_staff_id', $staff->id)->delete();
            $staff->delete();
        });

        $this->revalidatePaths([
            '/about',
            '/about/team'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Staff deleted successfully.'
        ]);
    }

    public function edit(string $slug, int $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        $staff = OrganizationStaff::where('id', $id)->with('getStaffRole')
            ->where('organization_id', $organization->id)
            ->firstOrFail();

        return view('admin.organization-staff.edit', compact(
            'organization',
            'staff'
        ));
    }

    public function update(Request $request, string $slug, int $id)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();
        $staff = OrganizationStaff::where('id', $id)
            ->where('organization_id', $organization->id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => ['required', 'regex:/^(\+977)?9[6-9]\d{8}$/'],
            'bio' => 'nullable',
            'image' => 'nullable|exists:medias,id',
            'staff_role_id' => 'required|exists:staff_roles,id',
        ]);

        $staff->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'media_id' => $request->image,
        ]);

        OrganizationStaffRole::updateOrCreate(
            ['organization_staff_id' => $staff->id],
            ['staff_role_id' => $request->staff_role_id]
        );

        $this->revalidatePaths([
            '/about',
            '/about/team'
        ]);

        return redirect()
            ->route('organization-staff.index', $organization->slug)
            ->with('success', $staff->name . ' Staff updated successfully.');
    }

    public function storeMessage(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'staff_id' => 'required',
            'message' => 'required',
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $staff = OrganizationStaff::findOrFail($request->staff_id);

        Message::updateOrCreate(
            [
                'organization_id' => $staff->organization_id,
                'organization_staff_id' => $staff->id,
            ],
            [
                'title' => $request->title,
                'content' => $request->message,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Message created successfully.',
        ]);
    }

    public function messageExists($staffId, $organizationId)
    {
        $message = Message::where('organization_id', $organizationId)
            ->where('organization_staff_id', $staffId)
            ->first();

        return response()->json([
            'exists' => (bool) $message,
            'data' => $message ? [
                'id' => $message->id,
                'title' => $message->title,
                'content' => $message->content,
            ] : null,
        ]);
    }
}