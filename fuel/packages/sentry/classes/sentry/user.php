<?php
/**
 * Part of the Sentry package for FuelPHP.
 *
 * @package    Sentry
 * @version    2.1
 * @author     Cartalyst LLC
 * @license    MIT License
 * @copyright  2011 - 2012 Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Sentry;

use ArrayAccess;
use Config;
use DB;
use FuelException;
use Iterator;
use Lang;
use Str;
use Arr;
use Format;
use Request;
use Inflector;

class SentryUserException extends \FuelException {}
class SentryUserNotFoundException extends \SentryUserException {}
class SentryPermissionsException extends \SentryUserException {}
class SentryPermissionDenied extends SentryPermissionsException {}

/**
 * Sentry Auth User Class
 *
 * @package  Sentry
 * @author   Daniel Petrie
 */

/**
 * Sentry v2.0 Updates
 *
 * @package  Sentry
 * @auth     Daniel Berry
 */
class Sentry_User implements Iterator, ArrayAccess
{
	/**
	 * @var  string  Database instance
	 */
	protected $db_instance = null;

	/**
	 * @var  array  User
	 */
	protected $user = array();

	/**
	 * @var  array  Passwords
	 */
	protected $passwords = array();

	/**
	 * @var  array  Password Fields
	 */
	protected $password_fields = array(
		'password',
		'password_reset_hash',
		'temp_password',
		'remember_me',
		'activation_hash',
	);

	/**
	 * @var  array  Groups
	 */
	protected $groups = array();

	/**
	 * @var  object  Hashing Object
	 */
	protected $hash = null;

	/**
	 * @var  string  Table name
	 */
	protected $table = null;

	/**
	 * @var  string  User metadata table
	 */
	protected $table_metadata = null;

	/**
	 * @var  string  User groups table
	 */
	protected $table_usergroups = null;

	/**
	 * @var  string  Login column
	 */
	protected $login_column = null;

	/**
	 * @var  string  Login column string (formatted)
	 */
	protected $login_column_str = '';

	/**
	 * @var  array  Contains the merged user & group permissions
	 */
	protected $permissions = array();

	/**
	 * @var  array  Contains the rules from the sentry config
	 */
	protected $rules = array();

