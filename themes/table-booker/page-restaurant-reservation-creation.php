<?php
/** Template Name: Create Reservation */

// The script to to create the reservation.
wp_enqueue_script(
    'new-reservation',
    get_template_directory_uri().'/assets/js/new-reservation.js',
    array(),
    filemtime(get_template_directory().'/assets/js/new-reservation.js')
);

// Converts to type="module" script.
add_filter(
    'script_loader_tag',
    function($tag, $handle, $src) {
        if ($handle != 'new-reservation') {
            return $tag;
        }
        
        $tag = '<script type="module" src="'.esc_url($src).'"></script>';
        return $tag;
    },
    10,
    3
);

get_header();
?>

<main>
    <h1><?php echo esc_html(the_title()); ?></h1>

    <form id="tb-new-reservation">
        <script>const tbNewReservationNonce = '<?php echo esc_js(wp_create_nonce('wp_rest')); ?>';</script>

        <label for="restaurant-id">Choose your Restaurant</label>
        <select id="restaurant-id" name="restaurant-id">
            <?php
            $user_restaurants = new WP_Query([
                'author'    => get_current_user_id(),
                'post_type' => 'restaurant',
                'status'    => 'any'
            ]);
            ?>
            <?php if ( $user_restaurants->have_posts() ): ?>
                <?php while( $user_restaurants->have_posts() ): $user_restaurants->the_post(); ?>
                    <option value="<?php echo esc_attr( get_the_ID() ); ?>">
                        <?php echo esc_html(get_post()->post_title); ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>

        <label for="reservation-time">Reservation Date and Time</label>
        <input id="reservation-time" name="reservation-time" type="datetime-local" />

        <label for="reservation-party-size">Party Size</label>
        <input id="reservation-party-size" name="reservation-party-size" type="number" value="1" min="1" />

        <button type="submit">Create Reservation</button>
    </form>
</main>

<?php get_footer(); ?>