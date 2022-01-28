<?php
/** Template Name: Restaurant Categories */

add_action('wp_head', function() { ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/restaurant-categories.css?v=<?php echo filemtime(get_template_directory().'/assets/css/restaurant-categories.css'); ?>">
<?php });

get_header();

$terms = get_terms( array(
    'taxonomy' => 'restaurantcategory',
    'hide_empty' => false,
) );
?>

<main>
    <?php // Checks if the taxonomy exists. ?>
    <?php if (is_object($terms) && is_a('WP_Error', get_class($terms))): ?>
        ?>
        <h1>There are no restauarant categories.</h1>
    <?php else: ?>
        <h1>Restaurant Categories</h1>
        <ul class="tb-category-list">
            <?php foreach($terms as $term): ?>
                <li>
                    <article class="tb-category">
                        <a href="<?php echo esc_url(bloginfo('url').'/'.$term->taxonomy.'/'.$term->slug);?> ">
                            <?php echo esc_html($term->name); ?>
                        </a>
                        <p><?php echo esc_html($term->description); ?></p>
                    </article>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>

<?php get_footer(); ?>