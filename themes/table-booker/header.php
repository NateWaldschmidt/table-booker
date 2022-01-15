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
		<a id="home-logo-link" href="<?= esc_url(bloginfo('url')); ?>"><?= bloginfo('name'); ?></a>
		<!-- <button type="button">+</button> -->
	</div>
	<div class="account-container">
		<a id="header-login" href="<?= esc_url(bloginfo('url').'/login') ?>">Login</a>
		<a id="header-signup" href="<?= esc_url(bloginfo('url').'/sign-up') ?>">Sign Up</a>
	</div>
</header>

<div id="page" class="site">
	<div id="content" class="site-content">
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">