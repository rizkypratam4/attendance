<?php

namespace App\View\Components\ui;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SecondaryStatCard extends Component
{
    public $title; 
    public $value; 
    public $meta; 
    public $icon; 
    public $iconBg; 
    public $metaColor;

    public function __construct($title = 'Title', $value = '', $meta = '', $icon = null, $iconBg = 'bg-purple-100', $metaColor = 'text-gray-500')
    {
        $this->title = $title; 
        $this->value = $value; 
        $this->meta = $meta; 
        $this->icon = $icon; 
        $this->iconBg = $iconBg; 
        $this->metaColor = $metaColor;
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.secondary-stat-card');
    }
}
