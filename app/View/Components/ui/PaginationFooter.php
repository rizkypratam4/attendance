<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaginationFooter extends Component
{
    public $paginator;

    public function __construct($paginator)
    {
        $this->paginator = $paginator;
    }
    
    public function render(): View|Closure|string
    {
        return view('components.ui.pagination-footer');
    }
}
