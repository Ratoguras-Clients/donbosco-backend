<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * The breadcrumbs array.
     *
     * @var array
     */
    public $breadcrumbs;

    /**
     * Create a new component instance.
     *
     * @param  array  $breadcrumbs
     * @return void
     */
    public function __construct($breadcrumbs = [])
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.breadcrumb');
    }
}
