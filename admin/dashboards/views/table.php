<div class='yoast-dashboard yoast-data-table' id="table-<?php echo $dashboard; ?>" data-label="<?php echo $settings['title']; ?>" data-dimension="<?php echo (!empty($settings['custom-dimension-id'])) ? $settings['custom-dimension-id'] : ''; ?>">
	<h3>
		<span class='alignleft'><?php echo $settings['title']; ?></span>
		<?php
		if ( ! empty( $settings['help'] ) ) {
			echo Yoast_GA_Admin_Form::show_help( 'graph-' . $dashboard, $settings['help'] );
		}
		?>
		<span class='alignright period'><?php echo __( 'Last month', 'google-analytics-for-wordpress' ); ?></span>
	</h3>

	<div>
		<table class="widefat fixed stripe">
			<thead>
				<th><?php echo $settings['title']; ?></th>
				<?php foreach($settings['columns'] As $columns) { ?>
				<th><?php echo $columns; ?></th>
				<?php } ?>
			</thead>
		</table>
	</div>
</div>