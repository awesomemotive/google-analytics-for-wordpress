import Vue from 'vue';

const MonsterInsightsHelper = {
	install( VueInstance ) {
		if ( window.monsterinsights ) {
			VueInstance.prototype.$mi = window.monsterinsights;
		}
		VueInstance.prototype.$isPro = isPro;
		VueInstance.prototype.$addQueryArg = addQueryArg;
		VueInstance.prototype.$getUrl = getUrl;
		VueInstance.prototype.$getUpgradeUrl = getUpgradeUrl;
	},
};

export function getUpgradeUrl( medium, campaign, url ) {
	const upgrade_url = getUrl( medium, campaign, url );

	if ( isPro() ) {
		return upgrade_url;
	}

	// eslint-disable-next-line
	if ( '0' !== window.monsterinsights.shareasale_id ) {
		// eslint-disable-next-line
		return addQueryArg( window.monsterinsights.shareasale_url, 'urllink', upgrade_url )
	}

	return upgrade_url;
}

export function getUrl( medium, campaign, url ) {
	const source = isPro() ? 'proplugin' : 'liteplugin',
		default_url = isPro() ? 'my-account/' : 'lite/',
		content = Vue.prototype.$mi.plugin_version;

	medium = medium ? medium : 'defaultmedium';
	campaign = campaign ? campaign : 'defaultcampaign';
	url = url ? url : 'https://www.monsterinsights.com/' + default_url;

	url = addQueryArg( url, 'utm_source', source );
	url = addQueryArg( url, 'utm_medium', medium );
	url = addQueryArg( url, 'utm_campaign', campaign );
	url = addQueryArg( url, 'utm_content', content );

	return url;
}

export function isPro() {
	return 'Pro' === process.env.VUE_APP_VERSION;
}

export function addQueryArg( uri, key, value ) {
	var re = new RegExp( '([?&])' + key + '=.*?(&|#|$)', 'i' );
	if ( uri.match( re ) ) {
		return uri.replace( re, '$1' + key + '=' + value + '$2' );
	} else {
		var hash = '';
		if ( uri.indexOf( '#' ) !== -1 ) {
			hash = uri.replace( /.*#/, '#' );
			uri = uri.replace( /#.*/, '' );
		}
		var separator = uri.indexOf( '?' ) !== -1 ? '&' : '?';
		return uri + separator + key + '=' + value + hash;
	}
}

export default MonsterInsightsHelper;
