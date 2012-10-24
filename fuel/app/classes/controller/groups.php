<?php

class Controller_Groups extends Controller_Base
{
	public function before(){
		parent::before();

		$this->template->js = Asset::js(array('plugins.js', 'mylibs/jquery.chosen.js', 'script.js', 'mylibs/jquery.ui.touch-punch.js'));

		$this->current_user = self::current_user();
	}

	private function no_access(){
		Session::set_flash('error', 'You DO NOT have access to the groups administration');
		Response::redirect('');
	}

	public function action_index(){
		if(!Sentry::user()->has_access('groups_index')){
			self::no_access();
		} 

		$this->template->js .= Asset::js(array('mylibs/jquery.dataTables.js'));
		$this->template->css = Asset::css(array('sprite.tables.css'));

		$data['groups'] = Sentry::group()->all();

		$this->template->title = "Groups &raquo; index";
		$this->template->h2 = 'List of groups';
		$this->template->content = View::forge('groups/index', $data);
	}

	public function action_view($id = null){
		if(!Sentry::user()->has_access('groups_view')){
			self::no_access();
		} 

		$data['group'] = Sentry::group(intval($id))->get(array('name', 'permissions'));
		$data['users'] = Sentry::group(intval($id))->users();
		$this->template->h2 = ucwords($data['group']['name']).' Group';
		$this->template->title = 'Groups &raquo; view';
		$this->template->content = View::forge('groups/view', $data);
	}

	public function action_create(){
		if(!Sentry::user()->has_access('groups_create')){
			self::no_access();
		} 

		if(Input::method() == 'POST'){

		}

		$this->template->title = 'Groups &raquo; Create';
		$this->template->h2 = 'Create a group';
		$this->template->js .= Asset::js(array('mylibs/jquery.validate.js', 'script.js'));
		$this->template->content = View::forge('groups/create');
	}

	public function action_delete($id = null){
		if(!Sentry::user()->has_access('groups_delete')){
			self::no_access();
		} 

		if(Sentry::group(intval($id))->delete()){
			Session::set_flash('success', 'Group successfuly deleted');
			Response::redirect('groups');
		}
		else
			Session::set_flash('error','Could not delete the group');
			Response::redirect('groups');
		}


}