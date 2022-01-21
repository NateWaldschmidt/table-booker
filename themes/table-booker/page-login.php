<?php
    /* Template Name: Login Page */
    add_action('wp_head', function() {
        ?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/login.css?=<?php echo filemtime(get_template_directory().'/assets/css/login.css'); ?>">
        <?php
    });
    get_header();
?>

<img src="<?php echo get_template_directory_uri(); ?>/assets/images/burger-plates.webp">

<h1>
    <div class="image-text-existing">Existing User?</div>
    <div class="image-text-welcome">Welcome Back.</div>
</h1>

<form id="login-form" action="">
    <h2 class="title">Login.</h2>

    <label for ="email-address">
        Email Address
        <input id = "email-addresss" type = "string"/>
    </label>

    <label for ="email-address">
        Password
        <input id = "email-addresss" type = "password"/>
    </label>

    <button type="submit" style="cursor: pointer">
        Login
    </button>

    <!-- Popup Confirmation Message -->
    <div class="confirmation-message" hidden>
        <p>Welcome Back!</p>
    </div>
</form>

<script>
    // Handles the submission of the form and shows a confirmation of the booking. This will not stay here for production.
    (function() {
        const submitButton = document.querySelector('#login-form > [type=submit]');
        submitButton.addEventListener('click', (e) => {
            e.preventDefault();

            // Runs first animation.
            let confirmationMessage = document.querySelector('.confirmation-message')
            confirmationMessage.hidden = false;

            // Shows message for 5 seconds.
            setTimeout(() => {
                // Stores and removes animation style.
                let animationStyle = window.getComputedStyle(confirmationMessage).animation;

                // Runs the hide animation.
                window.requestAnimationFrame(() => {
                    confirmationMessage.style.animation = undefined;
                    window.requestAnimationFrame(() => {
                        confirmationMessage.style.animation = '0.3s ease 0s 1 reverse none running slide-in';
                    });
                });

                // Hides from DOM and reverts styles back.
                setTimeout(() => {
                    confirmationMessage.hidden = true;
                    confirmationMessage.style.animation = animationStyle;
                }, 300);
            }, 5000);
        });
    })();
</script>

<?php get_footer(); ?>