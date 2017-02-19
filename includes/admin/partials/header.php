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
	<img class="monsterinsights-header-title" src="<?php echo esc_attr( $data['logo'] ); ?>" srcset="<?php echo esc_attr( $data['2xlogo'] ); ?> 2x" alt="<?php esc_attr__( 'MonsterInsights', 'google-analytics-for-wordpress' ); ?>"/>
	<img class="monsterinsights-header-mascot" src="<?php echo esc_attr( $data['mascot'] ); ?>" srcset="<?php echo esc_attr( $data['2xmascot'] ); ?> 2x" alt="<?php esc_attr__( 'MonsterInsights Mascot', 'google-analytics-for-wordpress' ); ?>"/>
</div>