<?php
/**
 * Notices admin class.  
 *
 * Handles retrieving whether a particular notice has been dismissed or not,
 * as well as marking a notice as dismissed.
 *
 * @since 6.0.0
 *
 * @package MonsterInsights
 * @subpackage Notices
 * @author  Chris Christoff
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class MonsterInsights_Notice_Admin {

    /**
     * Holds all dismissed notices
     *
     * @access public
     * @since 6.0.0
     * @var array $notices Array of dismissed notices.
     */
    public $notices;

    /**
     * Primary class constructor.
     *
     * @access public
     * @since 6.0.0
     */
    public function __construct() {

        // Populate $notices
        $this->notices = get_option( 'monsterinsights_notices' );
        if ( ! is_array( $this->notices ) ) {
            $this->notices = array();
        }

    }

    /**
     * Checks if a given notice has been dismissed or not
     *
     * @access public
     * @since 6.0.0
     * 
     * @param string $notice Programmatic Notice Name
     * @return bool Notice Dismissed
     */

    public function is_dismissed( $notice ) {
        if ( ! isset( $this->notices[ $notice ] ) ) {
            return false;
        }
        return true;

    }

    /**
     * Marks the given notice as dismissed
     *
     * @access public
     * @since 6.0.0
     * 
     * @param string $notice Programmatic Notice Name
     * @return null
     */
    public function dismiss( $notice ) {
        $this->notices[ $notice ] = true;
        update_option( 'monsterinsights_notices', $this->notices );

    }


    /**
     * Marks a notice as not dismissed
     *
     * @access public
     * @since 6.0.0
     *
     * @param string $notice Programmatic Notice Name
     * @return null
     */
    public function undismiss( $notice ) {
        unset( $this->notices[ $notice ] );
        update_option( 'monsterinsights_notices', $this->notices );

    }

    /**
     * Displays an inline notice with some MonsterInsights styling.
     *
     * @access public
     * @since 6.0.0
     *
     * @param string    $notice             Programmatic Notice Name
     * @param string    $title              Title
     * @param string    $message            Message
     * @param string    $type               Message Type (updated|warning|error) - green, yellow/orange and red respectively.
     * @param string    $button_text        Button Text (optional)
     * @param string    $button_url         Button URL (optional)
     * @param bool      $is_dismissible     User can Dismiss Message (default: false)
     */ 
    public function display_inline_notice( $name, $title, $message, $type = 'success', $is_dismissible = false, $args = array() ) {
        /* Available/Planned $args options
         * $args = array(
         *  'primary'    => array(
         *      'text'   => '',
         *      'url'    => '',
         *      'target' => '',
         *      'class'  => 'button button-primary',
         *  ),
         *  'secondary'  => array(
         *      'text'   => '',
         *      'url'    => '',
         *      'target' => '',
         *      'class'  => 'button button-secondary',
         *  ),
         *  'skip_message_escape' => true // note: This param is for internal use only. Do not use as a developer.
         * );
         */


        // Check if the notice is dismissible, and if so has been dismissed.
        if ( $is_dismissible && $this->is_dismissed( $name ) ) {
            // Nothing to show here, return!
            return '';
        }

        $dismissible = ( $is_dismissible ) ? ' is-dismissible': '';

        // Display inline notice
        ob_start();
        ?>
        <div class="monsterinsights-notice <?php echo 'monsterinsights-' . esc_attr( $type ) . '-notice' . $dismissible; ?>" data-notice="<?php echo esc_attr( $name ); ?>">
            <div class="monsterinsights-notice-icon <?php echo 'monsterinsights-' . esc_attr( $type ) . '-notice-icon'?>">
            </div>
            <div class="monsterinsights-notice-text <?php echo 'monsterinsights-' . esc_attr( $type ) . '-notice-text'?>">
                <?php
                // Title
                if ( ! empty ( $title ) ) {
                    ?>
                    <p class="monsterinsights-notice-title"><?php echo esc_html( $title ); ?></p>
                    <?php
                }

                // Message
                if ( ! empty( $message ) ) {
                    if ( empty( $args['skip_message_escape'] ) ) {
                        ?>
                        <p class="monsterinsights-notice-message"><?php echo esc_html( $message ); ?></p>
                        <?php
                    } else {
                        ?>
                        <p class="monsterinsights-notice-message"><?php echo $message; ?></p>
                        <?php
                    }
                }
                
                // Primary Button
                if ( ! empty( $args['primary']['text'] ) ) {
                    
                    $text = '';
                    if ( ! empty( $args['primary']['text'] ) ) {
                        $text = $args['primary']['text'];
                    }

                    $url = '#';
                    if ( ! empty( $args['primary']['url'] ) ) {
                        $url = $args['primary']['url'];
                    }

                    $target = '';
                    if ( ! empty( $args['primary']['target'] ) && $args['primary']['target'] === 'blank') {
                        $target = ' target="_blank" rel="noopener noreferrer"';
                    }

                    $class = 'button button-primary';
                    if ( ! empty( $args['primary']['class'] ) ) {
                        $class = ' class="'. $args['primary']['class'] . '"';
                    }
                    ?>
                    <a href="<?php echo esc_attr( $url ); ?>"<?php echo $target; ?><?php echo $class;?>><?php echo esc_html( $text ); ?></a>
                    <?php
                }

                // Secondary Button
                if ( ! empty( $args['secondary']['text'] ) ) {
                    
                    $text = '';
                    if ( ! empty( $args['secondary']['text'] ) ) {
                        $text = $args['secondary']['text'];
                    }

                    $url = '#';
                    if ( ! empty( $args['secondary']['url'] ) ) {
                        $url = $args['secondary']['url'];
                    }           

                    $target = '';
                    if ( ! empty( $args['secondary']['target'] ) && $args['secondary']['target'] === 'blank') {
                        $target = ' target="_blank" rel="noopener noreferrer"';
                    }

                    $class = 'button button-secondary';
                    if ( ! empty( $args['secondary']['class'] ) ) {
                        $class = ' class="'. $args['secondary']['class'] . '"';
                    }
                    ?>
                    <a href="<?php echo esc_attr( $url ); ?>"<?php echo $target; ?><?php echo $class;?>><?php echo esc_html( $text ); ?></a>
                    <?php
                }

                // Dismiss Button
                if ( $is_dismissible ) {
                    ?>
                    <button type="button" class="notice-dismiss<?php echo $dismissible; ?>">
                        <span class="screen-reader-text">
                            <?php esc_html_e( 'Dismiss this notice', 'google-analytics-for-wordpress' ); ?>
                        </span>
                    </button>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}