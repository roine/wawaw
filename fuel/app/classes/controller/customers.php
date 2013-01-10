<?php

class Controller_Customers extends Controller_Base
{
	

	public function before(){

		// check right accesses
		if(!Sentry::user()->has_access('customers_index')) self::no_access();

		// inherit parent
		parent::before();

		$this->languages = array();
		if(Sentry::user()->has_access('customers_en')) array_push($this->languages, 'en');
		if(Sentry::user()->has_access('customers_ru')) array_push($this->languages, 'ru');
		if(Sentry::user()->has_access('customers_cn')) array_push($this->languages, 'cn');
		if(Sentry::user()->has_access('customers_tw')) array_push($this->languages, 'tw');
		


		View::set_global('language', $this->languages);
		// assets
		$this->template->js = Asset::js(array(
			'mylibs/jquery.chosen.js', 
			'mylibs/jquery.ba-resize.js', 
			'mylibs/jquery.easing.1.3.js', 
			'mylibs/jquery.ui.touch-punch.js',
			'/mylibs/jquery.jgrowl.js',
			'mylibs/jquery-fallr-1.2.js',
			'script.js',
			// datatables libs, plugins, config
			'mylibs/jquery.dataTables.1.9.4.min.js', 
			'mylibs/dataTables/jquery.jeditable.js',
			'mylibs/dataTables/jquery.dataTables.editable.js',
			'mylibs/dataTables/ColVis.js',
			'mylibs/dataTables/ZeroClipboard.js',
			'mylibs/dataTables/TableTools.min.js',
			'mylibs/dataTables/FixedHeader.min.js',
			'libs/date.js',
			'datatables.configuration.js',

			));
		$this->template->css = Asset::css(array(
			'external/jquery-ui-1.8.16.custom.css',
			'ColVis.css',
			'TableTools.css',
			// 'prettyCheckable.css',
			'sprite.tables.css',
			));
		$this->template->less = Asset::less(array('customic.less'));
		// set the global to get the table url, name, clean name

		// View::set_global('current_table',$this->current_table(Request::active()->action));
		// $this->current_table = $this->current_table(Request::active()->action);

		if(!empty(Request::active()->method_params)){
			View::set_global('current_table',$this->current_table(Request::active()->method_params[0]));
			$this->current_table = $this->current_table(Request::active()->method_params[0]);
		}
		else{
			View::set_global('current_table',$this->current_table(Request::active()->action));
			$this->current_table = $this->current_table(Request::active()->action);
		}
		
		$this->template->h2 = $this->current_table['cleanName'];
		
	}

	public function action_i($form){
		if(empty($form)){
			self::no_access();
		}

		if($this->current_table){
			if(!Sentry::user()->has_access('customers_'.$this->current_table['table'].'_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

			if($this->current_table['cleanName'] == 'All'){
				View::set_global('columns', array('id', 'Full Name', 'Country', 'City', 'Telephone', 'Mobile Phone', 'E-mail', 'Language', 'platform', 'type','created_at'));
			}else{
				View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns($this->current_table['table'])));
			}
			$this->template->title = 'Customers &raquo; '.$this->current_table['cleanName'];
			$this->template->content = View::forge('customers/view');
		}
		else
			Response::redirect('/404');
		
	}

	public function no_access(){
		Session::set_flash('error', 'You cannot access these informations');	
		Response::redirect('/');
	}

