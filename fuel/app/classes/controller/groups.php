<?php

class Controller_Groups extends Controller_Base
{
	public function before(){
		parent::before();

		$this->template->js = Asset::js(array('plugins.js', 'mylibs/jquery.checkbox.js', 'mylibs/jquery.chosen.js', 'script.js', 'mylibs/jquery.ui.touch-punch.js'));

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
			if(!Input::post('groupName')){
				Session::set_flash('error', 'Please choose a name for the group');
			}
			else{
				$post = Input::post();
				$groupName = $post['groupName'];
				unset($post['groupName']);
				$permissions = json_encode($post, JSON_NUMERIC_CHECK);
				$updated = Model_Group::createGroup($groupName, $permissions);
				if($updated)
					Session::set_flash('success', 'The group '.$groupName.' has been successfully created');
					Response::redirect('groups');
			}
			
			
		}

		$var = self::access();
		View::set_global('var',$var);
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

		public function action_edit($id = null){
			if(!Sentry::user()->has_access('groups_edit')){
				self::no_access();
			}
			$groups = Model_Group::access(intval($id));
			$group['permissions'] = json_decode($groups[0]['permissions'], true);
			$group['groupName'] = $groups[0]['name'];
			

			if(Input::method() == 'POST'){
				if(!Input::post('groupName')){
					Session::set_flash('error', 'Please choose a name for the group');
				}
				else{
					$post = Input::post();
					$groupName = $post['groupName'];
					unset($post['groupName']);
					// JSON_NUMERIC_CHECK to keep the int
					$permissions = json_encode($post, JSON_NUMERIC_CHECK);

					$updated = Model_Group::editGroup(intval($id), $groupName, $permissions);
					if($updated){
						Session::set_flash('success', 'The group '.$groupName.' has been successfully edited');
						Response::redirect('groups');
					}
						
				}
			}

			$var = self::access();
			// all the access
			View::set_global('var',$var);
			// all the needed informations about the current group
			View::set_global('group',$group);
			$this->template->h2 = 'Edit Group';
			$this->template->title = 'Groups &raquo; Edit';
			$this->template->content = View::forge('groups/edit');
		}

		private function access(){
			// user control
			$var[] = array('access' => 'users_index', 'text' => 'Can see the Users List', 'start' => array('is_start' => 1, 'text'=> 'User Control'));
			$var[] = array('access' => 'users_edit', 'text' => 'can edit a user');
			$var[] = array('access' => 'users_delete', 'text' => 'can delete a user');
			$var[] = array('access' => 'users_view', 'text' => 'Can view the user\'s profile');
			$var[] = array('access' => 'users_create', 'text' => 'Can create a new user');
			$var[] = array('access' => 'users_password_change', 'text' => 'Can change own password');
			$var[] = array('access' => 'change_acl', 'text' => 'Can change own group');
			$var[] = array('access' => 'users_unblock', 'text' => 'Can block/unblock a user access', 'start' => array('is_start' => 0));

			// group control
			$var[] = array('access' => 'groups_index', 'text' => 'Can see the group list', 'start' => array('is_start' => 1, 'text' => 'Group Control'));
			$var[] = array('access' => 'groups_edit', 'text' => 'can edit a group');
			$var[] = array('access' => 'groups_delete', 'text' => 'can delete a group');
			$var[] = array('access' => 'groups_view', 'text' => 'can view the group\'s profile');
			$var[] = array('access' => 'groups_create', 'text' => 'can create a group', 'start' => array('is_start' => 0));

			// customers view control
			$var[] = array('access' => 'full_view', 'text' => 'Can read all the informations about customers', 'start' => array('is_start' => 1, 'text' => 'Customers View Control'));
			$var[] = array('access' => 'all_tables_read', 'text' => 'Can read all the tables');
			$var[] = array('access' => 'ib_read', 'text' => 'can read Introducing Brokers');
			$var[] = array('access' => 'franchisescheme_read', 'text' => 'can read Franchise Scheme');
			$var[] = array('access' => 'whitelabel_read', 'text' => 'can read white label');
			$var[] = array('access' => 'seniorpartner_read', 'text' => 'can read senior partners');
			$var[] = array('access' => 'callback_read', 'text' => 'can read callback');
			$var[] = array('access' => 'inquiry_read', 'text' => 'can read inquiry');
			$var[] = array('access' => 'small_registration_read', 'text' => 'can read small registration');
			$var[] = array('access' => 'forexblog_ib_registration_read', 'text' => 'can read forexblog');
			$var[] = array('access' => 'promotions_read', 'text' => 'can read promotions');
			$var[] = array('access' => 'videoconference_read', 'text' => 'can read video conference');
			$var[] = array('access' => 'demoaccount_read', 'text' => 'can read demo account');
			$var[] = array('access' => 'fb_home_read', 'text' => 'can read facebook');
			$var[] = array('access' => 'pay_order_info_read', 'text' => 'can read Din pay');
			$var[] = array('access' => 'cmginfo_read', 'text' => 'can read CMG');
			$var[] = array('access' => 'customers_index', 'text' => 'Allow to see the customers menu');
			$var[] = array('access' => 'customers_delete', 'text' => 'Can delete a customer');
			$var[] = array('access' => 'customers_update', 'text' => 'can update a customer');
			$var[] = array('access' => 'filters_lang_use', 'text' => 'Can use the language filter');
			$var[] = array('access' => 'filters_date_use', 'text' => 'can use the date filter');
			$var[] = array('access' => 'filters_multi_use', 'text' => 'Can use one filter by column', 'start' => array('is_start' => 0));

			// Others
			$var[] = array('access' => 'ajax_dashboard', 'text' => 'Can see the stats on the dashboard', 'start' => array('is_start' => 1, 'text' => 'Others'));
			$var[] = array('access' => 'message_send', 'text' => 'Can send messages (not yet)');
			$var[] = array('access' => 'notifications_send', 'text' => 'Can send Live notification (not yet)');
			$var[] = array('access' => 'notifications_receive', 'text' => 'Can receive Live notification (not yet)');
			$var[] = array('access' => 'charts_index', 'text' => 'Can access the charts (not yet)');
			$var[] = array('access' => 'speed_loading_read', 'text' => 'Can see the loading speed', 'start' => array('is_start' => 0));
			return $var;
		}


}