<?php

class Controller_Base extends Controller_Template{

	public function before(){
		parent::before();

		if(Request::active()->action != 'login' && !Sentry::check() && Request::active()->action != '404')
			Response::redirect('login');

		$this->current_user = self::current_user();

		View::set_global('current_user', self::current_user());
		if(Sentry::check()){
			// logout if banned
			
			if(Sentry::attempts($this->current_user->username)->get() == Sentry::attempts()->get_limit()){
				Session::set_flash('Your account has been blocked');
				Sentry::logout();
				Response::redirect('login');
			} 
		}
		

		View::set_global('site_title', 'IKON Backend');
		View::set_global('separator', '/');
		foreach(Model_Forms::find('all') as $k => $form){
			$this->tables[$k]['cleanName'] = $form->cleanName;
			$this->tables[$k]['url'] = $form->url;
			$this->tables[$k]['table'] = $form->table;
		}
		
		View::set_global('tables', $this->tables);



	}

	public function current_user(){
		return Sentry::check() ? Sentry::user() : '';
	}

	public function current_table($str = null, $key = null){

		if($str == null)
			 throw new \InvalidArgumentException('A string contained in the array tables should be defined as first parameter to find the relative key');

		if(gettype($str) !== 'string')
			throw new \InvalidArgumentException('current table expect first argument to be a string');

		while($element = current($this->tables)) {

		    if(array_search($str, $element)){

		    	if($key == null)
		    		return $this->tables[key($this->tables)];
		    	return $this->tables[key($this->tables)][$key];
			}
		    next($this->tables);
		}
		return false;
	}

}

	

?>