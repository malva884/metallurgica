<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SelectForm extends Component
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
		$this->type  = Null;
        if(!empty($type))
            $this->type = $type;
		if(!empty($class))
			$this->class = $class;
		if(!empty($required))
			$this->required = $required;

	}

	public function get_default_values($type){
		switch ($type) {
			case "status":
				return [1=>__('locale.Active'),0=>__('locale.Disabled')];
				break;
			case "statusUser":
				return [ 1=>__('locale.Active'), 2=> __('locale.Blocked'), 3=>__('locale.Disabled')];
				break;
			case "sex":
				return [ 'm'=>__('locale.Male'), 'f'=> __('locale.Female')];
				break;
			case "stock":
				return [1=>__('locale.Active'),0=>__('locale.Disabled')];
				break;
			case "Yes_no":
				return [1=>__('locale.Yes'),0=>__('locale.No')];
				break;
		}
	}

	/**
	 * Get the view / contents that represent the component.
	 *
	 * @return \Illuminate\Contracts\View\View|string
	 */
	public function render()
	{
		return view('components.select-form');
	}
}
