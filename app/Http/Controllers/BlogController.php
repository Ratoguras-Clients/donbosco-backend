<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Organization;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $organization = Organization::where('parent_organization_id', null)
            ->where('status', 'active')
            ->first();
            
        $blogs = Blog::with('media')
            ->where('organization_id', $organization->id)
            ->where('is_published', true)
            ->orderBy('start_date', 'desc')
            ->paginate(6);

        $blogs = $blogs->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'name'=>$item->name,
                'image' => $item->media ? $item->media->url : null,
                'start_date' => $item->start_date,
            ];
        });

        $data = [
            
            'blogs'=>$blogs,
        ];

        return view('guest.medias.blog', compact('data'));
    }
}
