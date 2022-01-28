<?php
add_action('wp_head', function() { ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/taxonomy-restaurantcategory.css?v=<?php echo filemtime(get_template_directory().'/assets/css/taxonomy-restaurantcategory.css'); ?>">
<?php });
get_header();
?>

<?php $term = get_queried_object();  ?>

<main>
    <h1><?php echo esc_html($term->name); ?></h1>

    <?php // Looks for restaurants with this category. ?>
    <?php if (have_posts()): ?>
        <ul class="tb-category-restaurants">
            <?php while(have_posts()): the_post(); ?>
                <li>
                    <article class="tb-restaurant">
                        <a class="tb-restaurant-title" href="<?php echo esc_url(get_permalink()); ?>">
                            <?php echo esc_html(get_the_title()); ?>
                        </a>
                        <p class="tb-restaurant-description">
                            <?php echo esc_html(get_post_meta(get_the_ID(), 'restaurant_description', true)); ?>
                        </p>
                    </article>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Unfortunately, there are no <?php echo esc_html($term->name); ?> restaurants.</p>
    <?php endif; ?>
</main>



<?php get_footer(); ?>