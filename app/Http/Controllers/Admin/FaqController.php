<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Traits\RevalidatesNextJs;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    use RevalidatesNextJs;
    public function index(Request $request, $slug)
    {
        if ($request->has('status')) {
            $staffRole = Faq::find($request->input('id'));
            $staffRole->is_active = !$staffRole->is_active;
            $staffRole->save();

         $this->revalidatePaths([
            '/',
            '/faq'
        ]);

            return response()->json([
                'status' => true,
                'message' => 'The role is ' . ($staffRole->is_active ? 'activated' : 'deactivated') . ' successfully.',
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
            $data = Faq::orderBy('id', 'DESC')->where('organization_id', $id);

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "question" => $item->question,
                        "answer" => $item->answer,

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

        return view('admin.faq.index', compact('slug'));
    }

    public function store(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $organization = Organization::where('slug', $slug)->first();

        $faq = Faq::updateOrCreate(
            ['id' => $request->id],
            [
                'organization_id' => $organization->id,
                'question' => $request->question,
                'answer' => $request->answer,
            ]
        );

         $this->revalidatePaths([
            '/',
            '/faq'
        ]);

        return response()->json([
            'status'  => true,
            'message' => $faq->slug
                ? 'FAQ updated successfully.'
                : 'FAQ created successfully.',
        ]);
    }

    public function destroy($id)
    {
        $faq = Faq::find($id);
        $faq->delete();

        
         $this->revalidatePaths([
            '/',
            '/faq'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'FAQ deleted successfully.',
        ]);
    }
}
