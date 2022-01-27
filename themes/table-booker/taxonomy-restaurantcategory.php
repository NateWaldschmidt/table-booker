<?php
add_action('wp_head', function() { ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/taxonomy-restaurantcategory.css?v=<?php echo filemtime(get_template_directory().'/assets/css/taxonomy-restaurantcategory.css'); ?>">
<?php });
get_header();
?>

<?php $term = get_queried_object();  ?>

<h1><?php echo esc_html($term->name); ?></h1>

<?php // Looks for restaurants with this category. ?>
<?php if (have_posts()): ?>
    <ul>
        <?php while(have_posts()): the_post(); ?>
            <li>
                <a href="<?php echo esc_url(get_permalink()); ?>">
                    <?php echo esc_html(get_the_title()); ?>
                </a></li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Unfortunately, there are no <?php echo esc_html($term->name); ?> restaurants.</p>
<?php endif; ?>

<?php get_footer(); ?>