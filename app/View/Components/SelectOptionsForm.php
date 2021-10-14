<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectOptionsForm extends Component
{
    public $values;
    public $title= '';
    public $class;
    public $defoult;
    public $required;
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title,$class,$values=null,$defoult=null,$required=null,$type=null)
    {
        $this->values = $values;
        $this->title = $title;
        $this->defoult = $defoult;
        $this->class = '';
        $this->type  = $type;
        if(!empty($class))
            $this->class = $class;
        if(!empty($required))
            $this->required = $required;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.select-options-form');
    }
}
