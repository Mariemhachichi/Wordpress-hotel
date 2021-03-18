<?php

namespace MPHB\Shortcodes\CheckoutShortcode;

class StepComplete extends Step {

	public function setup(){

	}

	public function render(){
		$this->showSuccessMessage();
	}

}
