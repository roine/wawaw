<?php

class Controller_Message extends Controller_Base
{	

	public function before(){
		parent::before();
		$this->template->js = Asset::js(array('mylibs/jquery.jgrowl.js', 'plugins.js', 'script.js', 'messages.js'));
	}

	public function action_index(){
		
	}

	public function action_to($id = null){
		// redirect if no id
		if($id == null) Response::redirect('message');

		// redirect if no right access
		if(!Sentry::user()->has_access('message_send')){
			Session::set_flash('error', "You are not allowed to send message");
			Response::redirect('');
		}

		if(Input::method() == 'POST'){
			
		}

		$data['user'] = Sentry::user(intval($id));
		$data['messages'] = Model_Message::messageWith($data['user'], $this->current_user);
		$this->template->h2 = 'Send Message to '.$data['user']->username;
		$this->template->title = 'Message &raquo; to';
		$this->template->content = View::forge('message/to', $data);
	}
}

?>
