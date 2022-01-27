<?php
/** Template Name: Restaurant Categories */

get_header();

$terms = get_terms( array(
    'taxonomy' => 'restaurantcategory',
    'hide_empty' => false,
) );

// Checks if the taxonomy exists.
if (is_object($terms) && is_a('WP_Error', get_class($terms))) {
    ?>
    <h1>There are no restauarant categories.</h1>
    <?php
} else {
    ?>
    <ul>
        <?php foreach($terms as $term): ?>
            <li>
                <article>
                    <a href="<?php echo esc_url(bloginfo('url').'/'.$term->taxonomy.'/'.$term->slug);?> ">
                        <?php echo esc_html($term->name); ?>
                    </a>
                    <p><?php echo esc_html($term->description); ?></p>
                </article>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
}

get_footer();