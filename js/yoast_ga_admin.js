jQuery(document).ready(function() {
	jQuery('#ga-tabs').find('a').click(function() {
		jQuery('#ga-tabs').find('a').removeClass('nav-tab-active');
		jQuery('.gatab').removeClass('active');

		var id = jQuery(this).attr('id').replace('-tab', '');
		jQuery('#' + id).addClass('active');
		jQuery(this).addClass('nav-tab-active');
		jQuery('#return_tab').val(id);
	});

	jQuery('a.activate-link').click(function() {
		jQuery('#extensions.wpseotab').removeClass('active');
		jQuery('#extensions-tab').removeClass('nav-tab-active');
		jQuery('#licenses.wpseotab').addClass('active');
		jQuery('#licenses-tab').addClass('nav-tab-active');
	});

	// init
	var activeTab = window.location.hash.replace('#top#', '');

	// default to first tab
	if (activeTab === '' || activeTab === '#_=_') {
		activeTab = jQuery('.gatab').attr('id');
	}

	jQuery('#' + activeTab).addClass('active');
	jQuery('#' + activeTab + '-tab').addClass('nav-tab-active');

	// Manually enter a UA code
	jQuery('#yoast-ga-form-checkbox-settings-manual_ua_code').click(function() {
		if (jQuery(this).is(':checked')) {
			jQuery('#enter_ua').show();
		} else {
			jQuery('#enter_ua').hide();
			jQuery('#yoast-ga-form-text-settings-manual_ua_code_field').attr('value', '');
		}
	});

	jQuery('.nav-tab-active').click();

	if (jQuery('#yoast-ga-form-checkbox-settings-manual_ua_code').is(':checked')) {
		jQuery('#enter_ua').show();
	}

	jQuery('.yoast_help').qtip({
		position: {
			corner: {
				target: 'topMiddle',
				tooltip: 'bottomLeft'
			}
		},
		show: {
			when: {
				event: 'mouseover'
			}
		},
		hide: {
			fixed: true,
			when: {
				event: 'mouseout'
			}
		},
		style: {
			tip: 'bottomLeft',
			name: 'blue'
		}
	});
});
