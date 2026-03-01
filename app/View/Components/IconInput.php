<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IconInput extends Component
{
    /**
     * Create a new component instance.
     */

    public $triggerButton;
    public $inputContainer;
    public $previewDiv;
    public $removeIconBtn;

    public function __construct($triggerButton = 'select-icon-btn', $inputContainer = 'icon-field-container', $previewDiv = 'icon-preview', $removeIconBtn = 'remove-icon-btn')
    {
        $this->triggerButton = $triggerButton;
        $this->inputContainer = $inputContainer;
        $this->previewDiv = $previewDiv;
        $this->removeIconBtn = $removeIconBtn;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.icon-input');
    }
}