	/**
	 * Loads in the user object
	 *
	 * @param   int|string  User id or Login Column value
	 * @return  void
	 * @throws  SentryUserNotFoundException
	 */
	public function __construct($id = null, $check_exists = false)
	{
		// load and set config
		$this->table = strtolower(Config::get('sentry.table.users'));
		$this->table_usergroups = strtolower(Config::get('sentry.table.users_groups'));
		$this->table_metadata = strtolower(Config::get('sentry.table.users_metadata'));
		$this->groups_table = Config::get('sentry.table.groups');
		$this->login_column = strtolower(Config::get('sentry.login_column'));
		$this->login_column_str = ucfirst($this->login_column);
		$_db_instance = trim(Config::get('sentry.db_instance'));

		try
		{
			// init a hashing mechanism
			$strategy = Config::get('sentry.hash.strategy');
			$options = Config::get('sentry.hash.strategies.'.$strategy);
			$this->hash = Sentry_Hash_Driver::forge($strategy, $options);
		}
		catch (SentryGroupNotFoundException $e)
		{
			throw new \SentryUserException($e->getMessage());
		}

		// db_instance check
		if ( ! empty($_db_instance) )
		{
			$this->db_instance = $_db_instance;
		}

		// if an ID was passed
		if ($id)
		{
			// make sure ID is valid
			if (is_int($id))
			{
				if ($id <= 0)
				{
					throw new \SentryUserException(__('sentry.invalid_user_id'));
				}
				// set field to id for query
				$field = 'id';
			}
			// if ID is not an integer
			else
			{
				// set field to login_column
				$field = $this->login_column;
			}

			//query database for user
			$user = DB::select()
				->from($this->table)
				->where($field, $id)
				->execute($this->db_instance);

			// if there was a result - update user
			if (count($user))
			{
				// if just a user exists check - return true, no need for additional queries
				if ($check_exists)
				{
					return true;
				}

				$temp = $user->current();

				// query for metadata
				$metadata = DB::select()
					->from($this->table_metadata)
					->where('user_id', $temp['id'])
					->execute($this->db_instance);

				$temp['metadata'] = (count($metadata)) ? $metadata->current() : array();

				// lets set and remove password fields
				$temp = $this->extract_passwords($temp);

				$this->user = $temp['user'];
				$this->passwords = $temp['passwords'];
			}
			// user doesn't exist
			else
			{
				throw new \SentryUserNotFoundException(__('sentry.user_not_found'));
			}

			/**
			 * fetch the user's groups and assign as array usable via $this->groups
			 */
			
			$this->groups = DB::select($this->groups_table.'.*')
				->from($this->groups_table)
				->where($this->table_usergroups.'.user_id', '=', $this->user['id'])
				->join($this->table_usergroups)
				->on($this->table_usergroups.'.group_id', '=', $this->groups_table.'.id')
				->execute($this->db_instance)->as_array();

			/**
			 * set rules and permissions if enabled
			 */
			if (Config::get('sentry.permissions.enabled'))
			{
				$this->rules = static::fetch_rules();
				$this->permissions = static::fetch_permissions();
			}
		}
		
		/**
		* If no user logged in, place in guest group if configured
		*/
		
		$this->guest_group = Config::get('sentry.permissions.guest_group');

		if (! $this->user)
		{
			if (is_int($this->guest_group))
			{
				// Fetch the configured guest group
				$this->groups = DB::select($this->groups_table.'.*')
					->from($this->groups_table)
					->where('id', '=', $this->guest_group)
					->execute($this->db_instance)->as_array();

				// Throw exception if group doesn't exist
				if (empty($this->groups))
				{
					throw new \SentryAuthConfigException(__('sentry.nonexistent_guest_group_id', array('id' => $this->guest_group)));
				}

				/**
				 * set (guest) rules and permissions if enabled
				 */
				if (Config::get('sentry.permissions.enabled'))
				{
					$this->rules = static::fetch_rules();
					$this->permissions = static::fetch_permissions();
				}
			}
			else
			{
				// Throw exception if something else than int or false is configured
				if ($this->guest_group != false)
				{
					throw new \SentryAuthConfigException(__('sentry.invalid_guest_group'));
				}
			}
		}
	}

	/**
	 * Register a user - Alias of create()
	 *
	 * @param   array  User array for creation
	 * @return  int
	 * @throws  SentryUserException
	 */
	public function register($user)
	{
		return $this->create($user, true);
	}

