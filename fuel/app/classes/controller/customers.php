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
		if(Sentry::user()->has_access('customers_tw')) array_push($this->languages, 'tw');
		if(Sentry::user()->has_access('customers_cn')) array_push($this->languages, 'cn');


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
		View::set_global('current_table',$this->current_table(Request::active()->action));
		$current_table = $this->current_table(Request::active()->action);
		$this->template->h2 = $current_table['CleanName'];
		
	}

	public function no_access(){
		Session::set_flash('error', 'You cannot access these informations');	
		Response::redirect('/');
	}

	public function action_all(){
		if(!Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'Full Name', 'Country', 'City', 'Telephone', 'Mobile Phone', 'E-mail', 'Language', 'platform', 'type','created_at'));
		$this->template->title = 'All the customers';
		$this->template->content = View::forge('customers/view');
	}

	public function action_introducing_brokers()
	{
		if(!Sentry::user()->has_access('ib_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'Full Name', 'Gender', 'Country', 'State', 'Telephone', 'Mobile Phone', 'E-mail', 'Clients', 'Position', 'Interested In', 'business website', 'referer', 'language spoken', 'question', 'from', 'website', 'created_at', 'ip'));

		$this->template->title = 'Customers &raquo; Introducing brokers';
		$this->template->content = View::forge('customers/view');
	}

	public function action_franchise_scheme()
	{
		if(!Sentry::user()->has_access('franchisescheme_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'full name', 'company', 'country', 'state', 'telephone', 'mobile phone', 'e-mail', 'position', 'business website', 'question', 'from', 'website', 'created_at', 'ip'));

		$this->template->title = 'Customers &raquo; Franchise scheme';
		$this->template->content = View::forge('customers/view');
	}

	public function action_white_label()
	{
		if(!Sentry::user()->has_access('whitelabel_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'full name', 'company', 'country', 'state', 'telephone', 'mobile phone', 'e-mail', 'clients', 'position', 'business website', 'question', 'from', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; White label';
		$this->template->content = View::forge('customers/view');
	}

	public function action_senior_partners()
	{
		if(!Sentry::user()->has_access('seniorpartner_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'full name', 'company', 'country', 'state', 'telephone', 'mobile phone', 'e-mail', 'position', 'business website', 'question', 'from', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; Senior partners';
		$this->template->content = View::forge('customers/view');
	}

	public function action_callback()
	{
		if(!Sentry::user()->has_access('callback_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'send to', 'first name', 'lastname', 'country', 'state', 'city', 'telephone', 'mobile phone', 'e-mail', 'language spoken', 'previous visit', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; Callback';
		$this->template->content = View::forge('customers/view');
	}

	public function action_inquiry()
	{
		if(!Sentry::user()->has_access('inquiry_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array('id', 'send to', 'full name', 'e-mail', 'country', 'state', 'city', 'mobile phone', 'telephone', 'inquiry', 'from', 'website', 'created_at', 'ip'));
		$this->template->title = 'Customers &raquo; Inquiry';
		$this->template->content = View::forge('customers/view');
	}

	public function action_small_registration()
	{
		if(!Sentry::user()->has_access('small_registration_read') && !Sentry::user()->has_access('all_read')) self::no_access();
		
		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('small_registration')));
		$this->template->title = 'Customers &raquo; Small registration';
		$this->template->content = View::forge('customers/view');
	}

	public function action_forexblog()
	{
		if(!Sentry::user()->has_access('forexblog_ib_registration_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('forexblog_ib_registration')));
		$this->template->title = 'Customers &raquo; Forexblog';
		$this->template->content = View::forge('customers/view');
	}

	public function action_promotions()
	{
		if(!Sentry::user()->has_access('promotions_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('promotions')));
		$this->template->title = 'Customers &raquo; Promotions';
		$this->template->content = View::forge('customers/view');
	}

	public function action_video_conference()
	{
		if(!Sentry::user()->has_access('videoconference_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('videoconference')));
		$this->template->title = 'Customers &raquo; Video conference';
		$this->template->content = View::forge('customers/view');
	}

	public function action_demo_account()
	{
		if(!Sentry::user()->has_access('demoaccount_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('demoaccount')));
		$this->template->title = 'Customers &raquo; Demo accounts';
		$this->template->content = View::forge('customers/view');
	}

	public function action_facebook()
	{
		if(!Sentry::user()->has_access('fb_home_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('fb_home')));
		$this->template->title = 'Customers &raquo; Facebook';
		$this->template->content = View::forge('customers/view');
	}

	public function action_pay_order()
	{
		if(!Sentry::user()->has_access('pay_order_info_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('pay_order_info')));
		$this->template->title = 'Customers &raquo; Pay order';
		$this->template->content = View::forge('customers/view');
	}

	public function action_cmg()
	{
		if(!Sentry::user()->has_access('cmginfo_read') && !Sentry::user()->has_access('all_read')) self::no_access();

		View::set_global('columns', array_map('Inflector::humanize', Model_Ajax::getColumns('cmginfo')));
		$this->template->title = 'Customers &raquo; Cmg';
		$this->template->content = View::forge('customers/cmg');
	}

}
