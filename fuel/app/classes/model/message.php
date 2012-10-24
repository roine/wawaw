<?php

class Model_Message extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'subject',
		'content',
		'parent_id',
		'to',
		'from',
		'read',
		'from_delete',
		'to_delete',
		'created_at',
		'updated_at'
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	static public function messageWith($to, $from){
		if($to == null || $from == null)
			return null;
		$req = DB::select()->from('messages')->where_open()
				->where('from', $from->id)->and_where('to', $to->id)
				->where_close();
		return $req->execute()->as_array();
	}
}
