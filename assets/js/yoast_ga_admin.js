function yst_popupwindow(url, w, h) {
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/8);
	return window.open(url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

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

	function yst_ga_switch_manual() {
        if ( jQuery('#yoast-ga-form-checkbox-settings-manual_ua_code').is(':checked') ) {
            jQuery('#enter_ua').show();
            jQuery("#yoast-ga-form-select-settings-analytics_profile").prop('disabled', true).trigger("chosen:updated");
            jQuery("#yst_ga_authenticate").attr('disabled', true);
			jQuery('#oauth_code').hide();
        } else {
            jQuery('#enter_ua').hide();
            jQuery('#yoast-ga-form-text-settings-manual_ua_code_field').attr('value', '');
            jQuery("#yoast-ga-form-select-settings-analytics_profile").prop('disabled', false).trigger("chosen:updated");
            jQuery("#yst_ga_authenticate").attr('disabled', false);
			jQuery('#oauth_code').show();
        }
    }

	// Manually enter a UA code
	jQuery('#yoast-ga-form-checkbox-settings-manual_ua_code').click( function() { yst_ga_switch_manual(); } );
    yst_ga_switch_manual();

	jQuery('#oauth_code').hide();
	jQuery("#yst_ga_authenticate").click( function() {
		jQuery('#oauth_code').show();
		Focusable.setFocus(jQuery('#oauth_code'), {hideOnESC:true});
		jQuery('#oauth_code input').focus();
	});

	jQuery('.nav-tab-active').click();

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
