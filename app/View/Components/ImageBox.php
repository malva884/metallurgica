<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImageBox extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $src;
    public $class;
    public $alt;

    public function __construct($name,$folder,$class,$alt)
    {
        if(count($name))
            $this->src = 'images/'.$folder.'/med_'.$name[0];
        else
            $this->src = '#';
        $this->class = $class;
        $this->alt = $alt;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.image-box');
    }
}
