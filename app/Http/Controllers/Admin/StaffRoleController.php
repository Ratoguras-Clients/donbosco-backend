<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StaffRoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('status')) {
            $staffRole = StaffRole::find($request->input('id'));
            $staffRole->is_active = !$staffRole->is_active;
            $staffRole->save();

            return response()->json([
                'status' => true,
                'message' => 'The role is ' . ($staffRole->is_active ? 'activated' : 'deactivated') . ' successfully.',
            ]);
        }

        if ($request->has('messageable')) {
            $staffRole = StaffRole::find($request->input('id'));
            $staffRole->is_messageable = !$staffRole->is_messageable;
            $staffRole->save();

            return response()->json([
                'status' => true,
                'message' => 'The messageable status is ' . ($staffRole->is_messageable ? 'enabled' : 'disabled') . ' successfully.',
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

            $data = StaffRole::orderBy('id', 'DESC');

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "name" => $item->name,
                        "slug" => $item->slug,
                        "description" => $item->description,
                        "is_active" => $item->is_active,
                        "is_messageable" => $item->is_messageable,

                        "created_by" => $item->getCreatedBy->name,
                        "updated_by" => $item->getUpdatedBy->name ?? 'N/A',

                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                })->toArray(),
                "recordsFiltered" => $filtered_count,
                "recordsTotal" => $count
            ]);
        }

        return view('admin.staff-role.index');
    }

    public function store(Request $request)
    {
        $id = $request->input('id');

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:staff_roles,name,' . $id,
            'description' => 'required',
            'is_messageable' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $staffRole = StaffRole::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'is_messageable' => $request->boolean('is_messageable'),
            ]
        );

        return response()->json([
            'status' => true,
            'message' => $id
                ? 'Staff role updated successfully.'
                : 'Staff role created successfully.',
        ]);
    }

    public function destroy($id)
    {
        $staffRole = StaffRole::find($id);
        $staffRole->delete();

        return response()->json([
            'status' => true,
            'message' => 'Staff role deleted successfully.',
        ]);
    }
}