	/**
	 * Create's a new user.  Returns user 'id'.
	 *
	 * @param   array  User array for creation
	 * @return  int
	 * @throws  SentryUserException
	 */
	public function create(array $user, $activation = false)
	{
		// check for required fields
		if (empty($user[$this->login_column]) or empty($user['password']))
		{
			throw new \SentryUserException(
				__('sentry.column_and_password_empty', array('column' => $this->login_column_str))
			);
		}

		// if login_column is set to username - email is still required, so check
		if ($this->login_column != 'email' and empty($user['email']))
		{
			throw new \SentryUserException(
				__('sentry.column_email_and_password_empty', array('column' => $this->login_column_str))
			);
		}

		// check to see if login_column is already taken
		$user_exists = $this->user_exists($user[$this->login_column]);
		if ($user_exists)
		{
			// create new user object
			$user = new static((int) $user_exists['user']['id']);

			// check if account is not activated
			if ($activation and $user->get('activated') != 1)
			{
				// update and resend activation code
				$hash = Str::random('alnum', 24);

				$update = array(
					'activation_hash' => $hash
				);

				if ($user->update($update))
				{
					return array(
						'id'   => $user->user['id'],
						'hash' => base64_encode($user[$user->login_column]).'/'.$hash
					);
				}
				return false;
			}

			// if login_column is not set to email - also check to make sure email doesn't exist
			if ($this->login_column != 'email' and $this->user_exists($user['email'], 'email'))
			{
				throw new \SentryUserException(__('sentry.email_already_in_use'));
			}
			throw new \SentryUserException(
				__('sentry.column_already_exists', array('column' => $this->login_column_str))
			);
		}

		// set new user values
		$new_user = array(
			$this->login_column => $user[$this->login_column],
			'password' => $this->hash->create_password($user['password']),
			'created_at' => time(),
			'activated' => (bool) ($activation) ? false : true,
			'status' => 1,
		) + $user;

		// check for metadata
		if (array_key_exists('metadata', $new_user))
		{
			$metadata = $new_user['metadata'];
			unset($new_user['metadata']);
		}
		else
		{
			$metadata = array();
		}

		// set activation hash if activation = true
		if ($activation)
		{
			$hash = Str::random('alnum', 24);
			$new_user['activation_hash'] = $this->hash->create_password($hash);
		}

		// insert new user
		list($insert_id, $rows_affected) = DB::insert($this->table)->set($new_user)->execute($this->db_instance);

		// insert into metadata
		$metadata = array(
			'user_id' => $insert_id
		) + $metadata;

		DB::insert($this->table_metadata)->set($metadata)->execute($this->db_instance);

		// return activation hash for emailing if activation = true
		if ($activation)
		{
			// return array of id and hash
			if ($rows_affected > 0)
			{
				return array(
					'id'   => (int) $insert_id,
					'hash' => base64_encode($user[$this->login_column]).'/'.$hash
				);
			}

			return false;
		}
		return ($rows_affected > 0) ? (int) $insert_id : false;
	}

