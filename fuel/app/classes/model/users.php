<?php
use Orm\Model;

class Model_Users extends Model
{
	protected static $_table_name = 'sentry_users';

	protected static $_properties = array(
		'id',
		'username',
		'password',
		'group',
		'email',
		'last_login',
		'login_hash',
		'created_at',
	);

	protected static $_observers = array(
		'Orm\\Observer_Typing' => array('before_save', 'after_save', 'after_load')
	);
	
	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		if($factory == 'create'){
			$val->add_field('username', 'Username', 'required|max_length[50]');
			$val->add_field('password', 'Password', 'required|max_length[255]');
			$val->add_field('r_password', 'Password', 'match_field[password]');
			// $val->add_field('', '', '');
			$val->add_field('email', 'Email', 'required|max_length[255]|valid_email');
		}
		else if($factory == 'edit'){

		}
		
		return $val;
	}

}
