<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header>
	<div class="header-menu">
		<a id="home-logo-link" href="<?php echo bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a>
		<!-- <button type="button">+</button> -->
	</div>
	<?php
	wp_nav_menu(
		array(
			'menu' => 'header-menu'
		)
	);
	?>
	<?php if ( is_user_logged_in() ): ?>
		<div class="account-container">
			<?php
			global $current_user;
			wp_get_current_user();
			?>
			<p id="header-login">
				<?php echo esc_html($current_user->display_name); ?>
			</p>
		</div>
	<?php else: ?>
		<div class="account-container">
			<a id="header-login" href="<?php echo esc_url(bloginfo('url').'/login') ?>">Login</a>
			<a id="header-signup" href="<?php echo esc_url(bloginfo('url').'/sign-up') ?>">Sign Up</a>
		</div>
	<?php endif; ?>
</header>

<div id="tb-content">