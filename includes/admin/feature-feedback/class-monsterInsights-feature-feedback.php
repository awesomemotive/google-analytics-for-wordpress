<?php

/**
 *
 * @author David Paternina
 */
class MonsterInsights_Feature_Feedback
{
    /**
     * Feedback registered features
     */
    const FEEDBACK_TRACKED_FEATURES = 'monsterinsights_feedback_tracked_features';

    /**
     * Feedback submitted
     */
    const FEEDBACK_SUBMITTED = 'monsterinsights_feedback_submitted';

    public function __construct()
    {
        $this->init_hooks();
    }

    /**
     * Init hooks
     * @return void
     */
    private function init_hooks()
    {
        //  Rest API
        add_action( 'rest_api_init', [$this, 'register_ajax_endpoints'] );

        //  Cron to clear expired feedback tracking
        add_action( 'monsterinsights_feature_feedback_clear_expired', [$this, 'clear_expired_feedback'] );

        if ( ! wp_next_scheduled( 'monsterinsights_feature_feedback_clear_expired' ) ) {
            wp_schedule_event( time(), 'weekly', 'monsterinsights_feature_feedback_clear_expired' );
        }

        //  Cron to send data to Relay
        add_action( 'monsterinsights_feature_feedback_checkin', [$this, 'feature_feedback_checkin'] );

        if ( ! wp_next_scheduled( 'monsterinsights_feature_feedback_checkin' ) ) {
            wp_schedule_event( time(), 'daily', 'monsterinsights_feature_feedback_checkin' );
        }
    }

    public static function get_settings()
    {
        $tracked_features = get_option( self::FEEDBACK_TRACKED_FEATURES, [] );
        return [
            'tracked_features' => $tracked_features
        ];
    }

    /**
     * Register the AJAX endpoints
     *
     * @return void
     */
    public function register_ajax_endpoints()
    {
        register_rest_route( 'monsterinsights/v1', '/feedback', array(
            'methods'               => WP_REST_Server::CREATABLE,
            'callback'              => [$this, 'submit_feedback'],
            'permission_callback'   => [$this, 'monsterinsights_permissions_callback'],
        ));
    }

    /**
     * Clear expired feedback
     * @return void
     */
    public function clear_expired_feedback()
    {
        $tracked_features = get_option( self::FEEDBACK_TRACKED_FEATURES, [] );

        $now = time();

        foreach ($tracked_features as $feature_key => $expires) {
            if ($now > $expires) {
                unset($tracked_features[$feature_key]);
            }
        }

        update_option( self::FEEDBACK_TRACKED_FEATURES, $tracked_features );
    }

    /**
     * Checkin to send feedback to Relay
     * @return void
     */
    public function feature_feedback_checkin()
    {
        $feedback = get_option( self::FEEDBACK_SUBMITTED, [] );

        if ( empty($feedback) ) {
            return;
        }

        $data = [];

        foreach ($feedback as $feature_key => $feedback_data) {
            $data[] = [
                'feature_key'   => $feature_key,
                'rating'        => $feedback_data['rating'],
                'feedback'      => $feedback_data['message'],
            ];
        }

        $api = new MonsterInsights_API_Request( 'feature-feedback', [], 'POST' );

        $result = $api->request([
            'feedback' => $data,
        ]);

        if ( !is_wp_error( $result ) ) {
            //  Clear option
            delete_option( self::FEEDBACK_SUBMITTED );
        }
    }

    /**
     * Process insights feedback
     * @param $request
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function submit_feedback($request )
    {
        if (empty($request['rating']) || empty($request['feature_key'])) {
            return new WP_Error('rest_invalid_param', 'Invalid parameter', ['status' => 400]);
        }

        //  Store feedback to send to Relay later
        $feature_key = $request['feature_key'];
        $feedback = [
            'rating'        => $request['rating'],
            'message'       => $request['message'],
        ];

        $this->save_feedback($feature_key, $feedback);

        //  Store to tracked features option
        $expires = time() + MONTH_IN_SECONDS;
        $this->add_tracked_feature($feature_key, $expires);
    }

    /**
     *
     * @param $feature_key
     * @param $feedback
     * @return void
     */
    private function save_feedback($feature_key, $feedback)
    {
        $stored_feedback = get_option( self::FEEDBACK_SUBMITTED, [] );
        $stored_feedback[$feature_key] = $feedback;

        update_option( self::FEEDBACK_SUBMITTED, $stored_feedback );
    }

    /**
     * @param $feature_key
     * @param $expires
     * @return void
     */
    private function add_tracked_feature($feature_key, $expires)
    {
        $tracked_features = get_option( self::FEEDBACK_TRACKED_FEATURES, [] );
        $tracked_features[$feature_key] = $expires;

        update_option( self::FEEDBACK_TRACKED_FEATURES, $tracked_features );
    }

    /**
     * Check if the user has the required permissions
     *
     * @return bool
     */
    public function monsterinsights_permissions_callback()
    {
        // Check if the user has the required permissions
        return current_user_can( 'monsterinsights_save_settings' );
    }
}

new MonsterInsights_Feature_Feedback();