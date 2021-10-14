<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckBoxForm extends Component
{
	public $value;
	public $title= '';
	public $class;
	public $obligatory;
	public $defaults;
	public $label;
	public $translation;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($title,$class,$value,$label,$default,$translation=true)
    {

	    $this->title = $title;
	    $this->class = '';
	    $this->defaults = $default;
	    $this->label = $label;
	    $this->translation = $translation;

	    if(!empty($class))
		    $this->class = $class;
	    if(is_object($value)) {
            $this->value = ['id'=> $value->id, 'name' => $value->$label];
            //print_r($this->value);
        }else{
                $this->value =['id'=>1,'name'=>$label];
        }
	    if(is_bool($default)){
	        if($default === true)
            $this->defaults = true;
        else
            $this->defaults = false;
        }

		if(is_string($default))
            $this->defaults = [$default];
    }

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|string
	 */
	public function render()
	{
		return view('components.check-box-form');
	}
}
