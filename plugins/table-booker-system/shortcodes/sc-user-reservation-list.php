<?php
/**
 * Provides a list of all the user's reservations
 * that they current have scheduled, have completed,
 * or are cancelled.
 * 
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 * @return string HTML to be used in the shortcode.
 */
function tb_user_reservation_list():string {
    global $wpdb;

    // Table names.
    $reservation_table = "{$wpdb->prefix}tb_reservations";
    $post_table = "{$wpdb->prefix}posts";

    // Gets the data used for the reservation list.
    $results = $wpdb->get_results("
        SELECT $reservation_table.*, $post_table.post_title
        FROM $reservation_table
        INNER JOIN $post_table
        ON $reservation_table.restaurant_id = $post_table.ID
        WHERE $reservation_table.reservation_user_id = ".get_current_user_id().";
    ");

    ob_start(); ?>
        <section class="tb-user-reservations">
            <?php if ($results !== null && count($results) > 0): ?>
                <ul class="tb-user-reservations-list">
                    <?php foreach($results as $reservation): ?>
                        <li data-status="<?php echo esc_attr($reservation->reservation_status); ?>">
                            <article class="tb-res">
                                <h2 class="tb-res-rest"><?php echo esc_html($reservation->post_title); ?></h2>
                                <p class="tb-res-status">
                                    <?php if ($reservation->reservation_status == 3): ?>
                                        Completed
                                    <?php elseif ($reservation->reservation_status == 2): ?>
                                        Confirmed
                                    <?php elseif ($reservation->reservation_status == -1): ?>
                                        Cancelled
                                    <?php endif; ?>
                                    </p>
                                <p class="tb-res-datetime"><?php echo esc_html($reservation->reservation_time); ?></p>
                                <p class="tb-res-name"><?php echo esc_html($reservation->reservation_name); ?></p>
                                <p class="tb-res-size">Party of <?php echo esc_html($reservation->reservation_party_size); ?></p>
                                <p class="tb-res-notes"><?php echo esc_html($reservation->reservation_notes); ?></p>
                            <article>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <article>
                    <h2>You have no reservations.</h2>
                </article>
            <?php endif; ?>
        </section>
    <?php return ob_get_clean();
}
add_shortcode('tb-user-reservation-list', 'tb_user_reservation_list');