<?php

class Controller_Forms extends Controller_Base
{	

	public function before(){
		parent::before();

		$this->template->js = Asset::js(array('mylibs/jquery.jgrowl.js', 'mylibs/jquery.validate.js','plugins.js', 'script.js', 'mylibs/jquery.chosen.js', 'mylibs/jquery.ui.touch-punch.js'));
		if(!Sentry::user()->has_access('forms_index')){
			Session::set_flash('error', "You cannot access that section");
			Response::redirect('');
		}
	}


	public function action_edit($id = null) {
		$form = Model_Forms::find(intval($id));

		if(Input::method() == 'POST'){
			$form->cleanName = Input::post('cleanName');
			$form->url = Input::post('url');
			$form->table = Input::post('table');
			if($form->save()){
				Session::set_flash('success', "$form->cleanName edited");
				Response::redirect('forms');
			}
			else
				Session::set_flash('success', "$form->cleanName editing failed");
		}

		View::set_global('form', $form);
		$this->template->content = View::forge('forms/edit');
	}

	public function action_index() {
		$data['all'] = Model_Forms::find('all');

		$this->template->js .= Asset::js(array('mylibs/jquery.dataTables.js'));
		$this->template->css = Asset::css(array('sprite.tables.css'));
		$this->template->content = View::forge('forms/index', $data);
		
	}

	public function action_add(){

		if(Input::method() == 'POST'){
			$form = new Model_Forms();
			$form->cleanName = Input::post('cleanName');
			$form->url = Input::post('url');
			$form->table = Input::post('table');
			if($form->save()){
				Session::set_flash('success', "$form->cleanName created");
				Response::redirect('forms');
			}
			else
				Session::set_flash('success', "$form->cleanName creating failed");
		}
		$this->template->content = View::forge('forms/add');
	}


	public function action_delete($id){
		if ($form = Model_Forms::find($id))
		{
			$form->delete();

			Session::set_flash('success', 'Deleted form #'.$id);
		}

		else
		{
			Session::set_flash('error', 'Could not delete form #'.$id);
		}

		Response::redirect('forms');
	}
}