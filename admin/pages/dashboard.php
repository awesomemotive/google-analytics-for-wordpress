<?php
global $yoast_ga_admin;

echo $yoast_ga_admin->content_head();
?>
	<h2 id="yoast_ga_title"><?php echo __( 'Yoast Google Analytics: ', 'google-analytics-for-wordpress' ) . __( 'Dashboard', 'google-analytics-for-wordpress' ); ?></h2>

	<script>
		var yoast_ga_dashboard_nonce = '<?php echo wp_create_nonce( 'yoast-ga-dashboard-nonce' ); ?>';
	</script>

	<div class="tabwrapper">
		<div id="extensions" class="wpseotab gatab active">
			<div class="yoast-graphs">

				<?php
					Yoast_GA_Dashboards_Graph::get_instance()->display();
				?>
			</div>
		</div>
	</div>


<?php
echo $yoast_ga_admin->content_footer();
?>