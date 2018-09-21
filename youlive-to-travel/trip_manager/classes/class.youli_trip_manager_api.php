<?php
	if(!class_exists('youli_trip_manager_api'))
	{
		/**
		* Class to manage api
		*/
		class youli_trip_manager_api
		{
			/* Function to get the trips */
			public static function youli_get_trips($page, $searchQuery = ''){

				$arrMarketplaceSettings = get_option('marketplace_settings', array());
				$pageSize = ((isset($arrMarketplaceSettings['trips_per_page']) && count($arrMarketplaceSettings['trips_per_page']) > 0) ? $arrMarketplaceSettings['trips_per_page'] : 50);
				$token = get_option('youli_marketplace_token', '');

				// !!!!! IF YOU CHANGE THIS URL, be sure to CHANGE the one below AND the one in class.youli_trip_manager_admin_menu.php
				$url = 'https://youli.io/api/trip/?page_size='.$pageSize.'&page='.$page . $searchQuery;
				$header = array('Authorization' => 'bearer '.$token);
				$arrTrips = self::youli_make_request($url, false, $header, $header);
				return $arrTrips;
			}

			/* Function to get the token */
			public static function youli_get_token($userId, $password){
				// !!!!! IF YOU CHANGE THIS URL, be sure to CHANGE the one above AND the one in class.youli_trip_manager_admin_menu.php
				$url = 'https://youli.io/token';
				$postData = array('grant_type' => 'password', 'username' => $userId, 'password' => $password);
				$arrTokenData = self::youli_make_request($url, true, $postData);
				return $arrTokenData;
			}

			/* Function to manage curl request to fetch data */
			public static function youli_make_request($url, $isPost = false, $data = array(), $header = '')
			{
				if($isPost)
 				{
 					$contents = wp_remote_retrieve_body(wp_remote_post($url, array('body' => $data, 'method' => 'POST')));
 				}
 				else
 				{
 					$contents = wp_remote_retrieve_body(wp_remote_get($url, array('headers' => $data)));
 				}
				return json_decode($contents, true);
			}
		}
	}