	/**
	 * Update the current user
	 *
	 * @param   array  Fields to update
	 * @param   bool   Whether to hash the password
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function update(array $fields, $hash_password = true)
	{
		// make sure a user id is set
		if (empty($this->user))
		{
			throw new \SentryUserException(__('sentry.no_user_selected'));
		}

		// init update array
		$update = array();

		// init user metatdata
		$update_metadata = null;

		if (array_key_exists($this->login_column, $fields) and
			$fields[$this->login_column] != $this->user[$this->login_column] and
			$this->user_exists($fields[$this->login_column]))
		{
			throw new \SentryUserException(
				__('sentry.column_already_exists', array('column' => $this->login_column_str))
			);
		}
		elseif (array_key_exists($this->login_column, $fields) and
				$fields[$this->login_column] == '')
		{
			throw new \SentryUserException(
				__('sentry.column_is_empty', array('column' => $this->login_column_str))
			);
		}
		elseif (array_key_exists($this->login_column, $fields))
		{
			$update[$this->login_column] = $fields[$this->login_column];
			unset($fields[$this->login_column]);
		}

		// if updating email
		if (array_key_exists('email', $fields) and
			$fields['email'] != $this->user['email'])
		{
			// make sure email does not already exist
			if ($this->user_exists($fields['email'], 'email'))
			{
				throw new \SentryUserException(__('sentry.email_already_in_use'));
			}
			$update['email'] = $fields['email'];
			unset($fields['email']);
		}

		// if updating username
		if (array_key_exists('username', $fields) and
			$fields['username'] != $this->user['username'])
		{
			// make sure username does not already exist
			if ($this->user_exists($fields['username'], 'username'))
			{
				throw new \SentryUserException(__('sentry.username_already_in_use'));
			}
			$update['username'] = $fields['username'];
			unset($fields['username']);
		}

		// if updating username
		if (array_key_exists('username', $fields) and
			$fields['username'] != $this->user['username'])
		{
			// make sure email does not already exist
			if ($this->user_exists($fields['username'], 'username'))
			{
				throw new \SentryUserException(__('sentry.username_already_in_use'));
			}
			$update['username'] = $fields['username'];
			unset($fields['username']);
		}

		// update password
		if (array_key_exists('password', $fields))
		{
			if (empty($fields['password']))
			{
				throw new \SentryUserException(__('sentry.password_empty'));
			}
			if ($hash_password)
			{
				$fields['password'] = $this->hash->create_password($fields['password']);
			}
			$update['password'] = $fields['password'];
			unset($fields['password']);
		}

		// update temp password
		if (array_key_exists('temp_password', $fields))
		{
			if ( ! empty($fields['temp_password']))
			{
				$fields['temp_password'] = $this->hash->create_password($fields['temp_password']);
			}
			$update['temp_password'] = $fields['temp_password'];
			unset($fields['temp_password']);
		}

		// update password reset hash
		if (array_key_exists('password_reset_hash', $fields))
		{
			if ( ! empty($fields['password_reset_hash']))
			{
				$fields['password_reset_hash'] = $this->hash->create_password($fields['password_reset_hash']);
			}
			$update['password_reset_hash'] = $fields['password_reset_hash'];
			unset($fields['password_reset_hash']);
		}

		// update remember me cookie hash
		if (array_key_exists('remember_me', $fields))
		{
			if ( ! empty($fields['remember_me']))
			{
				$fields['remember_me'] = $this->hash->create_password($fields['remember_me']);
			}
			$update['remember_me'] = $fields['remember_me'];
			unset($fields['remember_me']);
		}

		if (array_key_exists('activation_hash', $fields))
		{
			if ( ! empty($fields['activation_hash']))
			{
				$fields['activation_hash'] = $this->hash->create_password($fields['activation_hash']);
			}
			$update['activation_hash'] = $fields['activation_hash'];
			unset($fields['activation_hash']);
		}

		if (array_key_exists('last_login', $fields) and ! empty($fields['last_login']) and is_int($fields['last_login']))
		{
			$update['last_login'] = $fields['last_login'];
			unset($fields['last_login']);
		}

		if (array_key_exists('ip_address', $fields))
		{
			$update['ip_address'] = $fields['ip_address'];
			unset($fields['ip_address']);
		}

		if (array_key_exists('activated', $fields))
		{
			$update['activated'] = $fields['activated'];
			unset($fields['activated']);
		}

		if (array_key_exists('status', $fields))
		{
			$update['status'] = $fields['status'];
			unset($fields['status']);
		}

		if (array_key_exists('permissions', $fields))
		{
			$update['permissions'] = $fields['permissions'];
			unset($fields['permissions']);
		}

		if (empty($update) and empty($fields['metadata']))
		{
			return true;
		}

		// add update time
		$update['updated_at'] = time();

		// update user table
		if ($update)
		{
			$update_user = DB::update($this->table)
				->set($update)
				->join($this->table_metadata)->on($this->table_metadata.'.user_id', '=', $this->table.'.id')
				->where('id', $this->user['id'])
				->execute($this->db_instance);
		}

		// update metadata table
		if ( ! empty($fields['metadata']))
		{
			$update_metadata = DB::update($this->table_metadata)
				->set($fields['metadata'])
				->where('user_id', $this->user['id'])
				->execute($this->db_instance);
		}
		else
		{
			$fields['metadata'] = array();
		}

		if ($update_user or $update_metadata)
		{
			$update['metadata'] = $fields['metadata'] + $this->user['metadata'];

			// lets remove passwords from global user array
			$update = $this->extract_passwords($update);

			// change user values in object
			$this->user = $update['user'] + $this->user;
			$this->passwords = $update['passwords'] + $this->passwords;

			return true;
		}

		return false;
	}

	/**
	 * Delete's the current user.
	 *
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function delete()
	{
		// make sure a user id is set
		if (empty($this->user))
		{
			throw new \SentryUserException(__('sentry.no_user_selected_to_delete'));
		}

		DB::start_transaction();

		try
		{
			// delete users groups
			$delete_user_groups = DB::delete($this->table_usergroups)
				->where('user_id', $this->user['id'])
				->execute($this->db_instance);

			// delete users metadata
			$delete_user_metadata = DB::delete($this->table_metadata)
				->where('user_id', $this->user['id'])
				->execute($this->db_instance);

			// delete user from database
			$delete_user = DB::delete($this->table)
				->where('id', $this->user['id'])
				->execute($this->db_instance);
		}
		catch(\Database_Exception $e) {
			DB::rollback_transaction();
			return false;
		}

		DB::commit_transaction();

		// update user to null
		$this->user = array();
		$this->passwords = array();

		return true;

	}

	/**
	 * Enable a User
	 *
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function enable()
	{
		if ($this->user['status'] == 1)
		{
			throw new \SentryUserException(__('sentry.user_already_enabled'));
		}
		return $this->update(array('status' => 1));
	}

	/**
	 * Disable a User
	 *
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function disable()
	{
		if ($this->user['status'] == 0)
		{
			throw new \SentryUserException(__('sentry.user_already_disabled'));
		}
		return $this->update(array('status' => 0));
	}

	/**
	 * Checks if the Field is set or not.
	 *
	 * @param   string  Field name
	 * @return  bool
	 */
	public function __isset($field)
	{
		return array_key_exists($field, $this->user);
	}

