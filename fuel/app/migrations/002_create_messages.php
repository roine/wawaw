<?php

namespace Fuel\Migrations;

class Create_messages
{
	public function up()
	{
		\DBUtil::create_table('messages', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'subject' => array('constraint' => 255, 'type' => 'varchar'),
			'content' => array('type' => 'text'),
			'parent_id' => array('constraint' => 11, 'type' => 'int'),
			'to' => array('constraint' => 11, 'type' => 'int'),
			'from' => array('constraint' => 11, 'type' => 'int'),
			'read' => array('type' => 'bool'),
			'from_delete' => array('type' => 'bool'),
			'to_delete' => array('type' => 'bool'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('messages');
	}
}