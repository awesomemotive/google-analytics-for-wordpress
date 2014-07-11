console.log(ga_settings['download_types']); // Get the files which should be tracked as a download
console.log(ga_settings['track_download']); // Get the files which should be tracked as a download


var gh = this;
var ext = ga_settings['download_types'];
ext = ext.split(',');

jQuery("a").click(function(){
	if(ga_settings['track_download']=='event'){
		// Track download as event
		ga('send', 'event', 'Download file', 'click', jQuery(this).attr('href'), 4);
	}
});