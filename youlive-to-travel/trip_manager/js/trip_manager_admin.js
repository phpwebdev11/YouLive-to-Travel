function showApi()
{
	if(jQuery('#marketplace_settings_location_autocomplete').is(':checked'))
	{
		jQuery('#showapi').show();
	}
	else
	{
		jQuery('#marketplace_settings_location_api').val('');
		jQuery('#showapi').hide();
	}
}
jQuery(document).ready(function(){
	jQuery('#marketplace_settings_location_autocomplete').click(function(){
		showApi();
	});

	showApi();

});