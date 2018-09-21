<?php

	if(!class_exists('youli_trip_manager_shortcode'))
	{
		/**
		* Class to manage the shortcode
		*/
		class youli_trip_manager_shortcode
		{
			
			function __construct()
			{
				/* Add shortcode to display trips */
				add_shortcode('youli-marketplace', array($this, 'youli_marketplace_hook'));
				
				/* Add ajax hooks */
				add_action('wp_ajax_load_trip_manager_content', array($this, 'youli_load_trip_manager_content'));
				add_action('wp_ajax_nopriv_load_trip_manager_content', array($this, 'youli_load_trip_manager_content'));
			}

			/* Hook to mange the shortcode */
			function youli_marketplace_hook()
			{
				ob_start();
				$content = $this->youli_fetch_trips();

				$arrMarketplaceSettings = get_option('marketplace_settings', array());
?>
				<div class='trips-container'>
<?php
					if((isset($arrMarketplaceSettings['hide_search']) && $arrMarketplaceSettings['hide_search'] == '0'))
					{
?>
						<div class='banner-section'>
							<h3><?php _e('Travel with purpose,', 'youlive-to-travel'); ?></h3>
							<h2><?php _e('Allow yourself to be changed.', 'youlive-to-travel'); ?></h2>
							<div class="search-box">
								<div class='trip-search-text'>
									<input type="text" name="trip-searchbox" placeholder="<?php _e('Where do you want to go?', 'youlive-to-travel'); ?>" id="trip-searchbox">
								</div>
								<div class='trip-search-buttons'>
									<button class='trip-search-button' id='trip-date-button' data-container='trip-date-dropdown'><?php _e('Dates', 'youlive-to-travel'); ?></button>
									<button class='trip-search-button' id='trip-price-button' data-container='trip-price-dropdown'><?php _e('Price', 'youlive-to-travel'); ?></button>
									<button class='trip-search-button' id='trip-type-button' data-container='trip-type-dropdown'><?php _e('Kind', 'youlive-to-travel'); ?></button>
									<div class='trip-search-container' id='trip-date-dropdown'>
										<p><?php _e('Select Dates', 'youlive-to-travel'); ?></p>
										<input type='text' name='trip_from_date' id='trip_from_date'>
										<input type='text' name='trip_to_date' id='trip_to_date'>
										<div id='trip-date-picker'></div>
										<div id='trip-date-links'>
											<div><a href="#" id='cancel_trip_dates'><?php _e('Clear', 'youlive-to-travel'); ?></a></div>
											<div><a href="#" id='apply_trip_dates'><?php _e('Apply', 'youlive-to-travel'); ?></a></div>
										</div>
									</div>
									<div class='trip-search-container' id='trip-price-dropdown'>
										<p><?php _e('Price Range', 'youlive-to-travel'); ?></p>
										<input type='text' name='trip_from_price' id='trip_from_price' value='0'>
										<input type='text' name='trip_to_price' id='trip_to_price' value='5000'>
										<div class='trip-price-text'>
											<span class='price'>0 - 5,000</span>
											<span><?php _e('Per person in ', 'youlive-to-travel'); ?>
												<select id='currency' name='currency'>
													<option value='AUD'>AUD</option>
													<option value='NZD'>NZD</option>
													<option value='EUR'>EUR</option>
													<option value='USD'>USD</option>
													<option value='JPY'>JPY</option>
													<option value='CAD'>CAD</option>
													<option value='GBP'>GBP</option>
												</select>
											</span>
										</div>
										<div id="trip-price-picker"></div>
										<div id='trip-price-links'>
											<div><a href="#" id='cancel_trip_price'><?php _e('Clear', 'youlive-to-travel'); ?></a></div>
											<div><a href="#" id='apply_trip_price'><?php _e('Apply', 'youlive-to-travel'); ?></a></div>
										</div>
									</div>
									<div class='trip-search-container' id='trip-type-dropdown'>
										<p><?php _e('Select Kind', 'youlive-to-travel'); ?></p>
										<input type='text' name='trip_type' id='trip_type'>
										<div class='trip-type-container'>
											<div>
												<div class='trip-type' data-type='Adventure'>
													<div class='trip-type-img adventure'></div>
													<span><?php _e('Adventure', 'youlive-to-travel'); ?></span>
												</div>
												<div class='trip-type' data-type='Retreat'>
													<div class='trip-type-img retreat'></div>
													<span><?php _e('Retreat', 'youlive-to-travel'); ?></span>
												</div>
												<div class='trip-type' data-type='Wedding'>
													<div class='trip-type-img weddings'></div>
													<span><?php _e('Wedding', 'youlive-to-travel'); ?></span>
												</div>
											</div>
											<div>
												<div class='trip-type' data-type='Tour'>
													<div class='trip-type-img tour'></div>
													<span><?php _e('Tour', 'youlive-to-travel'); ?></span>
												</div>
												<div class='trip-type' data-type='Conference'>
													<div class='trip-type-img conference'></div>
													<span><?php _e('Conference', 'youlive-to-travel'); ?></span>
												</div>
											</div>
										</div>
										<div id='trip-type-links'>
											<div><a href="#" id='cancel_trip_type'><?php _e('Clear', 'youlive-to-travel'); ?></a></div>
											<div><a href="#" id='apply_trip_type'><?php _e('Apply', 'youlive-to-travel'); ?></a></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class='trip-card-filter' style='<?php echo ((trim($content) == '') ? 'display:none' : ''); ?>'>
						<button data-filter='*' class='selected'><?php _e('Show All', 'youlive-to-travel') ?></button>
						<button data-filter='.private'><?php _e('Get Quote', 'youlive-to-travel') ?></button>
						<button data-filter='.instant'><?php _e('Instant Book', 'youlive-to-travel') ?></button>
						<button data-filter='.diy'><?php _e('Diy', 'youlive-to-travel') ?></button>
						</div>
<?php
					}

					
?>
					
					<div class='trip-card-row' id='trip_manager_trips_container' data-location='<?php echo (isset($_REQUEST['location']) ? $_REQUEST['location'] : ''); ?>' data-startdate='<?php echo (isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''); ?>' data-enddate='<?php echo (isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''); ?>' data-pricestart='<?php echo (isset($_REQUEST['price_start']) ? $_REQUEST['price_start'] : ''); ?>' data-priceend='<?php echo (isset($_REQUEST['price_end']) ? $_REQUEST['price_end'] : ''); ?>' data-kind='<?php echo (isset($_REQUEST['kind']) ? $_REQUEST['kind'] : ''); ?>' data-currency='<?php echo (isset($_REQUEST['currency']) ? $_REQUEST['currency'] : ''); ?>'>
						<?php echo $content; ?>
					</div>
<?php

					if(trim($content) == '')
					{
						$token = get_option('youli_marketplace_token', '');
						if($token == '')
						{
?>
							<p class='no-data'><?php _e('Trips cannot be displayed, please check your configuration.', 'youlive-to-travel'); ?></p>
<?php
						}
						else
						{
?>
							<p class='no-data'><?php _e('We couldn’t find any trips that matched, try again with different options and double check your location is spelled correctly. Note that when price range is selected, only trips sold in the selected currency are shown.', 'youlive-to-travel'); ?></p>
<?php
						}
					}
?>
				</div>
<?php
				$tripContent = ob_get_contents();
				ob_end_clean();

				return $tripContent;
			}

			/* Function to fetch the trips */
			function youli_fetch_trips()
			{
				$page = ((isset($_REQUEST['page']) && intval($_REQUEST['page']) > 0) ? $_REQUEST['page'] : 1);
				
				$searchQuery = '';
				
				if(isset($_REQUEST['location']) && trim($_REQUEST['location']) != '')
				{
					$searchQuery .= '&location='.trim($_REQUEST['location']);
				}

				if(isset($_REQUEST['start_date']) && trim($_REQUEST['start_date']) != '')
				{
					$searchQuery .= '&start_date='.trim($_REQUEST['start_date']);
				}

				if(isset($_REQUEST['end_date']) && trim($_REQUEST['end_date']) != '')
				{
					$searchQuery .= '&end_date='.trim($_REQUEST['end_date']);
				}

				if(isset($_REQUEST['price_start']) && trim($_REQUEST['price_start']) != '')
				{
					$searchQuery .= '&price_start=' . trim($_REQUEST['price_start']);
				}

				if(isset($_REQUEST['price_end']) && trim($_REQUEST['price_end']) != '')
				{
					$searchQuery .= '&price_end=' . trim($_REQUEST['price_end']);
				}

				if(isset($_REQUEST['kind']) && trim($_REQUEST['kind']) != '')
				{
					$searchQuery .= '&kind=' . trim($_REQUEST['kind']);
				}

				if(isset($_REQUEST['currency']) && trim($_REQUEST['currency']) != '')
				{
					$searchQuery .= '&currency=' . trim($_REQUEST['currency']);
				}

				/*$pageSize = ((isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) ? intval($_REQUEST['page_size']) : 10);
				$searchQuery .= '&page_size=' . $pageSize;*/

				$arrMarketplaceSettings = get_option('marketplace_settings', array());
				$pageSize = ((isset($arrMarketplaceSettings['trips_per_page']) && count($arrMarketplaceSettings['trips_per_page']) > 0) ? $arrMarketplaceSettings['trips_per_page'] : 50);

				$arrTrips = youli_trip_manager_api::youli_get_trips($page, $searchQuery);
				$totalTrips = (isset($arrTrips['Total']) ? $arrTrips['Total'] : 0);  
				$arrTrips = ((isset($arrTrips['Results']) && is_array($arrTrips['Results'])) ? $arrTrips['Results'] : array());  

				$currentlyLoadedTrips = ((($page - 1) * $pageSize) + count($arrTrips));

				ob_start();
				if(is_array($arrTrips) && count($arrTrips) > 0)
				{
					foreach ($arrTrips as $trip)
					{
						$tripLink = (isset($trip['TripLink']) ? $trip['TripLink'] : '');
						$tripFlag = (isset($trip['TripFlag']) ? $trip['TripFlag'] : '');
						$tripDuration = (isset($trip['TripDuration']) ? $trip['TripDuration'] : '');
						$tripType = array('1' => 'diy', '2' => 'instant', '3' => 'private');

?>						
						<div class='trip-card-col <?php echo (isset($tripType[$tripFlag]) ? $tripType[$tripFlag] : ''); ?>'>
							<div class="trip-card-wrapper">

							    <div class="top-row">
							        <div class="top-row-left">
							            <img src="<?php echo YOULI_TRIP_MANAGER_PLUGIN_URL; ?>images/map-marker-icon.svg" class="location-icon" width="14" height="18" /> <?php echo (isset($trip['TripPrimaryLocation']) ? $trip['TripPrimaryLocation'] : ''); ?>
							        </div>
							        <div class="top-row-right">
							        	<?php
							        		if(isset($trip['PlannerAvatarURL']) && trim($trip['PlannerAvatarURL']) != '')
							        		{
							        	?>
							        			<img class="avatar" src="<?php echo $trip['PlannerAvatarURL'] ?>" />
							        	<?php		
							        		}
							        		if(isset($trip['PlannerFullName']) && trim($trip['PlannerFullName']) != '')
							        		{
							        			echo ' ' . __('with', 'youlive-to-travel') . ' ';
							        			if(isset($trip['PlannerProfileLinkURL']) && trim($trip['PlannerProfileLinkURL']) != '')
							        			{
							        	?>
							        				<a class='planner-name' href="<?php echo $trip['PlannerProfileLinkURL']; ?>"><?php echo $trip['PlannerFullName']; ?></a>
							        	<?php
							        			}
							        			else
							        			{
							        	?>
							        				<span class="planner-name"><?php echo $trip['PlannerFullName']; ?></span>
							        	<?php
							        			}
							        		}
							        	?>
							        </div>
							    </div>

							    <div class="trip-card-banner-image">
							        <a href="<?php echo $tripLink; ?>">
							        	<?php
							        		if(isset($trip['TripBannerURL']) && trim($trip['TripBannerURL']))
							        		{
							        	?>
							            		<img src="<?php echo $trip['TripBannerURL']; ?>" />
							        	<?php
							        		}

						        			switch ($tripFlag)
						        			{
						        				case '1':
							        	?>
						        					<div class="sash diy"><?php _e('DIY', 'youlive-to-travel'); ?></div>
							        	<?php	
						        					break;
						        				case '2':
							        	?>
						        					<div class="sash instant-book"><?php _e('Instant Book', 'youlive-to-travel'); ?></div>
							        	<?php
						        					break;
						        				case '3':
							        	?>
						        					<div class="sash private"><?php _e('Get Quote', 'youlive-to-travel'); ?></div>
							        	<?php
						        					break;

							        		}
							        	?>
							        </a>
							    </div>

							    <div class="trip-card-details">
							        <h3><a href="<?php echo $tripLink; ?>"><?php echo (isset($trip['TripName']) ? $trip['TripName'] : ''); ?></a></h3>

							        <div class="trip-card-details-row">
							            <img src="<?php echo YOULI_TRIP_MANAGER_PLUGIN_URL; ?>images/calendar.svg" width="15" /> 
							            <?php

							            	if(isset($trip['TripDates']) && trim($trip['TripDates']) != '')
							            	{
							            		echo $trip['TripDates'];
							            	}
							            	else /*if(trim($tripDuration) == 'TBC')*/
							            	{
							            		if($tripFlag == '1')
							            		{
							            ?>
							            			<strong><?php _e('Do it yourself', 'youlive-to-travel'); ?></strong>
							            <?php
							            		}
							            		else /*if($tripFlag == '1')*/
							            		{
							            ?>
							            			<strong><?php _e('Pick your own dates', 'youlive-to-travel'); ?></strong>
							            <?php
							            		}
							            	}
							            	/*else if(trim($tripDuration) != '')
							            	{
							            ?>
							            		<strong><?php echo $tripDuration; ?></strong>
							            <?php
							            	}*/
							            ?>
							        </div>

							        <div class="trip-card-details-row">
							            <img src="<?php echo YOULI_TRIP_MANAGER_PLUGIN_URL; ?>images/dollar-circle-orange.svg" width="15" /> <?php echo (isset($trip['TripCost']) ? $trip['TripCost'] : ''); ?>
							        </div>
							        <?php
							        	/*if(isset($trip['TripParticipantsCount']) && $trip['TripParticipantsCount'] > 4)*/
							        	if($tripFlag == 3)
							        	{
							        ?>
							        		<div class="trip-card-details-row">
								                <img src="<?php echo YOULI_TRIP_MANAGER_PLUGIN_URL; ?>images/user-orange.svg" width="15" /> <strong><?php /*echo $trip['TripParticipantsCount'] . 'going';*/ ?><?php _e('Private Tour', 'youlive-to-travel') ?></strong>
								            </div>
							        <?php
							        	}
							        ?>
							    </div>
							</div>
						</div>
<?php
					}

					if($currentlyLoadedTrips < $totalTrips)
					{
?>
						<a href="#" id='trip_manager_pagination' data-page='<?php echo ($page + 1); ?>'><?php _e('View More', 'youlive-to-travel'); ?></a>
<?php
					}
				}

				$content = ob_get_contents();
				ob_end_clean();

				return $content;
			}

			/* Hook to manage ajax request */
			function youli_load_trip_manager_content()
			{
				echo $this->youli_fetch_trips();
				die;
			}
		}

		new youli_trip_manager_shortcode();
	}