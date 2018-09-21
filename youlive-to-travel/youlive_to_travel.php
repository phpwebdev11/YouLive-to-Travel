<?php

	/**
	 *
	 * Plugin is used to fetch trips from the api and display it
	 *
	 * Plugin Name:       YouLive to Travel
	 * Plugin URI:        https://youlivetotravel.com
	 * Description:       Activate this plugin to display your YouLi Travel Pages within your website. Used by Travel Organizers all over the world to organize their group trips, tours, retreats, workshops, conferences, FIT quotes, or destination events. Look professional, accept online bookings and automate engagement all in one place. <p>Insert [youli-marketplace] wherever you want your trips to display. ----> Go to <a href="https://youli.io?skipTripFind=true&utm_campaign=wordpressplugin">YouLi Dashboard</a> to signup or login and manage trips and quotes. Monetize your travel audience with DIY plans, group trips and quotes. <a href="https://youli.io?skipTripFind=true&utm_campaign=wordpressplugin">Free premium trial</a>.</p>
	 * Version:           1.13.7
	 * Author:            Chandni Patel
	 * Author URI:        http://phpwebdev.in/
	 * License:           GNU General Public License v3.0
	 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
	 * Text Domain:       youlive-to-travel
	 * Domain Path:       trip-manager/languages
	 */

	if(!defined('ABSPATH'))
	{
		die('Access Denied');
	}

	/* Include pluggable file */
	require_once(ABSPATH.'wp-includes/pluggable.php');

	/* Define constants */
	define('YOULI_TRIP_MANAGER_PLUGIN_DIR', plugin_dir_path(__FILE__) . 'trip_manager/');
	define('YOULI_TRIP_MANAGER_PLUGIN_URL', plugin_dir_url(__FILE__) . 'trip_manager/');
	define('YOULI_TRIP_MANAGER_PLUGIN_BASENAME', plugin_basename(__FILE__));

	/* Include the core files */
	require_once(YOULI_TRIP_MANAGER_PLUGIN_DIR . 'classes/class.youli_trip_manager.php');

	if(!function_exists('youli_trip_manager_init'))
	{
		function youli_trip_manager_init()
		{
			/* Initialize the base class of the plugin */
			$objTripManager = new youli_trip_manager();
		}
	}

	/* Add action to plugins_loaded */
	add_action('plugins_loaded', 'youli_trip_manager_init');