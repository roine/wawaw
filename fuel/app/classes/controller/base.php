<?php

class Controller_Base extends Controller_Template{

	public function before(){
		parent::before();
		
		View::set_global('current_user', self::current_user());
		View::set_global('site_title', 'IKON Backend');
		View::set_global('separator', '/');
		$this->tables = array(
			array('CleanName' => 'All',
				'url' => 'all',
				'table' => 'all'),
			array('CleanName' => 'Introducing Brokers',
				'url' => 'introducing_brokers',
				'table' => 'ib'),
			array('CleanName' => 'Franchise Scheme',
				'url' => 'franchise_scheme',
				'table' => 'franchisescheme'),
			array('CleanName' => 'White Label',
				'url' => 'white_label',
				'table' => 'whitelabel'),
			array('CleanName' => 'Senior Partners',
				'url' => 'senior_partners',
				'table' => 'seniorpartner'),
			array('CleanName' => 'Callback',
				'url' => 'callback',
				'table' => 'callback'),
			array('CleanName' => 'Inquiry',
				'url' => 'inquiry',
				'table' => 'inquiry'),
			array('CleanName' => 'Small Registration',
				'url' => 'small_registration',
				'table' => 'small_registration'),
			array('CleanName' => 'Forexblog',
				'url' => 'forexblog',
				'table' => 'forexblog_ib_registration'),
			array('CleanName' => 'Promotions',
				'url' => 'promotions',
				'table' => 'promotions'),
			array('CleanName' => 'Video Conference',
				'url' => 'video_conference',
				'table' => 'videoconference'),
			array('CleanName' => 'Demo Account',
				'url' => 'demo_account',
				'table' => 'demoaccount'),
			array('CleanName' => 'Facebook Registration',
				'url' => 'facebook',
				'table' => 'fb_home'),
			array('CleanName' => 'Din Pay',
				'url' => 'pay_order',
				'table' => 'pay_order_info'),
			array('CleanName' => 'CMG',
				'url' => 'cmg',
				'table' => 'cmginfo'));
		View::set_global('tables', $this->tables);
	}

	public function current_user(){
		return Sentry::check() ? Sentry::user() : '';
	}

	public function current_table($str = null, $key = null){

		if($str == null)
			 throw new \InvalidArgumentException('A string contained in the array tables should be defined as first parameter to find the relative key');

		while($element = current($this->tables)) {
		    if(array_search($str, $element)){
		    	if($key == null)
		    		return $this->tables[key($this->tables)];
		    	return $this->tables[key($this->tables)][$key];
			}
		    next($this->tables);
		}
	}

}

	

?>