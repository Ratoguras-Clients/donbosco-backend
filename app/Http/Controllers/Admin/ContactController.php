<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Organization;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request, $slug)
    {
        $organization = Organization::where('slug', $slug)->firstOrFail();

        if ($request->has('status')) {
            $contact = Contact::find($request->input('id'));
            $contact->is_active = !$contact->is_active;
            $contact->save();

            return response()->json([
                'status' => true,
                'message' => 'The contact is ' . ($contact->is_active ? 'activated' : 'deactivated') . ' successfully.',
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

            $data = Contact::where('organization_id', $organization->id)
                ->orderBy('id', 'DESC');

            $count = $data->count();

            $filtered_count = $data->count();
            $data = $data->offset($start)->limit($length)->get();

            return response()->json([
                "data" => $data->map(function ($item, $i) use ($start) {
                    return [
                        "sn" => $start + $i + 1,
                        "id" => $item->id,
                        "name" => $item->name,
                        "email" => $item->email,
                        "phone" => $item->phone,
                        "message" => $item->message,
                        "is_active" => $item->is_active,
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                })->toArray(),
                "recordsFiltered" => $filtered_count,
                "recordsTotal" => $count
            ]);
        }

        return view('admin.contact.index', compact('organization'));
    }
    public function destroy($id)
    {
        $data = Contact::find($id);
        $data->delete();

        return response()->json([
            'status' => true,
            'message' => 'Contact deleted successfully.',
        ]);
    }
}
