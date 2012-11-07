<?php

class Model_Group extends \Orm\Model
{
	public static function access($group){
		$access = DB::select()->from('sentry_groups')->where('id', $group)->execute()->as_array();
		return $access;
	}

	public static function createGroup($name, $permissions){
		$success = DB::insert('sentry_groups')->columns(array('name', 'permissions'))->values(array($name, $permissions))->execute();
		return $success;
		// return $success;
	}

	public static function editGroup($id, $name, $permissions){
		$success = DB::update('sentry_groups')->set(array('name' => $name, 'permissions' => $permissions))->where('id', $id)->execute();
		return $success;
	}
}