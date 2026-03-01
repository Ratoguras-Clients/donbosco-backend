<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Message;
use App\Models\Organization;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(){
         $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

            $messages = Message::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_active', true)
            ->where('is_home', true)
            ->get();

        $messages = $messages->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'content' => $item->content,
                'image' => $item->attachment
                    ? $item->attachment->url
                    : null,
                'staff_name' => $item->organizationStaff ? $item->organizationStaff->name : 'Unknown',
            ];
        });
        $data = [
            'organization' => $organization,
            'messages'=>$messages,
        ];

        return view('guest.message', compact('data'));

    }

    public function faq()
    {
       $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

        $faqs = Faq::where('organization_id', $organization->id)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();

        $data = [
            'organization' => $organization,
            'faqs' => $faqs,
            
        ];

        return view('guest.faq', compact('data'));
    }
    
}
