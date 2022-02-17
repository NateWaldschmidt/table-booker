<?php
    add_action('wp_head', function() {
        ?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/restaurant.css?=<?php echo filemtime(get_template_directory().'/assets/css/restaurant.css'); ?>">
        <?php
    });

    // The script to to submit the reservation.
    wp_enqueue_script(
        'user-reservation',
        get_template_directory_uri().'/assets/js/user-reservation.js',
        array(),
        filemtime(get_template_directory().'/assets/js/user-reservation.js')
    );
    add_filter(
        'script_loader_tag',
        function($tag, $handle, $src) {
            if ($handle != 'user-reservation') {
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
    <section class="restaurant-info">
        <a class="btn-cancel" href="<?php echo bloginfo('url'); ?>">Cancel</a>
        <h1><?php echo esc_html(the_title()); ?></h1>
        <?php
        $restaurant_photo = get_post_meta($post->ID, 'restaurant_photo', true);
        if (!empty($restaurant_photo)):
            ?>
            <img
            class="restaurant-photo"
            src="<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_photo', true)); ?>"
            alt="<?php echo esc_html(the_title()); ?>"
            >
            <?php
        endif;
        ?>
        <article>
            <h2>Primary Address</h2>
            <p>
                <?php echo esc_html(get_post_meta($post->ID, 'restaurant_street_1', true)); ?>
                <?php echo esc_html(get_post_meta($post->ID, 'restaurant_street_2', true)); ?>,
                <?php echo esc_html(get_post_meta($post->ID, 'restaurant_city', true)); ?>,
                <?php echo esc_html(get_post_meta($post->ID, 'restaurant_state', true)); ?>
            </p>
        </article>
        <a href="tel:<?php echo esc_attr(get_post_meta($post->ID, 'restaurant_phone_primary', true)); ?>">
            <?php echo esc_attr(get_post_meta($post->ID, 'restaurant_phone_primary', true)); ?>
        </a>
        <div class="price-rating">
            <?php
            $price_rating = get_post_meta($post->ID, 'restaurant_pricing', true);
            if (!empty($price_rating)) {
                for ($i = 0; $i < $price_rating; $i++): ?>
                    <div class="price-rating-active">$</div>
                <?php endfor;

                for ($i = 0; $i < (4 - $price_rating); $i++): ?>
                    <div class="price-rating-inactive">$</div>
                <?php endfor;
            }
            ?>
        </div>
    </section>
    <form id="tb-book-reservation" class="reservation-form">
        <script>const tbUserReservationNonce = '<?php echo esc_js(wp_create_nonce('wp_rest')); ?>';</script>

        <h2 class="title">Book Reservation</h2>

        <label class="label-general" for="reservation-name">
            Reservation Name
            <input type="text" name="reservation-name" id="reservation-name" />
        </label>

        <label class="label-general" for="reservation-id">
            Reservation Date
            <select id="reservation-id" name="reservation-id">
                <?php
                $request  = new WP_REST_Request( 'GET', "/tb/v1/reservations/{$post->ID}" );
                $response = rest_do_request( $request );
                $server   = rest_get_server();
                $data     = $server->response_to_data( $response, false );
                ?>
                <?php if ( is_array( $data ) && count( $data ) > 0 ): ?>
                    <?php foreach( $data as $reservation ): ?>
                        <?php
                        // Formatting of the reservation date.
                        $formatted_datetime = date_format(
                            date_create($reservation->reservation_time),
                            'F jS, Y, g:i A'
                        );
                        ?>
                        <option value="<?php echo esc_attr($reservation->ID); ?>">
                            <?php echo esc_html($formatted_datetime); ?>
                            (<?php echo esc_html($reservation->reservation_party_size); ?> People)
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled selected>No Available Reservations</option>
                <?php endif; ?>
            </select>
        </label>
        
        <button id="tb-reservation-update" type="submit">Book It</button>
    </form>
</main>
<?php get_footer(); ?>