	/**
	 * Gets a field value of the user
	 *
	 * @param   string  Field name
	 * @return  mixed
	 * @throws  SentryUserException
	 */
	public function __get($field)
	{
		return $this->get($field);
	}

	/**
	 * Gets a given field (or array of fields).
	 *
	 * @param   string|array  Field(s) to get
	 * @return  mixed
	 * @throws  SentryUserException
	 */
	public function get($field = null)
	{
		// make sure a user id is set
		if (empty($this->user['id']))
		{
			throw new \SentryUserException(__('sentry.no_user_selected_to_get'));
		}

		// if no fields were passed - return entire user
		if ($field === null)
		{
			return $this->user;
		}
		// if field is an array - return requested fields
		else if (is_array($field))
		{
			$values = array();

			// loop through requested fields
			foreach ($field as $key)
			{
				// see if field is a password field
				// see if field is a password field
				if (in_array($key, $this->password_fields))
				{
					$val = \Arr::get($this->passwords, $key, '__MISSING_KEY__');
				}
				else
				{
					 // check to see if field exists in user
					$val = \Arr::get($this->user, $key, '__MISSING_KEY__');
				}

				if ($val !== '__MISSING_KEY__')
				{
					$values[$key] = $val;
				}
				else
				{
					throw new \SentryUserException(
						__('sentry.not_found_in_user_object', array('field' => $key))
					);
				}
			}

			return $values;
		}
		// if single field was passed - return its value
		else
		{
			// see if field is a password field
			if (in_array($field, $this->password_fields))
			{
				$val = \Arr::get($this->passwords, $field, '__MISSING_KEY__');
			}
			else
			{
				// check to see if field exists in user
				$val = \Arr::get($this->user, $field, '__MISSING_KEY__');
			}

			// if val is not missing, return it
			if ($val !== '__MISSING_KEY__')
			{
				return $val;
			}

			throw new \SentryUserException(__('sentry.not_found_in_user_object', array('field' => $field)));
		}
	}

	/**
	 * Changes a user's password
	 *
	 * @param   string  The new password
	 * @param   string  Users old password
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function change_password($password, $old_password)
	{
		// make sure old password matches the current password
		if ( ! $this->check_password($old_password))
		{
			throw new \SentryUserException(__('sentry.invalid_old_password'));
		}

		return $this->update(array('password' => $password));
	}

	/**
	 * Returns an array of groups the user is part of.
	 *
	 * @return  array
	 */
	public function groups()
	{
		return $this->groups;
	}

