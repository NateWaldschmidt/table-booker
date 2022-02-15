<?php
    add_action('wp_head', function() { ?>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/index.css?v=<?php echo filemtime(get_template_directory().'/assets/css/index.css'); ?>">
    <?php });
    get_header();
?>

<img src="<?php echo get_template_directory_uri(); ?>/assets/images/landing.jpg" alt="Warm restaurant booth seating.">
<main>
    <form id="reservation-form" action="">
        <label for="reservation-date">
            Reservation Date
            <input id="reservation-date" type="date" value="2022-01-06" />
        </label>

        <label for="reservation-time">
            Reservation Time
            <input id="reservation-time" type="time" list="available-time-list" />
            <datalist id="available-time-list">
                <option value="18:30:00">6:30:00</option>
            </datalist>
        </label>

        <label for="reservation-party-size">
            Party Size
            <input id="reservation-party-size" type="number" value="3">
        </label>

        <label for="restaurant-name">
            Restaurant
            <input id="restaurant-name" type="text" list="available-restaurant-list" placeholder="Enter a Restaurant Name.">
            <datalist id="available-restaurant-list">
                <option value="Burger Local">445 W. 1st Street, Chicago, IL</option>
                <option value="Cafe Bilhares">85 3rd Street, Chicago, IL</option>
                <option value="Olive Garden">826 State Street, Chicago, IL</option>
            </datalist>
        </label>

        <button type="submit">
            Setup Reservation
        </button>
    </form>

    <section id="available-reservations">
        <h1>Available Reservations for 3 People.</h1>
        <article class="restaurant-container">
            <div class="restaurant-title-container">
                <span>Burger Local</span>
                <span>445 W. 1st Street, Chicago, IL</span>
            </div>
            <ul class="available-times">
                <li><a href=""><time datetime="2022-01-05 18:00">6:00 PM</time></a></li>
                <li><a href=""><time datetime="2022-01-05 18:30">6:30 PM</time></a></li>
                <li><a href=""><time datetime="2022-01-05 19:00">7:00 PM</time></a></li>
                <li><a href=""><time datetime="2022-01-05 19:30">7:30 PM</time></a></li>
                <li><a href=""><time datetime="2022-01-05 20:00">8:00 PM</time></a></li>
                <li><a href=""><time datetime="2022-01-05 20:30">9:30 PM</time></a></li>
            </ul>
        </article>
        <article class="restaurant-container">
            <div class="restaurant-title-container">
                <span>Cafe Bilhares</span>
                <span>85 3rd Street, Chicago, IL</span>
            </div>
            <ul class="available-times">
                <li><a href=""><time datetime="2022-01-05 18:00">6:00 PM</time></a></li>
                <li><a href="/wordpress/reservation-maker"><time datetime="2022-01-05 18:30">6:30 PM</time></a></li>
                <li style="grid-column: 5 / 6;"><a href=""><time datetime="2022-01-05 20:00">8:00 PM</time></a></li>
            </ul>
        </article>
        <article class="restaurant-container">
            <div class="restaurant-title-container">
                <span>Olive Garden</span>
                <span>826 State Street, Chicago, IL</span>
            </div>
            <ul class="available-times">
                <li><a href=""><time datetime="2022-01-05 18:00">6:00 PM</time></a></li>
                <li style="grid-column: 3 / 4;"><a href=""><time datetime="2022-01-05 19:00">7:00 PM</time></a></li>
                <li style="grid-column: 4 / 5;"><a href=""><time datetime="2022-01-05 20:00">8:00 PM</time></a></li>
                <li style="grid-column: 6 / 7;"><a href=""><time datetime="2022-01-05 20:30">9:30 PM</time></a></li>
            </ul>
        </article>
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