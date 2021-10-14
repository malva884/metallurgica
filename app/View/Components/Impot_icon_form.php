<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Impot_icon_form extends Component
{
	public $value;
	public $title = '';
	public $class= '';
	public $required;
	public $type;
	public $length;
	public $asd;
	public $icon;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($title,$class,$value,$icon,$type=null,$required=null,$length=null)
	{
		$this->value = $value;
		$this->title = $title;
		$this->type = $type;
		$this->class = '';
		$this->icon = $icon;
		if(!empty($class))
			$this->class = $class;
		if(!empty($required))
			$this->required = $required;
		if(!empty($length))
			$this->length = $length;
		if($type == 'price'){
			$this->type = 'number';
			$this->asd = 'step=0.01';
		}

	}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.impot_icon_form');
    }
}
