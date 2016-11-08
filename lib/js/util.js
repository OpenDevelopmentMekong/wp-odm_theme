jQuery(function(){

	if (jQuery(window).width() <= 750){
		jQuery('#country-select-dropdown').on('click', function(){
			jQuery('.country-selector').toggleClass('showall');
		});

		jQuery('.post-content').has('iframe').css('width', jQuery(window).width() / 90 * 100);
	}

	jQuery("#close-disclaimer-btn").on('click', function() {
		jQuery("#notification-message").fadeOut();
		setCookie("notification-dismissed",true,2);
	});

	if (getCookie("notification-dismissed") === true){
		jQuery("#notification-message").hide();
	}

	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+ d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

});