	/**
	 * Adds this user to the group.
	 *
	 * @param   string|int  Group ID or group name
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function add_to_group($id)
	{
		if ($this->in_group($id))
		{
			throw new \SentryUserException(__('sentry.user_already_in_group', array('group' => $id)));
		}

		$field = 'name';
		if (is_numeric($id))
		{
			$field = 'id';
		}

		try
		{
			$group = new \Sentry_Group($id);
		}
		catch (SentryGroupNotFoundException $e)
		{
			throw new \SentryUserException($e->getMessage());
		}

		list($insert_id, $rows_affected) = DB::insert($this->table_usergroups)->set(array(
			'user_id' => $this->user['id'],
			'group_id' => $group->get('id'),
		))->execute($this->db_instance);

		$this->groups[] = array(
			'id'       => $group->get('id'),
			'name'     => $group->get('name'),
		);

		return true;
	}

	/**
	 * Removes this user from the group.
	 *
	 * @param   string|int  Group ID or group name
	 * @return  bool
	 * @throws  SentryUserException
	 */
	public function remove_from_group($id)
	{
		if ( ! $this->in_group($id))
		{
			throw new \SentryUserException(__('sentry.user_not_in_group', array('group' => $id)));
		}

		$field = 'name';
		if (is_numeric($id))
		{
			$field = 'id';
		}

		try
		{
			$group = new \Sentry_Group($id);
		}
		catch (SentryGroupNotFoundException $e)
		{
			throw new \SentryUserException($e->getMessage());
		}

		$delete = DB::delete($this->table_usergroups)
				->where('user_id', $this->user['id'])
				->where('group_id', $group->get('id'))->execute($this->db_instance);

		// remove from array
		$field = 'name';
		if (is_numeric($id))
		{
			$field = 'id';
		}

		foreach ($this->groups as $key => $group)
		{
			if ($group[$field] == $id)
			{
				unset($this->groups[$key]);
			}
		}

		return (bool) $delete;
	}

