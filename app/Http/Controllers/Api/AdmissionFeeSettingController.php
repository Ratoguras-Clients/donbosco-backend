<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdmissionFeeSetting;
use Illuminate\Http\Request;

class AdmissionFeeSettingController extends Controller
{
    public function index(Request $request, $organization_id = null)
    {
        if (!$organization_id) {
            $organization = \App\Models\Organization::where('parent_organization_id', null)->first();
            if (!$organization) {
                return response()->json(['data' => [], 'total' => 0], 404);
            }
            $organization_id = $organization->id;
        }

        $type = $request->query('type');

        $query = AdmissionFeeSetting::where('organization_id', $organization_id)
            ->where('is_active', true)
            ->orderBy('order_index', 'asc')
            ->orderBy('id', 'asc');

        if ($type && in_array($type, AdmissionFeeSetting::TYPES)) {
            $query->ofType($type);
        }

        $items = $query->get();

        // Group by type
        $grouped = $items->groupBy('type')->map(function ($group) {
            return $group->map(function ($item) {
                return array_merge(
                    ['id' => $item->id],
                    $item->data,
                    ['order_index' => $item->order_index]
                );
            })->values();
        });

        // If a specific type was requested, return flat
        if ($type) {
            return response()->json([
                'data' => $grouped->get($type, collect())->toArray(),
                'type' => $type,
                'total' => $grouped->get($type, collect())->count(),
            ]);
        }

        // Calculate total for annual/one-time fees (proposed annual rows)
        $proposedAnnualRows = $grouped->get('proposed_annual', collect());
        $totalAnnualFee = $proposedAnnualRows->sum(function ($item) {
            return (float) ($item['amount_npr'] ?? 0);
        });

        // Return all grouped
        return response()->json([
            'admission_classes' => $grouped->get('admission_class', collect())->toArray(),
            'annual_fee_components' => $grouped->get('annual_fee', collect())->toArray(),
            'monthly_fee_classes' => $grouped->get('monthly_fee_class', collect())->toArray(),
            'monthly_fee_other_charges' => $grouped->get('monthly_fee_other', collect())->toArray(),
            'proposed_fee_monthly' => $grouped->get('proposed_monthly', collect())->toArray(),
            'proposed_fee_annual' => $proposedAnnualRows->toArray(),
            'total_annual_fee' => $totalAnnualFee,
        ]);
    }
}
