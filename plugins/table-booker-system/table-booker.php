<?php
/** 
 * @wordpress-plugin
 * Plugin Name: Table Booker System
 * Description: Manages restaurants and reservations.
 * Version:     0.0.1
 * Author:      Blue Group
 */

global $wpdb;
global $tb_db_version;
$td_db_version = '0.0.1';

require plugin_dir_path(__FILE__).'/shortcodes/sc-form-new-reservation.php';

// Ensures this class is not being used anywhere else.
if (!class_exists('TB_Init')) {
    /**
     * Initializes the custom post types for this 
     * Table Booker plugin.
     * 
     * @package    table-booker
     * @author     Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
     */
    class TB_Init {
        public function __construct() {
            // Initial creation of the plugin's database tables.
            register_activation_hook(__FILE__, ['TB_Init', 'create_reservation_table']);

            // Adds the restaurant post type.
            self::add_restaurant_post_type();

            // Adds restaurant category taxonomy.
            self::add_restaurant_taxonomy();

            // Adds all of the shortcodes.
            self::add_shortcodes();
        }

        /**
         * Does the initial creation of the wordpress table.
         * 
         * @static
         */
        static function create_reservation_table() {
            // Adds the version of the table being created.
            add_option('td_db_version', $td_db_version);

            // Reservation Table Name.
            $table_name = $wpdb->prefix.'td_reservations';

            $charset_collate = $wpdb->get_charset_collate();

            // Table SQL.
            $sql = "CREATE TABLE $table_name (
                ID                     INT         NOT NULL AUTO_INCREMENT PRIMARY KEY,
                restaurant_id          INT         NOT NULL FOREIGN KEY REFERENCES {$wpdb->prefix}posts (ID),
                reservation_time       DATETIME    NOT NULL,
                reservation_name       VARCHAR(40),
                reservation_user_id    INT                  FOREIGN KEY,
                reservation_status     INT         NOT NULL                CHECK (status >= -1 AND status <= 3),
                reservation_party_size INT         UNSIGNED,
                reservation_notes      VARCHAR(255),
                reservation_public     BOOLEAN     NOT NULL DEFAULT 0
            ) $charset_collate";

            // Actually creates the table.
            // dbDelta($sql);
        }

        /**
         * Creates the restaurant post type. This will assign
         * the necessary labels and permissions to the 
         * restaurant post type.
         * 
         * @static
         */
        static function add_restaurant_post_type() {
            register_post_type('restaurant', [
                'labels' => [
                    'name'          => 'Restaurants',
                    'singular_name' => 'Restaurant',
                    'add_new'       => 'Add New Restaurant',
                    'add_new_item'  => 'Add New Restaurant',
                    'edit_item'     => 'Edit Restaurant',
                    'all_items'     => 'All Restaurants',
                    'search_items'  => 'Search Restaurants',
                ],
                'public'              => true,
                'hierarchical'        => true,
                'publicly_queryable'  => true,
                'exclude_from_search' => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'has_archive'         => true,
                'rewrite'             => true,
                'query_var'           => true,
                'supports'            => ['title'],
                'capabilities' => [
                    'edit_post'          => 'edit_restaurant',
                    'edit_posts'         => 'edit_restaurants',
                    'edit_others_posts'  => 'edit_other_restaurants',
                    'publish_posts'      => 'publish_restaurants',
                    'read_post'          => 'read_restaurant',
                    'read_private_posts' => 'read_private_restaurants',
                    'delete_post'        => 'delete_restaurant'
                ],
            ]);

            // Adds meta boxes for the Restaurant post type.
            add_action('add_meta_boxes', ['TB_Init', 'restaurant_information']);

            // Assigns the new permissions to the custom restaurant owner user type.
            self::assign_restaurant_perms();

            // Adds the hook for saving Restaurant Meta data.
            add_action('save_post', ['TB_Init', 'save_restaurant_meta']);
        }

        /**
         * Assigns the different restaurant meta capabilities
         * to the newly defined role, restaurant owner.
         * 
         * @static
         */
        static function assign_restaurant_perms():void {
            // Creates a new role called restaurant owner.
            $ro_role = add_role('restaurant_owner', 'Restaurant Owner', array());

            // If the role already exists, $role will be null.
            if ($ro_role !== null) {
                $ro_role = get_role('restaurant_owner');

                // Adds permissions to the new role.
                $ro_role->add_cap('read',                      true);
                $ro_role->add_cap('edit_restaurant',           true);
                $ro_role->add_cap('edit_restaurants',          false);
                $ro_role->add_cap('edit_others_restaurants',   false);
                $ro_role->add_cap('publish_restaurants',       true);
                $ro_role->add_cap('read_restaurant',           true);
                $ro_role->add_cap('read_private_restaurants',  false);
                $ro_role->add_cap('delete_restaurant',         true);
                $ro_role->add_cap('delete_others_restaurants', false);
                $ro_role->add_cap('view_admin_dashboard',      true); // Maybe remove?
            }

            $admin_role = get_role('administrator');

            // Adds permissions to the admin role.
            $admin_role->add_cap('edit_restaurant',           true);
            $admin_role->add_cap('edit_restaurants',          true);
            $admin_role->add_cap('edit_others_restaurants',   true);
            $admin_role->add_cap('publish_restaurants',       true);
            $admin_role->add_cap('read_restaurant',           true);
            $admin_role->add_cap('read_private_restaurants',  true);
            $admin_role->add_cap('delete_restaurant',         true);
            $admin_role->add_cap('delete_others_restaurants', true);
        }

        /**
         * Creates the taxonomy for restaurant 
         * categories. This allows the categorization of
         * different types of restaurants.
         * 
         * @static
         */
        static function add_restaurant_taxonomy() {
            register_taxonomy('category', ['restaurant'], [
                'hierarchical' => false,
                'labels' => [
                    'name'          => _x( 'Restaurant Type', 'taxonomy general name' ),
                    'singular_name' => _x( 'Restaurant Type', 'taxonomy singular name' ),
                    'search_items'  => __( 'Search Restaurant Types' ),
                    'all_items'     => __( 'All Restaurant Types' ),
                    'edit_item'     => __( 'Edit Restaurant Type' ), 
                    'update_item'   => __( 'Update Restaurant Type' ),
                    'add_new_item'  => __( 'Add New Restaurant Type' ),
                    'new_item_name' => __( 'New Restaurant Type' ),
                    'menu_name'     => __( 'Restaurant Types' ),
                ],
                'show_ui'           => true,
                'show_in_rest'      => true,
                'show_admin_column' => true,
                'query_var'         => true,
            ]);
        }

        /**
         * Adds all of the shortcodes for the plugin.
         * Requirements for the files should be at the top of
         * this file.
         * 
         * @static
         */
        static function add_shortcodes() {

        }

        /**
         * Creates the restaurant meta box section for
         * the restaurant post type.
         * 
         * @static
         */
        static function restaurant_information() {
            add_meta_box(
                'restaurant-information',
                'Restaurant Information',
                ['TB_Init', 'display_restaurant_information'],
                'restaurant'
            );
        }
        
        /**
         * Creates the markup for the form to add
         * additional post meta data. If a new piece of 
         * meta data is decided to be added to the
         * restaurant post type it should go here. This will
         * only show through the admin side.
         * 
         * @static
         */
        static function display_restaurant_information($post) {
            // Creates the form for the restaurant post meta data.
            ob_start();
                wp_nonce_field('restaurant_meta_box_nonce', 'restaurant_meta_box_nonce');
                ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th><label for="restaurant-street-address">Street Address</label></th>
                            <td>
                                <input
                                id="restaurant-street-address"
                                name="restaurant-street-1"
                                type="text"
                                maxlength="100"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_street_1', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-addit-street-address">Additional Address Information</label></th>
                            <td>
                                <input
                                id="restaurant-addit-street-address"
                                name="restaurant-street-2"
                                type="text"
                                maxlength="50"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_street_2', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-city">City/ Town</label></th>
                            <td>
                                <input
                                id="restaurant-city"
                                name="restaurant-city"
                                type="text"
                                maxlength="50"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_city', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-zip">Zip Code</label></th>
                            <td>
                                <input
                                id="restaurant-zip"
                                name="restaurant-zip"
                                type="text"
                                maxlength="9"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_zip', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-state">State/ Province</label></th>
                            <td>
                                <input
                                id="restaurant-state"
                                name="restaurant-state"
                                type="text"
                                maxlength="50"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_state', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-country">Country</label></th>
                            <td>
                                <input
                                id="restaurant-country"
                                name="restaurant-country"
                                type="text"
                                maxlength="50"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_country', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-telephone">Main Phone Number</label></th>
                            <td>
                                <input
                                id="restaurant-telephone"
                                name="restaurant-phone-primary"
                                type="tel"
                                minlength="10"
                                maxlength="15"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_phone_primary', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-website">Main Website</label></th>
                            <td>
                                <input
                                id="restaurant-website"
                                name="restaurant-website"
                                type="url"
                                value="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_website', true)); ?>"
                                />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-pricing">Average Meal Cost</label></th>
                            <td>
                                <select name="restaurant-pricing" id="restaurant-pricing">
                                    <?php $price_rating = get_post_meta($post->ID, 'restaurant_pricing', true); ?>
                                    <option value="1" <?php if ($price_rating == 1) echo 'selected'; ?>>$0.00-$14.99</option>
                                    <option value="2" <?php if ($price_rating == 2) echo 'selected'; ?>>$15.00-$29.99</option>
                                    <option value="3" <?php if ($price_rating == 3) echo 'selected'; ?>>$30.00-$44.99</option>
                                    <option value="4" <?php if ($price_rating == 4) echo 'selected'; ?>>$45.00-$+</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-description">Description</label></th>
                            <td>
                                <?php
                                wp_editor(get_post_meta($post->ID, 'restaurant_description', true),'restaurant-description',[
                                    'media_buttons' => false,
                                    'teeny'         => true,
                                    'textarea_rows' => 5,
                                    'quicktags'     => false,
                                ]);
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php echo ob_get_clean();
        }

        /**
         * This hooks into post saving and processes the post
         * meta data when it is there to be submitted.
         * 
         * @static
         */
        static function save_restaurant_meta($post_id):void {
            // Ensure the nonce is set.
            if (!isset($_POST['restaurant_meta_box_nonce'])) {
                return;
            }
            
            // Validate the nonce.
            if (!wp_verify_nonce($_POST['restaurant_meta_box_nonce'], 'restaurant_meta_box_nonce')) {
                return;
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
        }
    }
    add_action('init', function() { new TB_Init(); });
}


