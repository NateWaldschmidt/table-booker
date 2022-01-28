<?php
/* Template Name: Search Page */

get_header();
?>

<main>
    <?php get_search_form(); ?>
    <ul>
        <?php if (have_posts()): ?>
            <?php while(have_posts()): the_post(); ?>
                <li><?php esc_html(the_title()); ?></li>
            <?php endwhile; ?>
        <?php endif; ?>
    </ul>
</main>

<?php get_footer(); ?>