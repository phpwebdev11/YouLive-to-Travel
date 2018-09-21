
function load_trip_results(_pageno)
{
	_tripContainer = jQuery('#trip_manager_trips_container');

	if(jQuery('#trip_manager_loading').length == 0)
	{
		_tripContainer.after(jQuery('<div>').attr('id','trip_manager_loading').append(jQuery('<img>').attr('src',trip_manager.loading_image)));
	}

	jQuery('.no-data').remove();

	_searchQuery = '';
	_location = jQuery.trim(_tripContainer.data('location'));
	_startdate = jQuery.trim(_tripContainer.data('startdate'));
	_enddate = jQuery.trim(_tripContainer.data('enddate'));
	_pricestart = jQuery.trim(_tripContainer.data('pricestart'));
	_priceend = jQuery.trim(_tripContainer.data('priceend'));
	_kind = jQuery.trim(_tripContainer.data('kind'));
	_currency = jQuery.trim(_tripContainer.data('currency'));

	if(_location != '')
	{
		_searchQuery += '&location=' + escape(_location);
	}

	if(_startdate != '')
	{
		_searchQuery += '&start_date=' + escape(_startdate);
	}

	if(_enddate != '')
	{
		_searchQuery += '&end_date=' + escape(_enddate);
	}

	if(_pricestart != '')
	{
		_searchQuery += '&price_start=' + escape(_pricestart);
	}

	if(_priceend != '')
	{
		_searchQuery += '&price_end=' + escape(_priceend);
	}

	if(_kind != '')
	{
		_searchQuery += '&kind=' + escape(_kind);
	}

	if(_currency != '')
	{
		_searchQuery += '&currency=' + escape(_currency);
	}

	jQuery.ajax({
		url: trip_manager.ajax_url,
		data:'action=load_trip_manager_content&page=' + escape(_pageno) + _searchQuery,
		success: function(data){
			if(data != '')
			{
				data = jQuery(data).addClass('new');
				jQuery('.trip-card-filter').show();

				_tripContainer.append(data);

				jQuery('.trip-card-row').isotope('insert', data);

				setTimeout(function(){
					jQuery('.trip-card-row').isotope('layout');
				}, 5000);

			}
			else
			{
				jQuery('#trip_manager_pagination').remove();
				jQuery('.trip-card-row').isotope('remove', jQuery('.trip-card-col'));
				jQuery('.trip-card-row').isotope('layout');
				jQuery('.trip-card-filter').hide();
				_tripContainer.after(jQuery('<p>').addClass('no-data').html(trip_manager.no_data));
			}

			
			jQuery('#trip_manager_loading').remove();				
		},
		error: function(){
			jQuery('#trip_manager_loading').remove();
		}
	}).then(function(){
		setTimeout(function(){ jQuery('.trip-card-col').removeClass('new'); }, 5000);
	});
}

function do_search(){
	if(jQuery('#trip_manager_trips_container').length > 0)
	{
		if(jQuery("#trip_manager_trips_container").data('isotope') == undefined)
		{
			jQuery('.trip-card-row').isotope({
				itemSelector: '.trip-card-col',
				masonry: {}
			});
	    }
		jQuery('#trip_manager_pagination').remove();
		jQuery('.trip-card-row').isotope('remove', jQuery('.trip-card-col'));
		jQuery('.trip-card-row').isotope('layout');
    	load_trip_results(1);
    }
}

