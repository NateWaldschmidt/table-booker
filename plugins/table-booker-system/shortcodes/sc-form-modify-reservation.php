<?php
/**
 * Provides a front-end form for users to create
 * new restaurants.  This is intended to be 
 * un-styled to allow for theme styling.
 * 
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 * @return string HTML to be used in the shortcode.
 */

function tb_modify_reservation_form():string {
    global $wpdb;
    
    $results = $wpdb->get_results("
            SELECT *
            FROM {$wpdb->prefix}tb_reservations
            WHERE reservation_user_id = ".get_current_user_id().";
        ");
   
    ob_start(); var_dump($results); ?>
    <!-- 
        Make sure to load up people using sql
        1. Button for Cancel 
        2. Number input to adjust the number of people

        object(stdClass)[713]
      public 'ID' => string '1' (length=1)
      public 'restaurant_id' => string '1' (length=1)
      public 'reservation_time' => string '0000-00-00 00:00:00' (length=19)
      public 'reservation_name' => null
      public 'reservation_user_id' => string '1' (length=1)
      public 'reservation_status' => string '0' (length=1)
      public 'reservation_party_size' => string '5' (length=1)
      public 'reservation_notes' => null
      public 'reservation_public' => string '0' (length=1)


     -->
     <form>

        <label for="wordpress-id"> Resturant ID: </label>
        <p><input type="text" id = "wordpress-id"></p>
        
        <label for="tb-restaurant-id"> Resturant ID: </label>
        <p><input type="text" id = "tb-restaurant-id"></p>

        <label for="tb-reservation-name"> Reservation Name: </label>
        <p><input type="text" id = "tb-reservation-name"></p>

        <label for="tb-reservation-id"> User ID: </label>
        <p><input type="text" id = "tb-user-id"></p>

        <label for="tb-reservation-time"> Reservation Time: </label>
        <p><input type="text" id = "tb-reservation-time"></p>

        <label for="tb-reservation-status"> Status: </label>
        <p><input type="text" id ="tb-reservation-status"></p>


        <label for="tb-party-size"> Party Size: </label>
        <p><input type="text" id ="tb-party-size"></p>

        <label for="td-reservation-notes">Reservation Notes: </label>
        <p><textarea id="tb-reservation-notes"></textarea></p>


        <button id="tb-res-cancel">Cancel</button>

    </form>
    <?php return ob_get_clean();
}
add_shortcode('tb-modify-reservation-form', 'tb_modify_reservation_form');