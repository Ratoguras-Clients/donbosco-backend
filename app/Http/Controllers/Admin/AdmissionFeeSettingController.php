<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdmissionFeeSetting;
use App\Models\Organization;
use App\Traits\RevalidatesNextJs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdmissionFeeSettingController extends Controller
{
    use RevalidatesNextJs;

    private function getTypeLabel($type)
    {
        return match ($type) {
            'admission_class' => 'Admission Class',
            'annual_fee' => 'Annual Fee Component',
            'monthly_fee_class' => 'Monthly Fee (Class)',
            'monthly_fee_other' => 'Monthly Fee (Other)',
            'proposed_monthly' => 'Proposed Fee (Monthly)',
            'proposed_annual' => 'Proposed Fee (Annual)',
            default => 'Setting',
        };
    }

    public function index(Request $request, $slug)
    {
        $type = $request->input('type', 'admission_class');

        if ($request->has('status')) {
            $item = AdmissionFeeSetting::find($request->input('id'));
            $item->is_active = !$item->is_active;
            $item->save();

            $this->revalidatePaths(['/', '/admission']);

            return response()->json([
                'status' => true,
                'message' => $this->getTypeLabel($item->type) . ' is ' . ($item->is_active ? 'activated' : 'deactivated') . ' successfully.',
            ]);
        }

        if ($request->has('getData')) {
            $start = 0;
            $length = 10;

            if ($request->has('start') && $request->input('start') >= 0) {
                $start = intval($request->input('start'));
            }
            if ($request->has('length') && $request->input('length') >= 5 && $request->input('length') <= 100) {
                $length = intval($request->input('length'));
            }

            $organizationId = Organization::where('slug', $slug)->first()->id;

            $data = AdmissionFeeSetting::where('organization_id', $organizationId)
                ->ofType($type)
                ->orderBy('order_index', 'ASC')
                ->orderBy('id', 'ASC');

            $count = $data->count();
            $filtered_count = $count;
            $items = $data->offset($start)->limit($length)->get();

            return response()->json([
                'data' => $items->map(function ($item, $i) use ($start) {
                    $d = $item->data;
                    return [
                        'sn' => $start + $i + 1,
                        'id' => $item->id,
                        'type' => $item->type,
                        'data' => $d,
                        'order_index' => $item->order_index,
                        'is_active' => $item->is_active,
                        'created_by' => $item->getCreatedBy->name ?? 'N/A',
                        'updated_by' => $item->getUpdatedBy->name ?? 'N/A',
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                    ];
                })->toArray(),
                'recordsFiltered' => $filtered_count,
                'recordsTotal' => $count,
            ]);
        }

        return view('admin.admission-fee-settings.index', compact('slug', 'type'));
    }

    public function store(Request $request, $slug)
    {
        $type = $request->input('type', 'admission_class');

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:' . implode(',', AdmissionFeeSetting::TYPES),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $organization = Organization::where('slug', $slug)->firstOrFail();

        // Build the data JSON from request based on type
        $data = $this->buildDataFromRequest($request, $type);

        AdmissionFeeSetting::updateOrCreate(
            ['id' => $request->input('id')],
            [
                'organization_id' => $organization->id,
                'type' => $type,
                'data' => $data,
                'order_index' => $request->input('order_index', 0),
            ]
        );

        $this->revalidatePaths(['/', '/admission']);

        return response()->json([
            'status' => true,
            'message' => $this->getTypeLabel($type) . ($request->input('id') ? ' updated' : ' created') . ' successfully.',
        ]);
    }

    public function destroy($id)
    {
        $item = AdmissionFeeSetting::findOrFail($id);
        $item->delete();

        $this->revalidatePaths(['/', '/admission']);

        return response()->json([
            'status' => true,
            'message' => $this->getTypeLabel($item->type) . ' deleted successfully.',
        ]);
    }

    private function buildDataFromRequest(Request $request, $type)
    {
        switch ($type) {
            case 'admission_class':
                return [
                    'class' => $request->input('class'),
                    'dob_not_later_than' => $request->input('dob_not_later_than'),
                    'min_age' => (int) $request->input('min_age'),
                    'required_age' => $request->input('required_age'),
                    'qualification_en' => $request->input('qualification_en'),
                    'qualification_np' => $request->input('qualification_np'),
                    'selection_en' => $request->input('selection_en'),
                    'selection_np' => $request->input('selection_np'),
                    'documents_en' => array_filter(array_map('trim', explode("\n", $request->input('documents_en', '')))),
                    'documents_np' => array_filter(array_map('trim', explode("\n", $request->input('documents_np', '')))),
                ];

            case 'annual_fee':
                return [
                    'item' => $request->input('item'),
                    'amount_npr' => (float) $request->input('amount_npr'),
                    'frequency' => $request->input('frequency'),
                ];

            case 'monthly_fee_class':
                return [
                    'classes' => $request->input('classes'),
                    'amount_npr' => (float) $request->input('amount_npr'),
                ];

            case 'monthly_fee_other':
                return [
                    'item' => $request->input('item'),
                    'amount_npr' => (float) $request->input('amount_npr'),
                ];

            case 'proposed_monthly':
                return [
                    'class_type' => $request->input('class_type'),
                    'frequency' => $request->input('frequency', 'Monthly'),
                    'amount_npr' => (float) $request->input('amount_npr'),
                ];

            case 'proposed_annual':
                return [
                    'item' => $request->input('item'),
                    'frequency' => $request->input('frequency'),
                    'amount_npr' => (float) $request->input('amount_npr'),
                ];

            default:
                return [];
        }
    }
}
