<?php

namespace App\View\Components\ShiftGroups;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class table.blade extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shift-groups.table.blade.php');
    }
}
