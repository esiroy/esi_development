<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SatisfactionRatings extends Component
{

    public $rating;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($rating) {
        $this->rating = $rating;     
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.satisfaction-ratings');
    }
}
