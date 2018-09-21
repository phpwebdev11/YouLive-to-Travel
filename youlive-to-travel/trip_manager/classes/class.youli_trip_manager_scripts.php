<?php
	if(!class_exists('trip_manager_scripts'))
	{
		/**
		* Function to manage scripts
		*/
		class trip_manager_scripts
		{
			
			/* Add hooks to include scripts */
			function __construct()
			{
				add_action('wp_enqueue_scripts', array($this, 'youli_wp_enqueue_scripts'));
				add_action('admin_enqueue_scripts', array($this, 'youli_admin_enqueue_scripts'));
			}

			/* Include frontend scripts */
			function youli_wp_enqueue_scripts()
			{
				wp_enqueue_style('trip-manager-css', YOULI_TRIP_MANAGER_PLUGIN_URL.'css/trip_manager.css');
				wp_enqueue_style('trip-manager-jqueryui-css', YOULI_TRIP_MANAGER_PLUGIN_URL.'css/jquery-ui.min.css');

				if(!wp_script_is('jquery'))
				{
					wp_enqueue_script('jquery');
				}

				wp_enqueue_script('trip-manager-isotope-js', YOULI_TRIP_MANAGER_PLUGIN_URL.'js/isotope.pkgd.min.js', array(), '1.1', true);
				
				if(!wp_script_is('jquery-ui-core'))
				{
					wp_enqueue_script('jquery-ui-core');
				}

				if(!wp_script_is('jquery-ui-datepicker'))
				{
					wp_enqueue_script('jquery-ui-datepicker');
				}

				if(!wp_script_is('jquery-ui-slider'))
				{
					wp_enqueue_script('jquery-ui-slider');
				}

				wp_enqueue_script('trip-manager-jqueryui-touch', YOULI_TRIP_MANAGER_PLUGIN_URL.'js/jquery.ui.touch-punch.min.js', array(), '1.1', true);
				
				$arrMarketplaceSettings = get_option('marketplace_settings', array());
				$locationAutocomplete = ((isset($arrMarketplaceSettings['location_autocomplete']) && $arrMarketplaceSettings['location_autocomplete'] == '1') ? true : false);
				$locationApi = (isset($arrMarketplaceSettings['location_api']) ? $arrMarketplaceSettings['location_api'] : '');
				$showMap = false;
				wp_enqueue_script('trip-manager-js', YOULI_TRIP_MANAGER_PLUGIN_URL.'js/trip_manager.js', array(), '1.1', true);

				if($locationAutocomplete && $locationApi != '')
				{
					wp_enqueue_script('trip-manager-gmap', '//maps.googleapis.com/maps/api/js?key='.$locationApi.'&libraries=places&callback=initMap', array(), '1.1', true);
					$showMap = true;
				}

				wp_localize_script( 'trip-manager-js', 'trip_manager', array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'loading_image' => YOULI_TRIP_MANAGER_PLUGIN_URL . 'images/youli-loading.gif',
					'no_data' => __('We couldn’t find any trips that matched, try again with different options and double check your location is spelled correctly. Note that when price range is selected, only trips sold in the selected currency are shown.', 'youlive-to-travel'),
					'location_autocomplete' => $showMap
				));
			}

			/* Add admin scripts */
			function youli_admin_enqueue_scripts()
			{
				wp_enqueue_style('trip-manager-admin-css', YOULI_TRIP_MANAGER_PLUGIN_URL.'css/trip_manager_admin.css');
				wp_enqueue_script('trip-manager-admin-js', YOULI_TRIP_MANAGER_PLUGIN_URL.'js/trip_manager_admin.js');
				
			}

			
		}

		new trip_manager_scripts();
	}