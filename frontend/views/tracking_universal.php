<!-- This site uses the Yoast Google Analytics plugin v<?php echo GAWP_VERSION; ?> - Universal enabled - https://yoast.com/wordpress/plugins/google-analytics/ -->
<script type="text/javascript">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

<?php
	// List the GA elements from the class-ga-js.php
	if( count( $gaq_push )>=1 ){
		foreach($gaq_push as $item){
			echo "	ga(".$item.");\n";
		}
	}

	// Output the custom code that could be added in the WP backend
	if( !empty( $ga_settings['custom_code'] ) ){
		echo "	" . stripslashes( $ga_settings['custom_code'] ) . "\n";
	}
	?>

</script>
<!-- / Yoast Google Analytics -->
