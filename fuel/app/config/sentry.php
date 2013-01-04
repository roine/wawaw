<?php

/**
 * Part of the Sentry package for FuelPHP.
 *
 * @package    Sentry
 * @version    2.0
 * @author     Cartalyst LLC
 * @license    MIT License
 * @copyright  2011 - 2012 Cartalyst LLC
 * @link       http://cartalyst.com
 */

return array(

	/**
	 * Database instance to use
	 * Leave this null to use the default 'active' db instance
	 * To use any other instance, set this to any instance that's defined in APPPATH/config/db.php
	 */
	'db_instance' => null,

	/*
	 * Table Names
	 */
	'table' => array(
		'users'           => 'sentry_users',
		'groups'          => 'sentry_groups',
		'users_groups'    => 'sentry_users_groups',
		'users_metadata'  => 'sentry_users_metadata',
		'users_suspended' => 'sentry_users_suspended',
	),

	/*
	 * Session keys
	 */
	'session' => array(
		'user'     => 'sentry_user',
		'provider' => 'sentry_provider',
	),

	/*
	 * Default Authorization Column - username or email
	 */
	'login_column' => 'username',

	/*
	 * Remember Me settings
	 */
	'remember_me' => array(

		/**
		 * Cookie name credentials are stored in
		 */
		'cookie_name' => 'sentry_rm',

		/**
		 * How long the cookie should last. (seconds)
		 */
		'expire' => 1209600, // 2 weeks
	),

	/**
	 * Limit Number of Failed Attempts
	 * Suspends a login/ip combo after a # of failed attempts for a set amount of time
	 */
	'limit' => array(

		/**
		 * enable limit - true/false
		 */
		'enabled' => true,

		/**
		 * number of attempts before suspensions
		 */
		'attempts' => 5,

		/**
		 * suspension length - minutes
		 */
		'time' => 15,
	),

	/**
	 * Password Hashing
	 * Sets hashing strategies for passwords
	 * Note: you may have to adjust all password related fields in the database depending on the password hash length
	 */
	'hash' => array(

		/**
		 * Strategy to use
		 * look into classes/sentry/hash/strategy for available strategies ( or make/import your own )
		 * Must be in strategies below
		 */
		'strategy' => 'Sentry',

		/**
		 * Convert hashes from another available strategy
		 */
		'convert'  => array(
			'enabled' => false,
			'from'    => '',
		),

		/**
		 * Available Strategies for your app
		 * This is used to set settings for conversion, like switching from SimpleAuth hashing to Sha256 or vice versa
		 */
		'strategies' => array(
			/**
			 * config options needed for hashing
			 * example:
			 * 'Strategy' => array(); // additional options needed for password hashing in your driver like a configurable salt
			 */

			'Sentry' => array(),

			'SimpleAuth' => array(
				'salt' => '',
			),

			'BCrypt' => array(
				'strength' => 4,
				// if you want to use a bcrypt hash with an algorithm
				'hashing_algorithm' => null,
			),
		),
	),

	'permissions' => array(

		/**
		 * enable permissions - true or false
		 */
		'enabled' => true,

		/**
		 * super user - string
		 * this will be used for the group and rules
		 * if you change this, you need to make sure you change the
		 */
		'superuser' => 'superuser',



		/**
		 * The permission rules file name
		 * Set name to '', null or 'config' to use config files ( will negate type option )
		 * Set type to files type. Supported types: php, json, ini, yaml,
		 * Path is relative to the modules base directory
		 *
		 * Type and Path are ignored if name is '', null or 'config'
		 */
		'file' => array(
			'name' => 'config',
			'type' => '',
			'path' => '',
		),

		/**
		 * setup rules for permissions
		 * These are resources that will require access permissions.
		 * Rules are assigned to groups or specific users in the
		 * format module_controller_method or controller_method
		 *
		 * This is ignored if file above is not set to config
		 */
		'rules' => array(
			// users control
			'users_index',
			'users_edit',
			'users_delete',
			'users_view',
			'users_create',
			'users_password_change',
			'users_unblock',
			'change_acl',

			// groups control
			'groups_index',
			'groups_edit',
			'groups_delete',
			'groups_view',
			'groups_create',

			// customers view control
			'customers_ib_read',
			'customers_franchisescheme_read',
			'customers_whitelabel_read',
			'customers_seniorpartner_read',
			'customers_callback_read',
			'customers_inquiry_read',
			'customers_small_registration_read',
			'customers_forexblog_ib_registration_read',
			'customers_promotions_read',
			'customers_videoconference_read',
			'customers_demoaccount_read',
			'customers_fb_home_read',
			'customers_pay_order_info_read',
			'customers_cmginfo_read',

			// special access for customers
			'customers_all_read',
			'customers_en',
			'customers_ru',
			'customers_tw',
			'customers_cn',

			'customers_index',
			'customers_delete',
			'customers_update',
			'filters_lang_use',
			'filters_date_use',
			'filters_multi_use',

			// charts
			'charts_index',
			'charts_monthly',

			// Others
			'ajax_dashboard',
			'message_send',
			'notifications_send',
			'notifications_receive',

			'speed_loading_read',

			// forms

			'forms_index'
		)
	)
);
