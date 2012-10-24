<?php

class Controller_Users extends Controller_Base
{

	public function before(){
		parent::before();

		$this->template->js = Asset::js(array('mylibs/jquery.jgrowl.js', 'plugins.js', 'mylibs/jquery.chosen.js', 'script.js', 'mylibs/jquery.ui.touch-punch.js'));

		// $this->current_user = self::current_user();
		// View::set_global('profile_fields', unserialize($user->profile_fields));
	}


	public function action_index()
	{
		if(!Sentry::user()->has_access('users_index')){
			Session::set_flash('error', 'You DO NOT have access to the user list');
			Response::redirect('');
		} 
		$this->template->less = Asset::less(array('customic.less'));
		$this->template->js .= Asset::js(array('mylibs/jquery.dataTables.js'));
		$this->template->css = Asset::css(array('sprite.tables.css'));

		$data['users'] = Sentry::user()->all();

		$this->template->h2 = 'List of users';
		$this->template->title = 'User &raquo; Index';
		$this->template->content = View::forge('users/index', $data);
	}


	public function action_create(){
		if(!Sentry::user()->has_access('users_create')) Response::redirect('');

		if(Input::method() == 'POST'){
			$val = Model_Users::validate('create');
			if(!$val->run()){
				// no valid values
				Session::set_flash('error', implode(' ', $val->error()));
			}
			else{
				// valid values
				if (Sentry::user_exists(Input::post('username'))){
				    // the user exist
				    Session::set_flash('error', 'User '.Input::post('username').' already exists');
				}
				else{
					$user_id = Sentry::user()->create(array(
				 	'username' => Input::post('username'),
				 	'email' => Input::post('email'),
				 	'password' => Input::post('password'),
				 	'metadata' => array(
				 		'department' => Input::post('department'),
				 		'first_name' => Input::post('first_name'),
				 		'last_name' => Input::post('last_name'),
				 		)
					));
					if(!$user_id){
						// user has not been created
					 	Session::set_flash('error', 'User not created');
					}
					else{
						// user has been created
						$user = Sentry::user($user_id);
						$user->add_to_group(Input::post('group'));
						Session::set_flash('success', 'User successfuly created');
						Response::redirect('users');
					}
				}
			}
		}

		View::set_global('groups', Sentry::group()->all());
		$this->template->h2 = 'Create a user';
		$this->template->title = 'User &raquo; Create';
		$this->template->js .= Asset::js(array('mylibs/jquery.validate.js', 'script.js'));
		$this->template->content = View::forge('users/create');
	}


	public function action_view($id = null){
		if(!Sentry::user()->has_access('users_view') && $this->current_user->id != $id){
			Session::set_flash('error', 'You do not have access');
			Response::redirect('');
		}
		$data['user'] = Sentry::user(intval($id));
		is_null($id) and Response::redirect('users');

		$this->template->h2 = $this->template->title = isset($data['user']['username']) ? ucwords($data['user']['username']).'\'s Profile' : 'User not found';
		$this->template->content = View::forge('users/view', $data);
	}


	public function action_edit($id = null){
		// redirect if no right access
		if(!Sentry::user()->has_access('users_edit') && $this->current_user->id != $id){
			Session::set_flash('warning', 'You don\'t have the right to edit a user');
			Response::redirect('users');
		}

		$user = Sentry::user(intval($id));
		$group = $user->groups();
		View::set_global('user', $user);
		View::set_global('groups', Sentry::group()->all());
		View::set_global('user_group', $group);
		// if receive a post update the user
		if(Input::method() == 'POST'){

			$val = Model_Users::validate('edit');
			if(!$val->run()){
				// the data are not valid
				Session::set_flash('error', implode(', ', $val->error()));
			}
			else{
				// valid data

				// remove the user from is actual group
				if(!empty($group[0]['name']))
					$user->remove_from_group($group[0]['name']);
				// set a new group for the user
				$user->add_to_group(Input::post('group'));
				$update = $user->update(array(
			        'password' => 'somenewpassword',
			        'email' => Input::post('email'),
			        'metadata' => array(
			            'first_name' => Input::post('first_name'),
			            'last_name'  => Input::post('last_name'),
			            'department' => Input::post('department'),
			        )
			    ));
				
			}
		}

		$this->template->h2 = $this->template->title = isset($user['username']) ? 'Editing '.ucwords($user['username']).'\'s profile' : 'User not found';

		$this->template->content = View::forge('users/edit');
	}

	public function action_delete($id = null){

		if(!Sentry::user()->has_access('users_delete') && $this->current_user->id != $id){
			Session::set_flash('warning', 'You don\'t have the right to delete a user');
			Response::redirect('users');
		}

		if ($user = Sentry::user(intval($id))){
			$user->delete();
			Session::set_flash('success', 'Deleted user #'.$id);
		}
		else{
			Session::set_flash('error', 'Could not delete user #'.$id);
		}

		Response::redirect('users');
	}

}
