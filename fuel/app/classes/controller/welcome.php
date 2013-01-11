<?php

class Controller_Welcome extends Controller_Base
{
	
	
	public function action_index()
	{
		$data['lang'] = array();
		if(Sentry::user()->has_access('customers_en')) $data['lang']['en'] = 'English';
		if(Sentry::user()->has_access('customers_ru')) $data['lang']['ru'] = 'Russian';
		if(Sentry::user()->has_access('customers_tw')) $data['lang']['tw'] = 'Taiwanese';
		if(Sentry::user()->has_access('customers_cn')) $data['lang']['cn'] = 'Chinese';

		
		// $lang = array("en" => "English", "cn" => "Chinese", "ru" => "Russian", "tw" => "Taiwanese"); 
		// $this->template->less = Asset::less('customic.less');
		$this->template = \View::forge('dashboard');
		$this->template->title = $data['data']['title'] = 'Welcome to IKON backoffice';
		$this->template->content = View::forge('welcome/index', $data);
	}

	public function action_login(){
		
		$redirect = Session::get('redirect');
		$vars = array(
    	'email'    => 'jonathan@ikonfx.com',
    	'password' => '123456',
    	'username' => 'jonathan',
    	'metadata' => array(
    		'first_name' => 'jonathan',
    		'last_name'  => 'de montalembert',
    		'department' => 'test',
	    	)
	    );

    // $user_id = Sentry::user()->create($vars, true);

		if(Sentry::check())
			Response::redirect($redirect);

		$this->template = \View::forge('login');

		if(Input::post()){
			if(Sentry::user_exists(Input::post('username'))){
				// User exists
				if(Sentry::attempts()->get_limit() > Sentry::attempts(Input::post('username'))->get()){
					// max attempts not reached
					$valid_login = Sentry::login(Input::post('username'), Input::post('password'));
		    
				    if ($valid_login){
				    	Session::set_flash('success', 'Welcome back '.ucwords(Input::post('username')));
				    	Response::redirect($redirect);
				    }
				    else{
				       	$data['username'] = Input::post('username');
						$data['password'] = Input::post('password');
						Session::set_flash('error', 'Username OR/AND Password incorrects. You tried '.Sentry::attempts(Input::post('username'))->get().'/'. Sentry::attempts()->get_limit());
				    }
				}
				else{
					// max attempts reached
					Session::set_flash('error', 'You\'ve reached your max attempts and will have to wait for '.Sentry::attempts(Input::post('username'))->get_time().' minutes');
				}
			}
		    else{
		    	// user do not exists
		    	Session::set_flash('error', 'User do not exists');
		    }
			

		}
		$this->template->title = $data['title'] = 'Welcome to IKON backoffice';
		$this->template->custom_class = 'special_page';
		$this->template->content = View::forge('welcome/login', $data);
	}

	public function action_404()
	{
		$this->template = \View::forge('404');
		$this->template->title = 'Page not found';
		$this->template->custom_class = 'special_page 404';
		$this->template->css = Asset::css(array('special-page.css'));
	}
}