	public function action_all(){
		if(!Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'Full Name', 'Country', 'City', 'Telephone', 'Mobile Phone', 'E-mail', 'Language', 'platform', 'type','created_at'));
		$this->template->title = 'All the customers';
		$this->template->content = View::forge('customers/view');
	}

	public function action_introducing_brokers()
	{
		if(!Sentry::user()->has_access('customers_ib_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'Full Name', 'Gender', 'Country', 'State', 'Telephone', 'Mobile Phone', 'E-mail', 'Clients', 'Position', 'Interested In', 'business website', 'referer', 'language spoken', 'question', 'from', 'website', 'created_at', 'ip'));

		$this->template->title = 'Customers &raquo; Introducing brokers';
		$this->template->content = View::forge('customers/view');
	}

	public function action_franchise_scheme()
	{
		if(!Sentry::user()->has_access('customers_franchisescheme_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'full name', 'company', 'country', 'state', 'telephone', 'mobile phone', 'e-mail', 'position', 'business website', 'question', 'from', 'website', 'created_at', 'ip'));

		$this->template->title = 'Customers &raquo; Franchise scheme';
		$this->template->content = View::forge('customers/view');
	}

	public function action_white_label()
	{
		if(!Sentry::user()->has_access('customers_whitelabel_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'full name', 'company', 'country', 'state', 'telephone', 'mobile phone', 'e-mail', 'clients', 'position', 'business website', 'question', 'from', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; White label';
		$this->template->content = View::forge('customers/view');
	}

	public function action_senior_partners()
	{
		if(!Sentry::user()->has_access('customers_seniorpartner_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'full name', 'company', 'country', 'state', 'telephone', 'mobile phone', 'e-mail', 'position', 'business website', 'question', 'from', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; Senior partners';
		$this->template->content = View::forge('customers/view');
	}

	public function action_callback()
	{
		if(!Sentry::user()->has_access('customers_callback_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'send to', 'first name', 'lastname', 'country', 'state', 'city', 'telephone', 'mobile phone', 'e-mail', 'language spoken', 'previous visit', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; Callback';
		$this->template->content = View::forge('customers/view');
	}

	public function action_inquiry()
	{
		if(!Sentry::user()->has_access('customers_inquiry_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array('id', 'send to', 'full name', 'e-mail', 'country', 'state', 'city', 'mobile phone', 'telephone', 'inquiry', 'from', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; Inquiry';
		$this->template->content = View::forge('customers/view');
	}

	public function action_small_registration()
	{
		if(!Sentry::user()->has_access('customers_small_registration_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();
		
		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('small_registration')));
		$this->template->title = 'Customers &raquo; Small registration';
		$this->template->content = View::forge('customers/view');
	}

	public function action_forexblog()
	{
		if(!Sentry::user()->has_access('customers_forexblog_ib_registration_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('forexblog_ib_registration')));
		$this->template->title = 'Customers &raquo; Forexblog';
		$this->template->content = View::forge('customers/view');
	}

	public function action_promotions()
	{
		if(!Sentry::user()->has_access('customers_promotions_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('promotions')));
		$this->template->title = 'Customers &raquo; Promotions';
		$this->template->content = View::forge('customers/view');
	}

	public function action_video_conference()
	{
		if(!Sentry::user()->has_access('customers_videoconference_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('videoconference')));
		$this->template->title = 'Customers &raquo; Video conference';
		$this->template->content = View::forge('customers/view');
	}

	public function action_demo_account()
	{
		if(!Sentry::user()->has_access('customers_demoaccount_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('demoaccount')));
		$this->template->title = 'Customers &raquo; Demo accounts';
		$this->template->content = View::forge('customers/view');
	}

	public function action_facebook()
	{
		if(!Sentry::user()->has_access('customers_fb_home_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('fb_home')));
		$this->template->title = 'Customers &raquo; Facebook';
		$this->template->content = View::forge('customers/view');
	}

	public function action_pay_order()
	{
		if(!Sentry::user()->has_access('customers_pay_order_info_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('pay_order_info')));
		$this->template->title = 'Customers &raquo; Pay order';
		$this->template->content = View::forge('customers/view');
	}

	public function action_cmg()
	{
		if(!Sentry::user()->has_access('customers_cmginfo_read') && !Sentry::user()->has_access('customers_all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('cmginfo')));
		$this->template->title = 'Customers &raquo; Cmg';
		$this->template->content = View::forge('customers/cmg');
	}

}
