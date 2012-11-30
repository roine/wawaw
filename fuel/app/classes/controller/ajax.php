<?php

class Controller_Ajax extends Controller_Base
{
	public function before(){
		parent::before();
		// $this->languages = array('en', 'cn', 'tw', 'ru');
		$this->languages = array();
		if(Sentry::user()->has_access('customers_en')) array_push($this->languages, 'en');
		if(Sentry::user()->has_access('customers_ru')) array_push($this->languages, 'ru');
		if(Sentry::user()->has_access('customers_tw')) array_push($this->languages, 'tw');
		if(Sentry::user()->has_access('customers_cn')) array_push($this->languages, 'cn');
		if(!Input::is_ajax()){
			Response::redirect('');
		}

	}

	public $template = 'ajax';

	public function action_dashboard()
	{
		if(!Sentry::user()->has_access('ajax_dashboard')){
			Session::set_flash('error', 'You do not have right acces there');
			Response::redirect('/');
		}

		$data['json'] = Model_Ajax::dashboard(Input::post('values'), $this->languages);
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_tables($table = null)
	{	
		if(!Input::post() || !Sentry::user()->has_access($table.'_read') && !Sentry::user()->has_access('all_tables_read')) Response::redirect('');

		$data['table'] = $table;
		$data['json'] = Model_Ajax::tables($table, Input::post());
		
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_allTables(){
		$data['json'] = Model_Ajax::allTables(Input::post());

		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_deleteData(){
		if(!Input::post() || !Sentry::user()->has_access('customers_delete')) Response::redirect('');

		$data['json'] = Model_Ajax::deleteData(Input::post());
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_updateData($table = null){
		if(!Input::post() || !Sentry::user()->has_access('customers_update')) Response::redirect('');

		$data['table'] = $table;
		$data['json'] = Model_Ajax::updateData(Input::post(), $table);
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_block(){
		if(!Input::post() || !Sentry::user()->has_access('users_unblock')) Response::redirect('');
		$user = Sentry::user(Input::post('username'));
		try
		{
			$data['json'] = Sentry::attempts(Input::post('username'), $user->get('ip_address'))->suspend_with_ajax();
		}
		catch(SentryAttemptsException $e){
			echo $e->getMessage();
		}
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_unblock(){
		if(!Input::post() || !Sentry::user()->has_access('users_unblock')) Response::redirect('');
		try
		{
			$data['json'] = Sentry::attempts(Input::post('username'))->clear();
		}
		catch(SentryAttemptsException $e){
			echo $e->getMessage();
		}
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_message(){
		
		$data['json'] = Model_Ajax::messages($this->current_user);
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_charts($type = 'daily'){
		if(!Sentry::user()->has_access('charts_monthly')) Response::redirect('');

		if($type == 'daily'){
			// slice to remove the all form
			$data['json'] = Model_Ajax::chartsDaily(array_slice($this->tables, 1, count($this->tables)));
		}

		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_statistics()
	{
		$this->template->title = 'Ajax &raquo; Statistics';
		$this->template->content = View::forge('ajax/statistics');
	}

	public function action_login(){
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
					Session::set_flash('error', 'You\'ve reached your max attempts and will have to wait for '.Sentry::attempts(Input::post('username'))->get_time().' minutes');
				}
			}
		    else{
		    	// user do not exists
		    	Session::set_flash('error', 'User do not exists');
		    }
			

		}
		$this->template->content = View::forge('ajax/view', $data);
	}

}
