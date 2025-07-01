<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toast extends Component
{
    public $type;
    public $message;
    /**
     * Create a new component instance.
     */
    public function __construct($type=null, $message=null)
    {
        $this->type = filled($type) ? $type : session('type');
        $this->message = filled($message) ? $message : session('message');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notyf-toast');
    }
}
