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
            register_activation_hook(__FILE__, [$this, 'create_reservation_table']);

            // Adds the restaurant post type.
            self::add_restaurant_post_type();

            // Adds restaurant category taxonomy.
            self::add_restaurant_taxonomy();

            // Adds all of the shortcodes.
            self::add_shortcodes();

            // Adds the meta boxes to the restaurant post pages.
            add_action('add_meta_boxes', ['TB_Init', 'restaurant_information']);
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

            // Assigns the new permissions to the custom restaurant owner user type.
            self::assign_restaurant_perms();
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

        static function add_restaurant() {

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
         * restaurant post type it should go here.
         * 
         * @static
         */
        static function display_restaurant_information($post) {
            // Creates the form for the restaurant post meta data.
            ob_start(); ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th><label for="restaurant-street-address">Street Address</label></th>
                            <td><input id="restaurant-street-address" type="text" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-addit-street-address">Additional Address Information</label></th>
                            <td><input id="restaurant-addit-street-address" type="text" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-city">City/ Town</label></th>
                            <td><input id="restaurant-city" type="text" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-zip">Zip Code</label></th>
                            <td><input id="restaurant-zip" type="text" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-state">State/ Province</label></th>
                            <td><input id="restaurant-state" type="text" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-country">Country</label></th>
                            <td><input id="restaurant-country" type="text" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-telephone">Main Phone Number</label></th>
                            <td><input id="restaurant-telephone" type="tel" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-website">Main Website</label></th>
                            <td><input id="restaurant-website" type="url" /></td>
                        </tr>
                        <tr>
                            <th><label for="restaurant-description">Description</label></th>
                            <td><?php
                                $editor_settings = array(
                                    'media_buttons' => false,
                                    'teeny'         => true,
                                    'textarea_rows' => 5,
                                    'quicktags'     => false,
                                );
                                wp_editor('','restaurant-description',$editor_settings);
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            <?= ob_get_clean();
        }
    }
    add_action('init', function() { new TB_Init(); });
}


