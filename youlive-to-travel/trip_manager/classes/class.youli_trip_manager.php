<?php

	if(!class_exists('youli_trip_manager'))
	{
		/**
		* Base class of the plugin
		*/
		class youli_trip_manager
		{
			
			function __construct()
			{
				/* Include plugin files */
				require_once(YOULI_TRIP_MANAGER_PLUGIN_DIR . 'classes/class.youli_trip_manager_api.php');
				require_once(YOULI_TRIP_MANAGER_PLUGIN_DIR . 'classes/class.youli_trip_manager_scripts.php');
				require_once(YOULI_TRIP_MANAGER_PLUGIN_DIR . 'classes/class.youli_trip_manager_shortcode.php');
				require_once(YOULI_TRIP_MANAGER_PLUGIN_DIR . 'classes/class.youli_trip_manager_admin_menu.php');
			}
		}
	}