<?php
    add_action('wp_head', function() {
        ?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/restaurant.css?=<?php echo filemtime(get_template_directory().'/assets/css/restaurant.css'); ?>">
        <?php
    });
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
    <?php get_template_part('/template-parts/part-reservation-form'); ?>
</main>

<script>
    // Handles the submission of the form and shows a confirmation of the booking. This will not stay here for production.
    (function() {
        const submitButton = document.querySelector('.reservation-form > [type=submit]');
        submitButton.addEventListener('click', (e) => {
            e.preventDefault();

            // Runs first animation.
            let confirmationMessage = document.querySelector('.confirmation-message')
            confirmationMessage.hidden = false;

            // Shows message for 5 seconds.
            setTimeout(() => {
                // Stores and removes animation style.
                let animationStyle = window.getComputedStyle(confirmationMessage).animation;

                // Runs the hide animation.
                window.requestAnimationFrame(() => {
                    confirmationMessage.style.animation = undefined;
                    window.requestAnimationFrame(() => {
                        confirmationMessage.style.animation = '0.3s ease 0s 1 reverse none running slide-in';
                    });
                });

                // Hides from DOM and reverts styles back.
                setTimeout(() => {
                    confirmationMessage.hidden = true;
                    confirmationMessage.style.animation = animationStyle;
                }, 300);
            }, 5000);
        });
    })();
</script>
<?php get_footer(); ?>