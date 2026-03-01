<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request, $slug)
    {
        if ($request->has('status')) {
            $service = Service::find($request->input('id'));
            $service->is_active = !$service->is_active;
            $service->save();

            return response()->json([
                'status' => true,
                'message' => 'The role is ' . ($service->is_active ? 'activated' : 'deactivated') . ' successfully.',
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

            $id = Organization::where('slug', $slug)->first()->id;
            $data = Service::orderBy('id', 'DESC')->where('organization_id', $id);

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "title" => $item->title,
                        "icon" => $item->icon,
                        "description" => $item->description,
                        "is_active" => $item->is_active,

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

        return view('admin.services.index', compact('slug'));
    }

    public function store(Request $request, $slug)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:services,title,' . $request->input('id'),
            'icon' => 'required',
            'description' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $organization = Organization::where('slug', $slug)->firstOrFail();

        $service = Service::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'title'        => $request->title,
                'description' => $request->description,
                'icon' => $request->icon,
                'organization_id' => $organization->id,
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => $service->slug
                ? 'Service updated successfully.'
                : 'Service created successfully.',
        ]);
    }

    public function destroy($id)
    {
        $service = Service::find($id);
        $service->delete();

        return response()->json([
            'status' => true,
            'message' => 'Service deleted successfully.',
        ]);
    }
}
