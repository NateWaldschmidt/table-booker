<?php
/* Template Name: Search Page */

// The script to filter results.
wp_enqueue_script(
    'search-filter',
    get_template_directory_uri().'/assets/js/search.js',
    array(),
    filemtime(get_template_directory().'/assets/js/search.js')
);
add_filter(
    'script_loader_tag',
    function($tag, $handle, $src) {
        if ($handle != 'search-filter') {
            return $tag;
        }
        
        $tag = '<script type="module" src="'.esc_url($src).'"></script>';
        return $tag;
    },
    10,
    3
);

add_action('wp_head', function() { ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/search.css?v=<?php echo filemtime(get_template_directory().'/assets/css/search.css'); ?>">
<?php });

get_header();
?>

<main>
    <?php get_search_form(); ?>
    <ul id="site-search-results">
        <?php if (have_posts()): ?>
            <?php while(have_posts()): the_post(); ?>
                <?php $post_type = get_post_type(); ?>
                
                <li
                data-post-type="<?php echo esc_attr($post_type); ?>"
                <?php if ($post_type == 'restaurant'): ?>
                    <?php
                    $price_rating = esc_attr(get_post_meta(get_the_id(), 'restaurant_pricing', true));
                    $category = get_the_terms(get_the_id(), 'restaurantcategory');
                    if ($category !== false) {
                        foreach($category as $index=>$cat) {
                            $category[$index] = $cat->term_id;
                        }
                    } else {
                        $category = array();
                    }
                    ?>
                    <?php if ($price_rating != '' || $price_rating != false): ?>
                        data-price-rating="<?php echo $price_rating; ?>"
                    <?php endif; ?>
                    <?php if (count($category) > 0): ?>
                        data-restaurant-category="<?php echo implode(',', $category); ?>"
                    <?php endif; ?>
                <?php endif; ?>
                >
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