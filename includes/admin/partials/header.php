<?php
/**
 * Outputs the green MonsterInsights Header
 *
 * @since   6.0.0
 *
 * @package MonsterInsights
 * @subpackage Settings
 * @author 	Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="monsterinsights-header-temp"></div>
<div id="monsterinsights-header" class="monsterinsights-header">
	<div class="monsterinsights-header-inner">
		<img class="monsterinsights-header-title" src="<?php echo esc_attr( $data['logo'] ) . '?v=' . monsterinsights_get_asset_version(); ?>" srcset="<?php echo esc_attr( $data['2xlogo'] ) . '?v=' . monsterinsights_get_asset_version(); ?> 2x" alt="<?php esc_attr__( 'MonsterInsights', 'google-analytics-for-wordpress' ); ?>"/>
	</div>
</div>
