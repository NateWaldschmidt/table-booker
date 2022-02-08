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
        register_rest_route('tb/v1', 'reservations/(?P<restaurant_id>\d+)', [
            'methods' => 'GET',
            'callback' => ['TB_Reservation_REST_API','get_restaurant_res'],
        ]);
        register_rest_route('tb/v1', 'reservations', [
            'methods' => 'POST',
            'callback' => ['TB_Reservation_REST_API','post'],
        ]);
        register_rest_route('tb/v1', 'reservations', [
            'methods' => 'PUT',
            'callback' => ['TB_Reservation_REST_API','put'],
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
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 401]);
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
     * Used for getting a restaurant's available
     * reservations.
     * 
     * @static
     */
    static function get_restaurant_res($req) {
        global $wpdb;

        $restaurant_id = $req['restaurant_id'];

        $results = $wpdb->get_results("
            SELECT
                restaurant_id,
                reservation_time,
                reservation_party_size
            FROM {$wpdb->prefix}tb_reservations
            WHERE restaurant_id = {$restaurant_id} AND reservation_status = 0;
        ");

        /** @var WP_REST_Response The Wordpress response to send back with the data. */
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
        // if (get_current_user_id() == 0) {
        //     return new WP_Error('invalid_user', 'Invalid User.', ['status' => 401]);
        // }

        /** @var object The submitted data sent with the request. */
        $req_data = $req->get_params();

        /** @var object Sanitized and validated data that is safe for database insertion. */
        $safe_data = new stdClass();

        // Restaurant ID validation.
        if (isset($req_data['restaurant-id'])) {
            $valid_id = new WP_Query([
                'author'    => 1,
                'post_type' => 'restaurant',
                'p'         => (int)$req_data['restaurant-id'],
                'status'    => 'any'
            ]);
            $valid_id = $valid_id->posts[0]->ID;

            // Ensures the ID was found.
            if ($valid_id == $req_data['restaurant-id']) {
                $safe_data->restaurant_id = $valid_id;
            } else {
                return new WP_Error(
                    'restaurant_not_found',
                    'Restaurant not found.',
                    ['status' => 404]
                );
            }

        } else {
            return new WP_Error(
                'no_restaurant_id',
                'Missing restaurant ID.',
                ['status' => 400]
            );
        }

        // Ensures the reservation time is set.
        if (isset($req_data['reservation-time'])) {
            // Formats the date.
            $safe_data->reservation_time = date_format(
                date_create($req_data['reservation-time']),
                'Y-m-d H:i:s'
            );

        } else {
            return new WP_Error(
                'no_reservation_datetime',
                'Missing reservation time.',
                ['status' => 400]
            );
        }
        
        // Validates reservation party size..
        if (isset($req_data['reservation-party-size'])) {
            // Ensures the reservation party size is a number.
            if (!is_numeric($req_data['reservation-party-size'])) {
                return new WP_Error(
                    'non_numeric_party_size',
                    'Non-numeric reservation party size.',
                    ['status' => 400]
                );
            }

            // Ensures the party size is greater than 0.
            if ((int)$req_data['reservation-party-size'] <= 0) {
                return new WP_Error(
                    'lt_zero_party_size',
                    'Less than 0 reservation party size.',
                    ['status' => 400]
                );
            }

            // Ensures the party size is less than 100.
            if ((int)$req_data['reservation-party-size'] >= 100) {
                return new WP_Error(
                    'gt_one_hundred_party_size',
                    'Greater than 100 reservation party size.',
                    ['status' => 400]
                );
            }

            $safe_data->reservation_party_size = (int)$req_data['reservation-party-size'];

        } else {
            return new WP_Error(
                'no_reservation_party_size',
                'Missing reservation party size.',
                ['status' => 400]
            );
        }

        // Inserts the new reservation with a confirmed status.
        $success = $wpdb->insert($wpdb->prefix.'tb_reservations', [
            'restaurant_id'          => $safe_data->restaurant_id,
            'reservation_time'       => $safe_data->reservation_time,
            'reservation_party_size' => $safe_data->reservation_party_size,
            'reservation_status'     => 2
        ], [
            '%d',
            '%s',
            '%d'
        ]);

        // Detects an error with the submission of the new reservation.
        if ($success === false) {
            return new WP_Error(
                'reservation_error',
                'Error saving reservation.',
                ['status' => 500]
            );
        }

        // Returns back the inserted ID.
        $return_data = new stdClass();
        $return_data->ID = $wpdb->insert_id;

        /** @var WP_REST_Response The Wordpress response to send back. */
        $response = new WP_REST_Response($return_data);
        $response->set_status(201);

        return $response;
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
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 401]);
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