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
		if(!Input::post() || !Sentry::user()->has_access('customers_'.$table.'_read') && !Sentry::user()->has_access('customers_all_read')) Response::redirect('');

		$data['table'] = $table;
		$data['json'] = Model_Ajax::tables($table, Input::post());
		
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_allTables(){
		if(!Sentry::user()->has_access('customers_all_read')) Response::redirect('');
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

	public function action_table_exists($table = ''){
		if($table == '')
			$data['json'] = false;
		else
			$data['json'] = DBUtil::table_exists($table);
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_session_up(){
		$data['json'] = Sentry::check() && Sentry::attempts()->check();
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_block(){
		if(!Sentry::user()->has_access('users_unblock')) return;
		$user_id = Input::post('user_id');

		if($user_id == null){
			throw new Exception('user id cannot be empty');
		}

		$data['json'] = Sentry::attempts($user_id)->block();
		$this->template->content = View::forge('ajax/view', $data);
	}

	public function action_unblock(){
		if(!Sentry::user()->has_access('users_unblock')) return;
		$user_id = Input::post('user_id');
		if($user_id == null){
			throw new Exception('user id cannot be empty');
		}

		$data['json'] = Sentry::attempts($user_id)->clear();
		$this->template->content = View::forge('ajax/view', $data);
	}
}
