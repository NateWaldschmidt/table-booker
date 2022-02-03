<?php
/**
 * Allows the creation, reading, editing, and 
 * deleting of restaurants within the system.
 * 
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 */
class TB_Restaurant_REST_API {
    function __construct() {
        register_rest_route('tb/v1', 'restaurant', [
            'methods' => 'GET',
            'callback' => ['TB_Restaurant_REST_API','get'],
        ]);
        register_rest_route('tb/v1', 'restaurant', [
            'methods' => 'POST',
            'callback' => ['TB_Restaurant_REST_API','post'],
        ]);
    }

    /**
     * Validates the user and then returns back the
     * requested data.
     * 
     * @static
     */
    static function get() {
        global $wpdb;

        // Invalid user.
        if (get_current_user_id() == 0) {
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 403]);
        }

        /** @var WP_Query The query results for the user's restaurants. */
        $user_restaurants = new WP_Query([
            'author'    => get_current_user_id(),
            'post_type' => 'restaurant',
            'status'    => 'any'
        ]);

        /** @var array Will be used to send back restaurant data to the user. */
        $response_data = array();

        /** @var int Tracks which index of the user's restaurants we are on. */
        $data_index = 0;

        if ($user_restaurants->have_posts()) {
            while($user_restaurants->have_posts()) {
                $user_restaurants->the_post();
                $response_data[$data_index] = new stdClass();

                $response_data[$data_index]->ID = get_the_ID();
                $response_data[$data_index]->title = get_post()->post_title;
                $response_data[$data_index]->street_1 = get_post_meta(
                    get_the_ID(),
                    'restaurant_street_1',
                    true
                );
                $response_data[$data_index]->street_2 = get_post_meta(
                    get_the_ID(),
                    'restaurant_street_2',
                    true
                );
                $response_data[$data_index]->city = get_post_meta(
                    get_the_ID(),
                    'restaurant_city',
                    true
                );
                $response_data[$data_index]->zip = get_post_meta(
                    get_the_ID(),
                    'restaurant_zip',
                    true
                );
                $response_data[$data_index]->state = get_post_meta(
                    get_the_ID(),
                    'restaurant_state',
                    true
                );
                $response_data[$data_index]->country = get_post_meta(
                    get_the_ID(),
                    'restaurant_country',
                    true
                );
                $response_data[$data_index]->phone_primary = get_post_meta(
                    get_the_ID(),
                    'restaurant_phone_primary',
                    true
                );
                $response_data[$data_index]->website = get_post_meta(
                    get_the_ID(),
                    'restaurant_website',
                    true
                );
                $response_data[$data_index]->pricing = get_post_meta(
                    get_the_ID(),
                    'restaurant_pricing',
                    true
                );
                $response_data[$data_index]->description = get_post_meta(
                    get_the_ID(),
                    'restaurant_description',
                    true
                );

                $data_index++;
            }
        }

        /** @var WP_REST_Response The response objects to send back the data and status code. */
        $response = new WP_REST_Response($response_data);
        $response->set_status(200);
    
        return $response;
    }

    /**
     * Validates the user and then will create a new 
     * restaurant for the user.
     * 
     * @static
     */
    static function post() {
        global $wpdb;

        // Invalid user.
        if (get_current_user_id() == 0) {
            return new WP_Error('invalid_user', 'Invalid User.', ['status' => 403]);
        }

        // Title Sanitization
        if (isset($_POST['restaurant-title'])) {
            $title = $_POST['restaurant-title'];
            $title = sanitize_text_field($title);
        } else {
            return new WP_Error('invalid-format', 'Invalid Format (Missing Title).', ['status' => 400]);
        }

        // Creates the restaurant post.
        $post_id = wp_insert_post([
            "post_title"  => $title,
            "post_type"   => 'restaurant',
            "post_status" => 'publish'
        ]);

        // Catches error when registering restaurant.
        if ($post_id <= 0) {
            return new WP_Error('restaurant-creation-failure', 'Failed to create restaurant.', ['status' => 500]);
        }

        // Preps the file to be base64 encoded and saved in the Database.
        if (isset($_FILES['restaurant-photo']['tmp_name']) && strlen($_FILES['restaurant-photo']['tmp_name']) > 0) {
            $mime = mime_content_type($_FILES['restaurant-photo']['tmp_name']);
            $file = "data:$mime;base64,".base64_encode(file_get_contents($_FILES['restaurant-photo']['tmp_name']));

            if ($file) {
                update_post_meta($post_id, 'restaurant_photo', $file);
            }
        }

        // Street Address 1 Validation and Sanitization.
        if (isset($_POST['restaurant-street-1'])) {
            $street_1 = $_POST['restaurant-street-1'];
            $street_1 = strlen($street_1) > 100 ? substr($street_1, 0, 100) : $street_1;
            $street_1 = sanitize_text_field($street_1);

            update_post_meta($post_id, 'restaurant_street_1', $street_1);
        }

        // Street Address 2 Validation and Sanitization.
        if (isset($_POST['restaurant-street-2'])) {
            $street_2 = $_POST['restaurant-street-2'];
            $street_2 = strlen($street_2) > 50 ? substr($street_2, 0, 50) : $street_2;
            $street_2 = sanitize_text_field($street_2);

            update_post_meta($post_id, 'restaurant_street_2', $street_2);
        }

        // City Validation and Sanitization.
        if (isset($_POST['restaurant-city'])) {
            $city = $_POST['restaurant-city'];

            // Length validation.
            if (strlen($city) <= 50) {
                $city = sanitize_text_field($city);
                update_post_meta($post_id, 'restaurant_city', $city);
            }
        }

        // Zip Code Validation and Sanitization.
        if (isset($_POST['restaurant-zip'])) {
            $zip = $_POST['restaurant-zip'];

            // Max zip length is 9.
            if (strlen($zip) <= 9) {
                $zip = sanitize_text_field($zip);
                update_post_meta($post_id, 'restaurant_zip', $zip);
            }
        }

        // State Validation and Sanitization.
        if (isset($_POST['restaurant-state'])) {
            $state = $_POST['restaurant-state'];

            // Length validation.
            if (strlen($state) <= 50) {
                $state = sanitize_text_field($state);
                update_post_meta($post_id, 'restaurant_state', $state);
            }
        }

        // Country Validation and Sanitization.
        if (isset($_POST['restaurant-country'])) {
            $country = $_POST['restaurant-country'];

            // Length validation.
            if (strlen($country) <= 50) {
                $country = sanitize_text_field($country);
                update_post_meta($post_id, 'restaurant_country', $country);
            }
        }

        // Main Phone Validation and Sanitization.
        if (isset($_POST['restaurant-phone-primary'])) {
            $phone = $_POST['restaurant-phone-primary'];

            // Minimum length validation.
            if (strlen($phone) >= 10 && strlen($phone) <= 15) {
                if (is_numeric($phone)) {
                    update_post_meta($post_id, 'restaurant_phone_primary', $phone);
                }
            }
        }

        // Main Website Validation and Sanitization.
        if (isset($_POST['restaurant-website'])) {
            $website = $_POST['restaurant-website'];

            if (strlen($website) <= 50) {
                $website = sanitize_text_field($website);
                update_post_meta($post_id, 'restaurant_website', $website);
            }
        }

        // Price Point Validation and Sanitization.
        if (isset($_POST['restaurant-pricing'])) {
            $price = (int)$_POST['restaurant-pricing'];

            // Validates it is a proper setting.
            if ($price >= 1 && $price <= 4) {
                update_post_meta($post_id, 'restaurant_pricing', $price);
            }
        }

        // Description Validation and Sanitization.
        if (isset($_POST['restaurant-description'])) {
            $description = $_POST['restaurant-description'];

            if (strlen($description) <= 250) {
                $description = sanitize_textarea_field($description);
                update_post_meta($post_id, 'restaurant_description', $description);
            }
        }

        /** @var WP_REST_Response The response objects to send back the success status code. */
        $response = new WP_REST_Response(get_post_permalink($post_id));
        $response->set_status(201);
    
        return $response;
    }
}
add_action('rest_api_init', function() { new TB_Restaurant_REST_API(); });