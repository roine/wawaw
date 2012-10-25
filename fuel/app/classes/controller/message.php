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

		$data['user'] = Sentry::user(intval($id));
		$data['messages'] = Model_Message::messageWith($data['user'], $this->current_user);

		if(Input::method() == 'POST'){
			$message = Model_Message::forge(array(
					'subject' => Input::post('subject'),
					'content' => Input::post('content'),
					'to' => $data['user']->id,
					'from' => $this->current_user->id,
					'parent_id' => '',
					'read' => 0,
					'from_delete' => 0,
					'to_delete' => 0
				));
			if($message and $message->save()){
				Session::set_flash('success', 'Message successfuly sent to '.$data['user']->username);
				Response::redirect('message');
			}
			else{
				Session::set_flash('error', 'Could not send the message.');
			}
		}

		
		$this->template->h2 = 'Send Message to '.$data['user']->username;
		$this->template->title = 'Message &raquo; to';
		$this->template->content = View::forge('message/to', $data);
	}
}

?>
