<!-- This site uses the Yoast Google Analytics plugin v<?php echo GAWP_VERSION; ?> - https://yoast.com/wordpress/plugins/google-analytics/ -->
<?php
if( false == $hide_js ):
?>
<script type="text/javascript">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

<?php
	if( count( $gaq_push )>=1 ){
		foreach($gaq_push as $item){
			echo "	ga(".$item.");\n";
		}
	}
	?>

</script>
<?php
else:
	echo '<!-- ' . __( '@Webmaster, The Google Analytics code won\'t be shown, because you are logged in and your user role matches the "Ignore user" setting' ) ."-->\n";
endif;
?>
<!-- / Yoast Google Analytics -->
