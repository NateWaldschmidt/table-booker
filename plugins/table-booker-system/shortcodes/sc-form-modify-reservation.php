<?php
/**
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
     

     <?php // Nonce for REST API use. ?>
    <script>const tbReservationNonce = '<?php echo esc_js(wp_create_nonce('wp_rest')); ?>';</script>
        
     <?php if ($results !== null && count($results) > 0): ?>
                <?php foreach($results as $reservation): ?>
                   
                        <form class="tb-modify-reservation">

                            <h2><?php echo esc_html('The reservation id'); ?></h2>
                            <input type="hidden" id ="tb-reservation-id" name="tb-reservation-id"  value="<?php echo esc_attr($reservation->ID); ?>">

                            <label for="tb-reservation-time"> Reservation Time: </label>
                            <?php $reservation->reservation_time[10] = 'T';?>
                            <input type="datetime-local" id = "tb-reservation-time" name="tb-reservation-time" value="<?php echo esc_attr($reservation->reservation_time);?>" >

                            <label for="tb-reservation-name"> Reservation Name: </label>
                            <input type="text" id = "tb-reservation-name" name="tb-reservation-name" value=<?php echo esc_attr($reservation->reservation_name);?> >
                            

                            <label for="tb-reservation-id"> User ID: </label>
                            <input type="text" id = "tb-user-id" name="tb-user-id" value=<?php echo esc_attr($reservation->reservation_user_id);?> >

                        

                            <label for="tb-reservation-status"> Status: </label>
                            <input type="text" id ="tb-reservation-status" name="tb-reservation-status" value=<?php echo esc_attr($reservation->reservation_status);?> >


                            <label for="tb-party-size"> Party Size: </label>
                            <input type="text" id ="tb-party-size" name="tb-party-size" value=<?php echo esc_attr($reservation->reservation_party_size);?> >

                            <label for="td-reservation-notes">Reservation Notes: </label>
                            <textarea id="tb-reservation-notes" name="tb-reservation-notes"  value=<?php echo esc_attr($reservation->reservation_notes);?> ></textarea>
                            
                            <button type="submit" id="tb-res-cancel" name="tb-res-cancel">Cancel</button>
            
                            <button type="submit" id="tb-save-changes" name="tb-save-changes">Update Reservation</button>
 
                        </form>

                         <?php endforeach; ?>
            <?php else: ?>
               
                <h2>You have no reservations.</h2>

            <?php endif; ?>

    <?php return ob_get_clean();
}
add_shortcode('tb-modify-reservation-form', 'tb_modify_reservation_form');
