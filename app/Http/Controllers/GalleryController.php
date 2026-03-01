<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Organization;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
   public function index ()
   {
       $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();

        $collections = Collection::with('coverMedia')
            ->where('organization_id', $organization->id)
            ->orderBy('created_at', 'asc')
            ->get();

             $collections = $collections->map(function ($item) {
            return [
                'id' => $item->id,
                'description'=> $item->description,
                'cover_image' => $item->cover_image ? $item->coverMedia->url : null,
                'created_at' => $item->created_at->format('Y-m-d'),
            ];
        });

        $data=[
            'collections'=>$collections
        ];

        return view('guest.medias.gallery', compact('data'));

   }
}
