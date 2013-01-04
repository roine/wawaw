<?php

class Controller_Settings extends Controller_Base
{
	public function action_index(){
		$this->template->content = View::forge('settings/index');
	}
}

?>