jQuery(document).ready(function(){

	if(jQuery('.trip-card-row').length > 0 && jQuery('.trip-card-col').length > 0)
	{
		jQuery('.trip-card-row').isotope({
			itemSelector: '.trip-card-col',
			masonry: {}
		});
	}

	jQuery('.trip-card-filter button').click(function(){
		jQuery('.trip-card-filter button').removeClass('selected');

		_this = jQuery(this);
		if(jQuery('.trip-card-row').length > 0 && jQuery('.trip-card-col').length > 0)
		{
			jQuery('.trip-card-row').isotope({filter: _this.data('filter')});
		}

		_this.addClass('selected');
	});

	jQuery(document).on('click', '#trip_manager_pagination', function(e){
		e.preventDefault();

		_this = jQuery(this);
		_pageno = _this.data('page');

		_this.remove();

		load_trip_results(_pageno);
	});

	jQuery('#trip-date-picker').datepicker({
		dateFormat: "yy-mm-dd",
		numberOfMonths: 1,
		minDate: 0,
		beforeShowDay: function(date) {
				var date1 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_from_date").val());
				var date2 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_to_date").val());
				return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
			},
		onSelect: function(dateText, inst) {
			var date1 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_from_date").val());
			var date2 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_to_date").val());
            var selectedDate = jQuery.datepicker.parseDate('yy-mm-dd', dateText);
            if (!date1 || date2) {
				jQuery("#trip_from_date").val(dateText);
				jQuery("#trip_to_date").val("");
                jQuery(this).datepicker();
            } else if( selectedDate < date1 ) {
                jQuery("#trip_to_date").val( jQuery("#trip_from_date").val() );
                jQuery("#trip_from_date").val( dateText );
                jQuery(this).datepicker();
            } else {
				jQuery("#trip_to_date").val(dateText);
                jQuery(this).datepicker();
			}
		}
	});

	jQuery('.trip-search-button').click(function(){
		jQuery('.trip-search-container').hide();
		jQuery('#' + jQuery(this).data('container')).show();

		/*_id = jQuery(this).attr('id');

		switch(_id)
		{
			case 'trip-date-button':
				jQuery('#trip_from_date').val(jQuery('#trip_from_date').data('prev-value'));
				jQuery('#trip_to_date').val(jQuery('#trip_to_date').data('prev-value'));
				break;
			case 'trip-price-button':
				jQuery('#trip_from_price').val(jQuery('#trip_from_price').data('prev-value'));
				jQuery('#trip_to_price').val(jQuery('#trip_to_price').data('prev-value'));
				break;
			case 'trip-type-button':
				jQuery('#trip_type').val(jQuery('#trip_type').data('prev-value'));
				break;
		}*/
	});

	jQuery('#cancel_trip_dates').click(function(e){
		e.preventDefault();
		jQuery('#trip-date-button').html('Dates').removeClass('selected');

		/*jQuery('#trip_from_date').data('prev-value', jQuery('#trip_from_date').val());
		jQuery('#trip_to_date').data('prev-value', jQuery('#trip_to_date').val());*/

		jQuery("#trip_from_date, #trip_to_date").val('');

		jQuery('#trip_manager_trips_container').data('startdate', '').data('enddate', '');

		jQuery('#trip-date-picker').datepicker('destroy');
		jQuery('#trip-date-picker').datepicker({
			dateFormat: "yy-mm-dd",
			numberOfMonths: 1,
			minDate: 0,
			beforeShowDay: function(date) {
					var date1 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_from_date").val());
					var date2 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_to_date").val());
					return [true, date1 && ((date.getTime() == date1.getTime()) || (date2 && date >= date1 && date <= date2)) ? "dp-highlight" : ""];
				},
			onSelect: function(dateText, inst) {
				var date1 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_from_date").val());
				var date2 = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_to_date").val());
	            var selectedDate = jQuery.datepicker.parseDate('yy-mm-dd', dateText);
	            if (!date1 || date2) {
					jQuery("#trip_from_date").val(dateText);
					jQuery("#trip_to_date").val("");
	                jQuery(this).datepicker();
	            } else if( selectedDate < date1 ) {
	                jQuery("#trip_to_date").val( jQuery("#trip_from_date").val() );
	                jQuery("#trip_from_date").val( dateText );
	                jQuery(this).datepicker();
	            } else {
					jQuery("#trip_to_date").val(dateText);
	                jQuery(this).datepicker();
				}
			}
		});

		do_search();

		jQuery('#trip-date-dropdown').hide();
	});
	
	jQuery('#apply_trip_dates').click(function(e){
		e.preventDefault();

		_fromDate = jQuery.trim(jQuery('#trip_from_date').val());
		_toDate = jQuery.trim(jQuery('#trip_to_date').val());

		/*jQuery('#trip_from_date').data('prev-value', _fromDate);
		jQuery('#trip_to_date').data('prev-value', _toDate);*/

		var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

		if(_fromDate == '' && _toDate == '')
		{
			jQuery('#trip-date-button').html('Dates').removeClass('selected');
		}
		else if(_toDate != '')
		{
			_dateFrom = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_from_date").val());
			_dateTo = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_to_date").val());			

			_dateFromM = monthNames[_dateFrom.getMonth()];
			_dateToM = monthNames[_dateTo.getMonth()];

			if(_dateFromM == _dateToM)
			{
				jQuery('#trip-date-button').html(_dateFrom.getDate() + ' - ' + _dateTo.getDate() + ' ' + _dateToM).addClass('selected');
			}
			else
			{
				jQuery('#trip-date-button').html(_dateFrom.getDate() + ' ' + _dateFromM + ' - ' + _dateTo.getDate() + ' ' + _dateToM).addClass('selected');
			}
		}
		else
		{
			_dateFrom = jQuery.datepicker.parseDate('yy-mm-dd', jQuery("#trip_from_date").val());
			_dateFromM = monthNames[_dateFrom.getMonth()];

			jQuery('#trip-date-button').html(_dateFrom.getDate() + ' ' + _dateFromM).addClass('selected');
		}

		jQuery('#trip_manager_trips_container').data('startdate', jQuery("#trip_from_date").val()).data('enddate', jQuery("#trip_to_date").val());
		do_search();

		jQuery('#trip-date-dropdown').hide();
	});

	jQuery('#trip-price-picker').slider({
		range: true,
		min: 0,
		max: 30000,
		step: 10,
		values: [ 0, 5000 ],
		slide: function( event, ui ) {
			_currency = jQuery('#currency').val();
			if(ui.values[0] == ui.values[1])
	    	{
	    		_val = ui.values[0] + ' ' + _currency;
	    	}
	    	else
	    	{
	    		_val = ui.values[0] + ' - ' + ui.values[1] + ' ' + _currency;
	    	}
			jQuery('.trip-price-text').find('.price').html(_val);
			jQuery('#trip_from_price').val(ui.values[0]);
			jQuery('#trip_to_price').val(ui.values[1]);
		}
    });

    jQuery('#cancel_trip_price').click(function(e){
    	e.preventDefault();
    	jQuery('#trip-price-button').html('Price').removeClass('selected');
    	jQuery('#trip_from_price').val(0);
    	jQuery('#trip_to_price').val(5000)
    	jQuery('#trip_manager_trips_container').data('pricestart', '').data('priceend', '').data('currency', '');
    	jQuery('#trip-price-picker').slider( "values", 0, 0 );
    	jQuery('#trip-price-picker').slider( "values", 1, 5000 );
    	jQuery('.trip-price-text').find('.price').html('0 - 5000 AUD');
    	jQuery('#currency').val('AUD');
    	do_search();
    	jQuery('#trip-price-dropdown').hide();
    });

    jQuery('#apply_trip_price').click(function(e){
    	e.preventDefault();
    	_fromPrice = jQuery('#trip_from_price').val();
    	_toPrice = jQuery('#trip_to_price').val();
    	_currency = jQuery('#currency').val();

    	if(_fromPrice != '' && _toPrice != '')
    	{
	    	if(_fromPrice == _toPrice)
	    	{
	    		_val = _fromPrice + ' ' + _currency;
	    	}
	    	else
	    	{
	    		_val = _fromPrice + ' - ' + _toPrice + ' ' + _currency;
	    	}
	    	jQuery('#trip-price-button').html(_val).addClass('selected');
    	}
    	else
    	{
			jQuery('#trip-price-button').html('Price').removeClass('selected');
    	}

    	jQuery('#trip_manager_trips_container').data('pricestart', _fromPrice).data('priceend', _toPrice).data('currency', _currency);
    	do_search();
    	
    	jQuery('#trip-price-dropdown').hide();
    });

    jQuery('.trip-type').click(function(){
    	_this = jQuery(this);

    	if(_this.hasClass('selected'))
    	{
    		_this.removeClass('selected')
    		jQuery('#trip_type').val('');
    	}
    	else
    	{
    		jQuery('.trip-type').removeClass('selected');
    		_this.addClass('selected');
    		jQuery('#trip_type').val(_this.data('type'));
    		jQuery('#apply_trip_type').click();
    	}
    });

    jQuery('#cancel_trip_type').click(function(e){
    	e.preventDefault();
    	jQuery('#trip-type-button').html('Kind').removeClass('selected');
    	jQuery('#trip_type').val('');
    	jQuery('.trip-type').removeClass('selected');
    	jQuery('#trip_manager_trips_container').data('kind', '');
    	do_search();
    	jQuery('#trip-type-dropdown').hide();
    });

    jQuery('#apply_trip_type').click(function(e){
    	e.preventDefault();

    	_kind = jQuery('#trip_type').val();

    	/*jQuery('#trip_type').data('prev-value', _kind);*/

    	if(_kind != '')
    		jQuery('#trip-type-button').html(jQuery('#trip_type').val()).addClass('selected');
    	else
    		jQuery('#trip-type-button').html('Kind').removeClass('selected');

    	jQuery('#trip_manager_trips_container').data('kind', _kind);
    	do_search();

    	jQuery('#trip-type-dropdown').hide();
    });

    var typingTimer;
	var doneTypingInterval = 5000;

	jQuery('#trip-searchbox').on('keyup', function () {
		clearTimeout(typingTimer);
		typingTimer = setTimeout(typing_complete, doneTypingInterval);
		if(jQuery('#trip_manager_loading').length == 0)
		{
			jQuery('#trip_manager_pagination').remove();
			jQuery('.trip-card-row').isotope('remove', jQuery('.trip-card-col'));
			jQuery('.trip-card-row').isotope('layout');
			jQuery('#trip_manager_trips_container').after(jQuery('<div>').attr('id','trip_manager_loading').append(jQuery('<img>').attr('src',trip_manager.loading_image)));
		}
	});

	jQuery('#trip-searchbox').on('keydown', function () {
		clearTimeout(typingTimer);
	});

	function typing_complete()
	{
		console.log(jQuery('#trip_manager_trips_container'));
    	jQuery('#trip_manager_trips_container').data('location', jQuery('#trip-searchbox').val());
		do_search();
	}

    /*jQuery('#trip-searchbox').blur(function(){
    	_this = jQuery(this);
    	jQuery('#trip_manager_trips_container').data('location', _this.val());
		do_search();
    });*/

	jQuery('.trip-search-button, .trip-search-container').click(function(e){
		e.stopPropagation();
	});

	jQuery('body').click(function() {
		jQuery('.trip-search-container').hide();
	});

	
});

jQuery(window).bind('scroll', function() {
	if(!jQuery('#trip_manager_pagination').hasClass('disabled'))
	{
	    if(jQuery(window).scrollTop() >= jQuery('#trip_manager_trips_container').offset().top + jQuery('#trip_manager_trips_container').outerHeight() - window.innerHeight) {
	        jQuery('#trip_manager_pagination').addClass('.disabled').click();
	    }
    }
});

function initMap() {
	if(trip_manager.location_autocomplete)
	{
		input = document.getElementById('trip-searchbox');

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {

		if(jQuery('#trip_manager_loading').length == 0)
		{
			jQuery('#trip_manager_pagination').remove();
			jQuery('.trip-card-row').isotope('remove', jQuery('.trip-card-col'));
			jQuery('.trip-card-row').isotope('layout');
			jQuery('#trip_manager_trips_container').after(jQuery('<div>').attr('id','trip_manager_loading').append(jQuery('<img>').attr('src',trip_manager.loading_image)));
			jQuery('#trip_manager_trips_container').data('location', jQuery('#trip-searchbox').val());
			do_search();
		}


          /*var place = autocomplete.getPlace();
          if (!place.geometry) {
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }*/
        });
    }
}
