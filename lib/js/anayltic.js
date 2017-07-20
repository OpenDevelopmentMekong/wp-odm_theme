jQuery(function(){

	//Google Analytic Event 
	//data-ga-event="EventCategory|EventAction|EventLabel"
	jQuery('[data-ga-event]').click(function(){
		var params = ['send', 'event'];
		params = params.concat(jQuery(this).data('ga-event').split("|"));
		ga.apply(null, params);
		
	});

});