	/**
	 * Checks if the current user is part of the given group.
	 *
	 * @param   string  Group name
	 * @return  bool
	 */
	public function in_group($name)
	{
		$field = 'name';
		if (is_numeric($name))
		{
			$field = 'id';
		}

		foreach ($this->groups as $group)
		{
			if ($group[$field] == $name)
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if user exists already
	 *
	 * @param   string  The Login Column value
	 * @param   string  Column to use for check
	 * @return  bool
	 */
	protected function user_exists($login, $field = null)
	{
		// set field value if null
		if ($field === null)
		{
			$field = $this->login_column;
		}

		// query db to check for login_column
		$result = DB::select()
			->from($this->table)
			->where($field, $login)
			->limit(1)
			->execute($this->db_instance)->current();

		if ($result)
		{
			$metadata = DB::select()
					->from($this->table_metadata)
					->where('user_id', $result['id'])
					->execute($this->db_instance);

			$result['metadata'] = (count($metadata)) ? $metadata->current() : array();

			// lets set and remove password fields
			$result = $this->extract_passwords($result);

			return $result;
		}

		return false;
	}

	/**
	 * Checks the given password to see if it matches the one in the database.
	 *
	 * @param   string  Password to check
	 * @param   string  Password type
	 * @return  bool
	 */
	public function check_password($password, $field = 'password')
	{
		if ($this->hash->check_password($password, $this->passwords[$field]))
		{
			return true;
		}

		if (Config::get('sentry.hash.convert.enabled') === true)
		{
			$strategy = Config::get('sentry.hash.convert.from');
			$options = Config::get('sentry.hash.strategies.'.$strategy);
			$hash = Sentry_Hash_Driver::forge($strategy, $options);

			if ($hash->check_password($password, $this->passwords[$field]))
			{
				$this->update(array(
					'password' => $password
				));

				return true;
			}
		}

		return false;
	}

	/**
	 * Return all users
	 *
	 * @return  array
	 */
	public function all()
	{
		return DB::select()->from($this->table)->execute($this->db_instance)->as_array();
	}

	/**
	 * Return user's custom permissions json
	 *
	 * @return  array|json
	 * @author  Daniel Berry
	 */
	public function permissions()
	{
		return $this->get('permissions');
	}

	/**
	 * Return user's merged permissions
	 *
	 * @return  array
	 * @author  Daniel Berry
	 */
	public function merged_permissions()
	{
		return $this->permissions;
	}

	/**
	 * Add/Update group permission rules.
	 *
	 * Usage:
	 *
	 * $permissions_to_add = array(
	 *      'blog_admin_create' => 1, // setting to 1 will add it to the group
	 *      'blog_admin_delete' => 0, // setting to zero will remove it from the group if it is in there.
	 * );
	 *
	 * Sentry::user()->update_permissions($permissions_to_add);
	 *
	 * @param array|string $rules
	 * @return bool
	 * @throws SentryPermissionsException
	 * @author Daniel Berry
	 */
	public function update_permissions($rules = array())
	{
		if (empty($rules))
		{
			throw new SentryPermissionsException('Oops, you forgot to specify any rules to add!');
		}

		// get the current permissions from the user column.
		$current_permissions = json_decode($this->user['permissions'], true);

		foreach ($rules as $key => $val)
		{
			if (in_array($key, $this->rules) or $key === Config::get('sentry.permissions.superuser'))
			{
				if (is_array($current_permissions) and ! empty($val))
				{
					$current_permissions = Arr::merge($current_permissions, array($key => $val));
				}
				if (is_array($current_permissions) and empty($val))
				{
					Arr::delete($current_permissions, $key);
				}
				else
				{
					$current_permissions = array($key => $val);
				}
			}
			else
			{
				throw new SentryPermissionsException(__('sentry.rule_not_found', array('rule' => $key)));
			}
		}

		if ( ! is_array($current_permissions))
		{
			return $this->update(array('permissions' => ''));
		}
		else
		{
			// let's update the permissions column.
			return $this->update(array('permissions' => Format::forge($current_permissions)->to_json()));
		}
	}


	/**
	 * Check to see if the user has access to a resource
	 *
	 * The user can specify a specific resource. If no resource is provided,
	 * then Sentry will generate the resource automatically. If the resource
	 * is found in the configured rules provided in the config file then the
	 * user's current merged permissions array will be checked.
	 *
	 * @param   null $resource
	 * @return  bool
	 * @throws  SentryPermissionDenied
	 * @author  Daniel Berry
	 */
	public function has_access($resource = null)
	{
		/**
		 * if we have a super user (this is the global administrator,
		 * GOD access, than just return true and skip checks
		 */
		if (in_array(Config::get('sentry.permissions.superuser'), $this->permissions))
		{
			return true;
		}


		/**
		 * Get the current page in our rule formate
		 * We'll use this if there is no $resource set and to check our array against.
		 */
		$module = Request::active()->module;
		$controller = str_replace('controller_', '', Str::lower(Inflector::denamespace(Request::active()->controller)));
		$method = '_'.Request::active()->action;

		if ( ! empty($module))
		{
			$current_resource = $module.'_'.$controller.$method;
		}
		else
		{
			$current_resource = $controller.$method;
		}

		/**
		 * if we have an array of resources, let's loop through them
		 * and if it's not an array just check the single resource
		 */
		if (is_array($resource))
		{
			foreach ($resource as $rule)
			{
				// if it is in the config rules & not in the array rules, than we don't have access.
				if (in_array($rule, $this->rules) and ! in_array($rule, $this->permissions) and $rule === $current_resource)
				{
					return false;
				}
			}
		}
		else
		{
			// assign $resource if empty.
			$resource = ($resource) ?: $current_resource;

			// if it is in the config rules & not in the array rules, than we don't have access.
			if (in_array($resource, $this->rules) and ! in_array($resource, $this->permissions))
			{
				return false;
			}
		}

		return true;
	}

	protected function extract_passwords($user)
	{
		$passwords = array();
		foreach ($user as $field => $value)
		{
			if (in_array($field, $this->password_fields))
			{
				$passwords[$field] = $value;
				unset($user[$field]);
			}
		}

		return array(
			'user' => $user,
			'passwords' => $passwords
		);
	}

	protected function fetch_rules()
	{
		// set rules array
		$rules = array();

		// get permissions file config options
		$permission_file = Config::get('sentry.permissions.file');

		// load global rules
		$rules = Config::get('sentry.permissions.rules');

		// see if we should use config files or not
		if ( $permission_file['name'] == 'config' or empty($permission_file['name']) or $permission_file['name'] == null)
		{
			// load current module specific rules if a module is active
			if (isset(\Request::active()->module))
			{
				\Config::load(\Request::active()->module, true);
				$module_permissions = Config::get(\Request::active()->module.'.rules');

				if (!empty($module_permissions))
				{
					$rules = Arr::merge($rules, $module_permissions);
				}
			}

			return $rules;
		}

		// loop through module paths and see if listed file exists
		$file = false;
		foreach (Config::get('module_paths') as $path)
		{
			$path = $path.\Request::active()->module.DS;

			if ( ! empty($permission_file['path']))
			{
				$path .= $permission_file['path'].DS.$permission_file['name'];
			}
			else
			{
				$path .= $permission_file['name'];
			}

			if (file_exists($path))
			{
				$file = $path;
				break;
			}
		}

		// if the file exists pull in rules if they are set
		if ($file)
		{
			// get file type
			switch ($permission_file['type'])
			{
				case 'ini':
					$info = parse_ini_file($file, true);
				break;

				case 'json':
					$info = json_decode(file_get_contents($file), true);
				break;

				case 'yaml':
					$info = \Format::forge(file_get_contents($file), 'yaml')->to_array();
				break;

				case 'php':
					$info = include($file);
				break;

				default: // move this somewhere else for easier debugging?
					throw new \SentryUserException(sprintf('Invalid permission file type "%s".', $type));
			}

			// now set rules if they exist
			if (isset($info['rules']))
			{
				$rules = $info['rules'];
			}
		}

		return $rules;
	}

	protected function fetch_permissions()
	{
		// set permissions arrray
		$permissions = array();

		// let's get the group permissions first.
		foreach ($this->groups as $group)
		{
			if ( ! empty($group['permissions']))
			{
				// group column permissions
				$group_permissions = json_decode($group['permissions'], true);

				foreach ($group_permissions as $key => $val)
				{
					if ( ! empty($key) and $val === 1)
					{
						$permissions = array_unique(Arr::merge($permissions, array($key)));
					}
					else
					{
						$permissions = Arr::merge(array_diff($permissions, array($key)));
					}
				}
			}
		}

		/**
		 * now let's merge the user's permissions
		 */
		if ( ! empty($this->user['permissions']))
		{
			// user column permissions
			$user_permissions = json_decode($this->user['permissions'], true);

			foreach ($user_permissions as $key => $val)
			{
				if (is_array($permissions) and $val === 1)
				{
					$permissions = array_unique(Arr::merge($permissions, array($key)));
				}
				elseif(is_array($permissions) and $val === 0)
				{
					$permissions = Arr::merge(array_diff($permissions, array($key)));
				}
				elseif( ! is_array($permissions) and $val === 1)
				{
					$permissions = array($val);
				}
			}
		}

		return array_values($permissions);
	}


	/**
	 * Implementation of the Iterator interface
	 */

	protected $_iterable = array();

	public function rewind()
	{
		$this->_iterable = $this->user;
		reset($this->_iterable);
	}

	public function current()
	{
		return current($this->_iterable);
	}

	public function key()
	{
		return key($this->_iterable);
	}

	public function next()
	{
		return next($this->_iterable);
	}

	public function valid()
	{
		return key($this->_iterable) !== null;
	}

	/**
	 * Sets the value of the given offset (class property).
	 *
	 * @param   string  $offset  class property
	 * @param   string  $value   value
	 * @return  void
	 */
	public function offsetSet($offset, $value)
	{
		$this->{$offset} = $value;
	}

	/**
	 * Checks if the given offset (class property) exists.
	 *
	 * @param   string  $offset  class property
	 * @return  bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->{$offset});
	}

	/**
	 * Unsets the given offset (class property).
	 *
	 * @param   string  $offset  class property
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		unset($this->{$offset});
	}

	/**
	 * Gets the value of the given offset (class property).
	 *
	 * @param   string  $offset  class property
	 * @return  mixed
	 */
	public function offsetGet($offset)
	{
		if (isset($this->{$offset}))
		{
			return $this->{$offset};
		}

		throw new \OutOfBoundsException('Property "'.$offset.'" not found for '.get_called_class().'.');
	}
}