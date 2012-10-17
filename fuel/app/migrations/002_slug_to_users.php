<?php

namespace Fuel\Migrations;

class Slug_to_users
{
	public function up()
	{
		\DBUtil::add_fields('users', array(
    'slug' => array('constraint' => 100, 'type' => 'varchar')
));
	}

	public function down()
	{
		\DBUtil::drop_fields('users', 'slug');
	}
}