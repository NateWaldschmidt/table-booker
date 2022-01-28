<?php
add_action('wp_head', function() {
    ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/404.css?v=<?php echo filemtime(get_template_directory().'/assets/css/404.css'); ?>">
    <?php
});

get_header();
?>

<h1>Uh oh... I couldn't find that.</h1>

<?php get_footer(); ?>