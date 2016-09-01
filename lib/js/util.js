jQuery(function(){
	if (jQuery(window).width() <= 750){	
		jQuery('#country-select-dropdown').on('click', function(){
			jQuery('.country-selector').toggleClass('showall');
		});

		jQuery('.post-content').has('iframe').css('width', jQuery(window).width() / 90 * 100);
	}	
});