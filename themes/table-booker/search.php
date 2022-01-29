<?php
/* Template Name: Search Page */

add_action('wp_head', function() { ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/search.css?v=<?php echo filemtime(get_template_directory().'/assets/css/search.css'); ?>">
<?php });

// The script to filter results.
wp_enqueue_script(
    'search-filter',
    get_template_directory_uri().'/assets/js/search-filter.js',
    array(),
    '1.0.0',
    filemtime(get_template_directory().'/assets/js/search-filter.js')
);

get_header();
?>

<main>
    <?php get_search_form(); ?>
    <ul id="site-search-results">
        <?php if (have_posts()): ?>
            <?php while(have_posts()): the_post(); ?>
                <li data-post-type="<?php echo esc_attr(get_post_type()); ?>">
                    <a href="<?php esc_url(the_permalink()); ?>">
                        <?php esc_html(the_title()); ?>
                    </a>
                    <p>
                        <?php echo esc_html(get_post_meta(get_the_id(), 'restaurant_description', true)); ?>
                    </p>
                </li>
            <?php endwhile; ?>
        <?php endif; ?>
    </ul>
</main>

<?php get_footer(); ?>