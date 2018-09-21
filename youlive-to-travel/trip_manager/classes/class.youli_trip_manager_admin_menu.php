<?php

	if(!class_exists('youli_trip_manager_admin_menu'))
	{
		/**
		* Class to manage the admin menu
		*/
		class youli_trip_manager_admin_menu
		{
			/* Constructor of the class */
			function __construct()
			{
				/* Add a custom menu */
				add_action('admin_menu', array($this, 'youli_admin_menu'));

				/* Add hook to admin init */
				add_action('admin_init', array($this, 'youli_admin_init'));

				/* Add custom action links for the plugin */
				add_filter( 'plugin_action_links_' . YOULI_TRIP_MANAGER_PLUGIN_BASENAME, array($this, 'youli_plugin_action_links'));
			}

			/* Add custom menu in admin panel */
			function youli_admin_menu()
			{
				add_menu_page(__('YouLi', 'youlive-to-travel'), __('YouLi', 'youlive-to-travel'), 'manage_options', 'marketplace_settings', array($this, 'youli_marketplace_settings'), YOULI_TRIP_MANAGER_PLUGIN_URL.'images/youli-admin-icon.png');
			}

			/* Function to manage the custom link */
			function youli_marketplace_settings()
			{
?>
				<div class='wrap'>
					<h1><?php _e('YouLi Settings', 'youlive-to-travel'); ?></h1>
					<?php
						if(isset($_REQUEST['info']))
						{
							$msg = '';
							$class = 'updated';
							switch ($_REQUEST['info']) {
								case 's':
									$msg = __('Information saved.', 'youlive-to-travel');
									break;
								case 'ue':
								case 'te':
									$msg = __('Contact support@youli.io to get help with your YouLi authentication.', 'youlive-to-travel');
									$class = 'error';
									break;
							}

							if($msg != '')
							{
					?>
								<div id="message" class="<?php echo $class; ?> notice notice-success is-dismissible">
									<p><?php echo $msg; ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
								</div>
					<?php
							}
						}
					?>
					<form method='post' action='' name='frmMarketplaceAuth'>
						<?php
							wp_nonce_field('auth_marketplace_settings', 'auth_marketplace_settings_nonce');
						?>

						<?php

							$token = get_option('youli_marketplace_token', '');
							if(trim($token) == '')
							{
						?>
								<div id="message" class="error notice notice-success is-dismissible">
									<p><?php _e('No valid token, please enter your login details to enable this plugin. <a href="mailto:support@youli.io">Contact YouLi support</a> with any questions'); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
								</div>
						<?php
							}
							else
							{
						?>
								<div id="message" class="updated notice notice-success is-dismissible">
									<p><?php _e('Valid token stored, your trips should appear when you use the shortcode [youli-marketplace]. <a href="mailto:support@youli.io">Contact YouLi support</a> with any questions.'); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
								</div>
						<?php	
							}
						?>

						<div class='marketplace-settings-container'>
							<p>Use the same credentials as you do to login to: <a href="https://youli.io?skipTripFind=true&utm_campaign=wordpressplugin">https://youli.io</a>. Only trips and quotes that have a privacy setting of 'Listed' will be displayed. <a href="https://youli.io?skipTripFind=true&utm_campaign=wordpressplugin">Signup for a FREE premium trial.</a></p>
							<div class='settings-block'>
								<label for='marketplace_api_username'><?php _e('API Username: ', 'youlive-to-travel'); ?></label>
								<input type='text' name='marketplace_api_username' id='marketplace_api_username' value='' size='20' maxlength='50'>
							</div>
							<div class='settings-block'>
								<label for='marketplace_api_password'><?php _e('API Password: ', 'youlive-to-travel'); ?></label>
								<input type='Password' name='marketplace_api_password' id='marketplace_api_password' value=''>
							</div>
							<div class='settings-block'>
								<input type='submit' name='validate_token' id='validate_token' value='<?php _e('Validate', 'youlive-to-travel'); ?>' class='button-primary'>
							</div>
						</div>
					</form>
					<form method='post' action='' name='frmMarketplaceSettings'>
						<?php
							wp_nonce_field('save_marketplace_settings', 'save_marketplace_settings_nonce');

							$arrMarketplaceSettings = get_option('marketplace_settings', array());
						?>
						<div class='marketplace-settings-container'>
							<div class='settings-block'>
								<label><?php _e('Trips per page: ', 'youlive-to-travel'); ?></label>
								<input type='number' name='marketplace_settings[trips_per_page]' id='marketplace_settings_trips_per_page' value='<?php echo ((isset($arrMarketplaceSettings['trips_per_page']) && intval($arrMarketplaceSettings['trips_per_page']) > 0) ? $arrMarketplaceSettings['trips_per_page'] : 50); ?>' step='1'>
								<p class='description'><?php _e('Set this number to the maximum number of trips you expect to have listed. If you have too many for one page and want to enable paging, <a href="mailto:support@youli.io">contact YouLi support</a> for guidance.', 'youlive-to-travel'); ?></p>
							</div>
							<div class='settings-block'>
								<label><?php _e('Hide Search: ', 'youlive-to-travel'); ?></label>
								<input type='checkbox' name='marketplace_settings[hide_search]' id='marketplace_settings_hide_search' value='1' <?php echo (isset($arrMarketplaceSettings['hide_search']) ? ($arrMarketplaceSettings['hide_search'] == '1' ? 'checked="checked"' : '') : 'checked="checked"'); ?>>
								<p class='description'><?php _e('By default, search is excluded above your trips. To enable search, uncheck "Hide Search". You can also pass "?location=bali" to hard code a pre-determined search on any page that uses the shortcode', 'youlive-to-travel'); ?></p>
							</div>
							<div class='settings-block'>
								<label><?php _e('Enable Location Autocomplete: ', 'youlive-to-travel'); ?></label>
								<input type='checkbox' name='marketplace_settings[location_autocomplete]' id='marketplace_settings_location_autocomplete' value='1' <?php echo ((isset($arrMarketplaceSettings['location_autocomplete']) && $arrMarketplaceSettings['location_autocomplete'] == '1') ? 'checked="checked"' : ''); ?>>
								<p class='description'><?php _e('Choose "Enable Location Autocomplete" to add google map autocomplete to the location search field. This can help with matching locations when users are likely to type in incorrect search values because of misspellings.', 'youlive-to-travel'); ?></p>
							</div>
							<div class='settings-block' id='showapi'>
								<label><?php _e('Google Map Places API Key: ', 'youlive-to-travel'); ?></label>
								<input type='text' name='marketplace_settings[location_api]' id='marketplace_settings_location_api' value='<?php echo (isset($arrMarketplaceSettings['location_api']) ? $arrMarketplaceSettings['location_api'] : ''); ?>'>
								<p class='description'><?php _e('Paste in the Google Places API Key into the box above. It should be ~40 characters long and contain numbers, letters and underscores. To request one, sign into your Google account and then click on “Get Key” on this page:', 'youlive-to-travel'); ?> <a href="https://developers.google.com/places/javascript/">https://developers.google.com/places/javascript/</a></p>
							</div>
							<div class='settings-block'>
								<input type='submit' name='save_settings' id='save_settings' value='<?php _e('Save', 'youlive-to-travel'); ?>' class='button-primary'>
							</div>
						</div>
					</form>
				</div>
<?php
			}

			/* Save plugin settings in the options table */
			function youli_admin_init()
			{
				if(isset($_POST['save_marketplace_settings_nonce']) && wp_verify_nonce($_POST['save_marketplace_settings_nonce'], 'save_marketplace_settings'))
				{
					if(isset($_POST['marketplace_settings']) && !isset($_POST['marketplace_settings']['hide_search']))
					{
						$_POST['marketplace_settings']['hide_search'] = '0';
					}
					
					update_option('marketplace_settings', ((isset($_POST['marketplace_settings']) && is_array($_POST['marketplace_settings'])) ? $_POST['marketplace_settings'] : array()));
					wp_redirect(admin_url('admin.php?page=marketplace_settings&info=s'));
					exit;
				}
				else if(isset($_POST['auth_marketplace_settings_nonce']) && wp_verify_nonce($_POST['auth_marketplace_settings_nonce'], 'auth_marketplace_settings'))
				{
					if(isset($_POST['marketplace_api_username']) && trim($_POST['marketplace_api_username']) != '' && isset($_POST['marketplace_api_password']) && trim($_POST['marketplace_api_password']) != '')
					{
						$arrTokenData = youli_trip_manager_api::youli_get_token($_POST['marketplace_api_username'], $_POST['marketplace_api_password']);
						if(isset($arrTokenData['access_token']) && trim($arrTokenData['access_token']) != '')
						{
							update_option('youli_marketplace_token', $arrTokenData['access_token']);
							wp_redirect(admin_url('admin.php?page=marketplace_settings'));
							exit;
						}
						else
						{
							update_option('youli_marketplace_token', '');
							wp_redirect(admin_url('admin.php?page=marketplace_settings&info=te'));
							exit;
						}
					}
					else
					{
						wp_redirect(admin_url('admin.php?page=marketplace_settings&info=ue'));
						exit;
					}
				}
			}

			/* Add settings link to plugin action links */
			function youli_plugin_action_links($links)
			{
				$links = array_merge(array('settings' => '<a href="'.admin_url('admin.php?page=marketplace_settings').'">'.__('Settings', 'youlive-to-travel').'</a>'), $links);
				return $links;
			}
		}

		/* Initialize the class when file is loaded */
		new youli_trip_manager_admin_menu();
	}