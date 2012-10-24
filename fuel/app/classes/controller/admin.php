<?php

class Controller_Admin extends Controller_Template
{

	public function action_index()
	{

		$data['username'] = null;
		$data['password'] = null;
		// Auth::create_user('test', '123456', 'test@test.com');
		if(Input::post()){
			if(Sentry::check()){
				Response::redirect('admin');
				Session::set_flash('success', 'Successfuly Logged In');
			}
			else{
				$data['username'] = Input::post('username');
				$data['password'] = Input::post('password');
				Session::set_flash('error', 'Error credential');
			}
		}
			
		$this->template->title = 'boss';
		$this->template->content = View::forge('admin/index', $data);
	}

	public function action_logout(){
		if(Sentry::login()){
			Session::set_flash('success', 'successfuly logged out');
			Sentry::logout();
		}
		else
			Session::set_flash('warning', 'You\'re not logged in');
		Response::redirect('');
	}

}
