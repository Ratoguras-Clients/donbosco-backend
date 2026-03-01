<?php

namespace App\View\Components;

use App\Models\Website\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Navbar extends Component
{
    public $pages;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->pages = Page::where('post_status', 'publish')
            ->where('post_parent', 0)
            ->where('post_type', 'page')
            ->with('children')
            ->orderBy('menu_order', 'asc')
            ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
//        dd('here');
        return view('website.layouts.include.navbar', ['pages' => $this->pages]);
    }
}
