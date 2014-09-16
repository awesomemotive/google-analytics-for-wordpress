jQuery(document).ready(function() {

	jQuery('#ga-tabs').find('a').click(function() {
		jQuery('#ga-tabs').find('a').removeClass('nav-tab-active');
		jQuery('.gatab').removeClass('active');

		var id = jQuery(this).attr('id').replace('-tab','');
		jQuery('#' + id).addClass('active');
		jQuery(this).addClass('nav-tab-active');
	});

	jQuery('a.activate-link').click(function() {
		jQuery('#extensions.wpseotab').removeClass('active');
		jQuery('#extensions-tab').removeClass('nav-tab-active');
		jQuery('#licenses.wpseotab').addClass('active');
		jQuery('#licenses-tab').addClass('nav-tab-active');
	});

	// init
	var active_tab = window.location.hash.replace('#top#','');

	// default to first tab
	if ( active_tab == '' || active_tab == '#_=_') {
		active_tab = jQuery('.gatab').attr('id');
	}

	jQuery('#' + active_tab).addClass('active');
	jQuery('#' + active_tab + '-tab').addClass('nav-tab-active');

	// Manually enter a UA code
	jQuery('#yoast-ga-form-checkbox-settings-manual_ua_code').click(function(){
		if(jQuery(this).is(':checked')){
			jQuery('#enter_ua').show();
		}
		else{
			jQuery('#enter_ua').hide();
			jQuery('#yoast-ga-form-text-settings-manual_ua_code_field').attr('value','');
		}
	});

	if(jQuery("#yoast-ga-form-checkbox-settings-manual_ua_code").is(':checked')){
		jQuery('#enter_ua').show();
	}
});