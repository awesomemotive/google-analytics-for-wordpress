<div id="graph-<?php echo $dashboard; ?>" class="yoast-graph" data-label="<?php echo $settings['data-label']; ?>">
	<h2><?php echo $settings['title']; ?></h2>

	<?php if ( empty( $settings['hide_y_axis'] ) ) {
		echo "<div class='yoast-graph-yaxis'></div >";
	} ?>
	<div class="yoast-graph-holder"></div>

	<?php if ( empty( $settings['hide_x_axis'] ) ) {
		echo "<div class='yoast-graph-xaxis'></div >";
	} ?>
</div>