<!-- This site uses the Yoast Google Analytics plugin v<?php echo GAWP_VERSION; ?> - https://yoast.com/wordpress/plugins/google-analytics/ -->
<script type="text/javascript">

	var _gaq = _gaq || [];
<?php
	if(count($gaq_push)>=1){
		foreach($gaq_push as $key => $value){
			if(is_null($value)){
				echo "	_gaq.push(['" . $key . "']);\n";
			}
			else{
				echo "	_gaq.push(['" . $key . "', '" .$value . "']);\n";
			}
		}
	}
?>

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

</script>
<!-- / Yoast Google Analytics -->
