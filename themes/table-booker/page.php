<?php
/* Template Name: General */
add_action('wp_head', function() {
    ?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/page.css?=<?php echo filemtime(get_template_directory().'/assets/css/page.css'); ?>">
    <?php
});
get_header();

if (have_posts()) {
    while(have_posts()) {
        the_post();
        the_content();
    }
}

get_footer();