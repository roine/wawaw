<?php

class Controller_Ajax extends Controller_Base
{
	public function before(){
		parent::before();
		$this->languages = array('en', 'cn', 'tw', 'ru');
		// if(!Input::is_ajax()){
		// 	Response::redirect('');
		// }

	}

	public $template = 'ajax';

	public function action_dashboard()
	{
		if(!Sentry::user()->has_access('ajax_dashboard')){
			Session::set_flash('error', 'You do not have right acces there');
			Response::redirect('/');
		}
		$data['json'] = Model_Ajax::dashboard($this->tables, $this->languages);
		$this->template->title = 'Ajax &raquo; Dashboard';
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

	public function action_statistics()
	{
		$this->template->title = 'Ajax &raquo; Statistics';
		$this->template->content = View::forge('ajax/statistics');
	}

}
