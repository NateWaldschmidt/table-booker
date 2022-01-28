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
        register_rest_route('tb/v1', 'reservations', [
            'methods' => 'PUT',
            'callback' => ['TB_Reservation_REST_API','post'],
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
        $results = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}tb_reservations
            WHERE reservation_user_id = ".get_current_user_id().";
        ");

        // Sets up the response.
        $response = new WP_REST_Response($results);
        $response->set_status(200);
    
        return $response;
    }

    /**
     * This will allow the creation of now reservations
     * and is intended for use only be restaurant
     * owning users.
     * 
     * @static
     */
    static function post($req) {
        global $wpdb;

        // User validation.
        if (get_current_user_id() == 0) {
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 403]);
        }

        /** @var object The submitted data sent with the request. */
        $req_data = $req->get_params();
    }

    /**
     * This will validate the user and allow the 
     * update of the reservation.  This is
     * particularly used when a user is accepting a reservation.
     * 
     * @static
     */
    static function put($req) {
        global $wpdb;

        // User validation.
        if (get_current_user_id() == 0) {
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 403]);
        }
        
        /** @var object The submitted data sent with the request. */
        $req_data = $req->get_params();

        /** @var array This is what will be used to insert that valid data into the database. */
        $valid_data = array();

        // Validation of the reservation name.
        try {
            $valid_data['reservation_name'] = self::validate_reservation_name($req_data['reservation-name']);
        } catch (Exception $e) {
            return new WP_Error(
                'invalid_res_name',
                $e->getMessage(),
                ['status' => 400]
            );
        }

        // Updates the requested reservation.
        $success = $wpdb->update(
            "{$wpdb->prefix}tb_reservations",
            [
                "reservation_name" => $valid_data['reservation_name'],
                "reservation_user_id" => get_current_user_id(),
                "reservation_status" => 2,
                "reservation_party_size" => $valid_data['reservation_party_size'],
                "reservation_public" => 0,
            ], [
                "reservation_id" => $valid_data['reservation_id'],
            ], [
                '%d',
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
                '%d',
            ], [
                '%d',
            ]
        );

        /** @var WP_REST_Response This is the object sent back to the user with the success status code. */
        $response = new WP_REST_Response();
        $response->set_status(204);

        return $response;
    }

    /**
     * Validates and sanitizes the reservation name 
     * through the check of string length.
     * 
     * @static
     */
    static function validate_reservation_name(string $res_name) {
        if (isset($res_name)) {
            // Validates the length of the string.
            if (strlen($req_data['reservation-name']) > 40) {
                throw new Exception('Invalid reservation name. Maximum length is 40 characters.');
            }

            // Sanitizes the string.
            return sanitize_text_field($req_data['reservation-name']);
        }
    }
}

add_action('rest_api_init', function() { new TB_Reservation_REST_API(); });