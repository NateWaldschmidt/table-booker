<?php
/**
 * This class is responsible for handling the REST
 * API endpoints for the reservation's table
 * created within this plugin.
 * 
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 */
class TB_Reservation_REST_API {
    function __construct() {
        register_rest_route('tb/v1', 'reservations', [
            'methods' => 'GET',
            'callback' => ['TB_Reservation_REST_API','get'],
        ]);
    }

    /**
     * This will validate the user and return their
     * available reservations.
     * 
     * @static
     */
    static function get() {
        global $wpdb;
    
        // Invalid user.
        if (get_current_user_id() == 0) {
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 403]);
        }

        // Does the query for the data.
        $results = $wpdb->query("
            SELECT *
            FROM {$wpdb->prefix}tb_reservations
            WHERE reservation_user_id = ".get_current_user_id().";
        ");

        // Sets up the response.
        $response = new WP_REST_Response($results);
        $response->set_status(200);
    
        return $response;
    }
}

add_action('rest_api_init', function() { new TB_Reservation_REST_API(); });

