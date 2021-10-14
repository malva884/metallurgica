<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ImputForm extends Component
{
	public $value;
	public $title = '';
	public $class= '';
	public $required;
	public $type;
	public $length;
	public $exstra = '';
    public $readonly;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($title,$class,$value,$type=null,$required=null,$readonly=null,$length=null)
	{
		$this->value = $value;
		$this->title = $title;
		$this->type = $type;
        $this->readonly = $readonly;
		$this->class = '';
		if(!empty($class))
			$this->class = $class;
		if(!empty($required))
			$this->required = $required;
		if(!empty($length))
			$this->length = $length;
		if($type == 'price'){
			$this->type = 'number';
			$this->exstra = 'step=0.01';
		}


	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|string
	 */
	public function render()
	{
		return view('components.imput-form');
	}
}
