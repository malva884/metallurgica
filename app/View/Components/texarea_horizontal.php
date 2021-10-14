<?php

namespace App\View\Components;

use Illuminate\View\Component;

class texarea_horizontal extends Component
{
	public $value;
	public $title = '';
	public $class= '';
	public $required;
	public $type;
	public $length;

	/**
	 * Create a new component instance.
	 *
	 * @return void
	 */
	public function __construct($title,$class,$value,$type=null,$required=null,$length=null)
	{
		$this->value = $value;
		$this->title = $title;
		$this->type = $type;
		$this->class = '';
		if(!empty($class))
			$this->class = $class;
		if(!empty($required))
			$this->required = $required;
		if(!empty($length))
			$this->length = $length;
	}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.texarea_horizontal');
    }
}
