jQuery(function(){
	if (jQuery(window).width() <= 750){	
		jQuery('#country-select-dropdown').on('click', function(){
			jQuery('.country-selector').toggleClass('showall');
		});
	}	
});