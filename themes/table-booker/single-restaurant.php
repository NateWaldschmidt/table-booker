<?php
    add_action('wp_head', function() {
        ?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/restaurant.css?=<?php echo filemtime(get_template_directory().'/assets/css/restaurant.css'); ?>">
        <?php
    });
    get_header();

    global $post;
?>
<section class="restaurant-info">
    <a class="btn-cancel" href="<?php echo bloginfo('url'); ?>">Cancel</a>
    <h1><?php echo esc_html(the_title()); ?></h1>
    <img class="restaurant-photo" src="<?php echo get_template_directory_uri(); ?>/assets/images/cafe.jpg" alt="Cafe Exterior.">
    <div class="restaurant-rating">
        <?php for($i = 0; $i < 5; $i++): ?>
            <svg width="37" height="35" viewBox="0 0 37 35" fill="black" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.621 2.01934C16.4064 -0.397884 19.8261 -0.397887 20.6115 2.01934L23.0621 9.56139C23.4133 10.6424 24.4207 11.3743 25.5574 11.3743H33.4876C36.0292 11.3743 37.0859 14.6267 35.0297 16.1206L28.6141 20.7818C27.6945 21.45 27.3097 22.6342 27.661 23.7152L30.1115 31.2573C30.8969 33.6745 28.1303 35.6846 26.0741 34.1906L19.6584 29.5294C18.7389 28.8613 17.4937 28.8613 16.5741 29.5294L10.1585 34.1906C8.10224 35.6846 5.33562 33.6745 6.12102 31.2573L8.57158 23.7152C8.92283 22.6342 8.53804 21.45 7.61847 20.7818L1.20282 16.1206C-0.853394 14.6267 0.20336 11.3743 2.74498 11.3743H10.6752C11.8118 11.3743 12.8192 10.6424 13.1704 9.56139L15.621 2.01934Z" />
            </svg>
        <?php endfor; ?>                                                                                                          
    </div>
    <div class="price-rating">
        <?php
            $price_rating = get_post_meta($post->ID, 'price-rating', true);
            if (!empty($price_rating)) {
                for ($i = 0; $i < $price_rating; $i++): ?>
                    <div class="price-rating-active">$</div>
                <?php endfor;

                for ($i = 0; $i < (4 - $price_rating); $i++): ?>
                    <div class="price-rating-inactive">$</div>
                <?php endfor;
            } else { ?>
                <div class="price-rating-inactive">$</div>
                <div class="price-rating-inactive">$</div>
                <div class="price-rating-inactive">$</div>
                <div class="price-rating-inactive">$</div>
            <?php }
        ?>
    </div>
</section>
<?php get_template_part('/template-parts/part-reservation-form'); ?>
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