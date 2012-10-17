<?php

class Controller_Welcome extends Controller_Base
{

	
	public function action_index()
	{
		if(!Sentry::check())
			Response::redirect('login');

		$this->template->title = $data['data']['title'] = 'Welcome to IKON backoffice';
		$this->template->css = Asset::css(array('sidebar.css', 'sprite.lists.css'));
		$this->template->js = Asset::js(array(
			'plugins.js',
			'mylibs/jquery.ba-resize.js',
			'mylibs/jquery.easing.1.3.js',
			'mylibs/jquery.ui.touch-punch.js',
			'mylibs/jquery.validate.js',
			'script.js',
			'dashboard.js'		
			));
		$this->template->content = View::forge('welcome/index', $data);
	}

	public function action_login(){
		// Auth::create_user('jonathan', '123456', 'jonathan@ikonfx.com', 200);
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
			Response::redirect('');

		if(Input::post()){
			if(Sentry::user_exists(Input::post('username'))){
				// User exists
				if(Sentry::attempts()->get_limit() != Sentry::attempts(Input::post('username'))->get()){
					// max attempts not reached
					$valid_login = Sentry::login(Input::post('username'), Input::post('password'));
		    
				    if ($valid_login){
				    	Session::set_flash('success', 'Successfuly connected');
				    	Response::redirect('');
				       
				    }
				    else{
				       	$data['username'] = Input::post('username');
						$data['password'] = Input::post('password');
						Session::set_flash('error', 'Username OR/AND Password incorrects. You tried '.Sentry::attempts(Input::post('username'))->get().'/'. Sentry::attempts()->get_limit());
				    }
				}
				else{
					// max attempts reached
					Session::set_flash('error', 'You\'ve reached your max attempts and will have to wait for 15 minutes');
				}
			}
		    else{
		    	// user do not exists
		    	Session::set_flash('error', 'User do not exists');
		    }
			

		}
		$this->template->css = Asset::css('special-page.css');
		$this->template->title = $data['title'] = 'Welcome to IKON backoffice';
		$this->template->js = Asset::js(array(
			'plugins.js',
			'mylibs/jquery.validate.js',
			'mylibs/jquery.jgrowl.js',
			'mylibs/jquery.checkbox.js',
			'script.js',
			'login.js',
			));
		$this->template->custom_class = 'special_page login';
		$this->template->content = View::forge('welcome/login', $data);
	}

	public function action_404()
	{
		$this->template->title = 'Page not found';
		$this->template->custom_class = 'special_page 404';
		$this->template->css = Asset::css(array('special-page.css'));
		$this->template->content = View::forge('welcome/404');
	}
}
