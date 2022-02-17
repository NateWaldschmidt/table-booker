<?php
    add_action('wp_head', function() { ?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/index.css?v=<?php echo filemtime(get_template_directory().'/assets/css/index.css'); ?>">
    <?php });
    get_header();
?>

<img src="<?php echo get_template_directory_uri(); ?>/assets/images/landing.jpg" alt="Warm restaurant booth seating.">
<main>
    <form id="form-site-search" action="/" method="get" role="search">
        <label for="site-search">
            Search
            <input
            type        ="search"
            id          ="site-search"
            name        ="s"
            value       ="<?php the_search_query(); ?>"
            aria-label  ="website"
            autocomplete="on"
            />
        </label>

        <button
        type      ="submit"
        class     ="btn"
        aria-label="Submit Search"
        title     ="Submit Search"
        >
            Submit Search
        </button>
    </form>

    <section id="available-reservations">
        <h1>Available Reservations.</h1>
        <?php
        global $wpdb;
        // Reservation table name.
        $res_table = $wpdb->prefix.'tb_reservations';

        // Posts table name.
        $posts_table = $wpdb->prefix.'posts';

        // Queries for the data.
        $data = $wpdb->get_results(
            "SELECT
                GROUP_CONCAT({$res_table}.reservation_time) AS reservation_times,
                {$posts_table}.post_title AS  restaurant_name,
                {$posts_table}.guid as URL
            FROM {$res_table}
            INNER JOIN {$posts_table}
            ON {$res_table}.restaurant_id = {$posts_table}.ID
            WHERE reservation_status = 0
            GROUP BY restaurant_name;"
        );
        ?>
        <?php if ( is_array( $data ) && count( $data ) > 0 ): ?>
            <?php foreach( $data as $reservation ): ?>
                <?php
                // Converts the dates into an array from string.
                $reservation->reservation_times = preg_split("/\,/", $reservation->reservation_times);
                ?>
                <article class="restaurant-container">
                    <div class="restaurant-title-container">
                        <span>
                            <?php echo esc_html($reservation->restaurant_name); ?>
                        </span>
                    </div>
                    <ul class="available-times">
                        <?php foreach($reservation->reservation_times as $date): ?>
                            <li>
                                <a href="<?php echo esc_attr($reservation->URL); ?>" datetime="<?php echo esc_attr($date); ?>">
                                    <?php echo esc_html(date("F j g:i a", strtotime($date))); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <option disabled selected>No Available Reservations</option>
        <?php endif; ?>
    </section>

    <section class="restaurant-categories">
        <h2>Find Your New Favorite.</h2>
        <ul>
            <?php
            $terms = get_terms( array(
                'taxonomy' => 'restaurantcategory',
                'hide_empty' => false,
            ) );
            ?>
            <?php if (is_object($terms) && is_a('WP_Error', get_class($terms))): ?>
                <li>
                    <a href="/">Unable to Find Restaurant Categories</a>
                </li>
            <?php else: ?>
                <?php foreach($terms as $term): ?>
                    <li>
                        <a href="<?php echo esc_url(bloginfo('url').'/'.$term->taxonomy.'/'.$term->slug);?> ">
                            <?php echo esc_html($term->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </section>
</main>

<?php get_footer(); ?>