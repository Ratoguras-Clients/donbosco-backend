<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MediaPiker extends Component
{
    public string $modalId;
    public string $title;
    public bool $allowMultiple;
    public bool $allowDownload = true;
    public bool $allowUpload;
    public bool $allowView;
    public string $acceptedTypes;
    public string $maxFileSize;
    public ?string $onSelect;
    public ?string $triggerSelector;
    public ?string $inputTarget;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $modalId = null,
        string $title = 'Select or Upload Media',
        bool $allowMultiple = false,
        bool $allowUpload = true,
        bool $allowDownload = true,
        bool $allowView = true,
        string $acceptedTypes = 'image/*,video/*,audio/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.*,text/plain',
        string $maxFileSize = '20MB',
        string $onSelect = null,
        string $triggerSelector = null,
        string $inputTarget = null
    ) {
        $this->modalId = $modalId ?? 'media-picker-modal-' . uniqid();
        $this->title = $title;
        $this->allowMultiple = $allowMultiple;
        $this->allowUpload = $allowUpload;
        $this->allowDownload = $allowDownload;
        $this->allowView = $allowView;
        $this->acceptedTypes = $acceptedTypes;
        $this->maxFileSize = $maxFileSize;
        $this->onSelect = $onSelect;
        $this->triggerSelector = $triggerSelector;
        $this->inputTarget = $inputTarget;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.media-piker');
    }
}
