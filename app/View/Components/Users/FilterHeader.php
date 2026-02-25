<?php

namespace App\View\Components\Users;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterHeader extends Component
{
    public $showing;
    public $total;

    public function __construct($showing = 0, $total = 0)
    {
        $this->showing = $showing;
        $this->total = $total;
    }

    public function render(): View|Closure|string
    {
        return view('components.users.filter-header');
    }
}
