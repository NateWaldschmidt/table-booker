<?php
    /* Template Name: Sign Up Page */
    add_action('wp_head', function() {
        ?><link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/sign-up.css?=<?php echo filemtime(get_template_directory().'/assets/css/reservation-form.css'); ?>">
        <?php
    });
    get_header();
?>

<img src="<?php echo get_template_directory_uri(); ?>/assets/images/fancy-restaurant.webp" style="width: 100%;">
<div class="image-text-existing">
    First Time?
</div>
<div class="image-text-welcome">
    Start Booking Today.
</div>

<form id="sign-up-form" action="">
    <h2 class="title">Create an Account.</h2>

    <label for ="full-name">
        Full Name
        <input id = "full-name" type = "string"/>
    </label>

    <label for ="email-address">
        Email Address
        <input id = "email-addresss" type = "string"/>
    </label>

    <label for ="email-address">
        Phone Number
        <input id = "email-addresss" type ="tel"/>
    </label>

    <label for ="password">
        Password
        <input id = "password" type = "password"/>
    </label>

    <label for ="password">
        Confirm Password
        <input id = "password" type = "password"/>
    </label>

    <button type="submit" style="cursor: pointer">
        Create Account
    </button>

    <!-- Popup Confirmation Message -->
    <div class="confirmation-message" hidden>
        <p>Thank you for Signing Up!</p>
    </div>
</form>

<script>
    // Handles the submission of the form and shows a confirmation of the booking. This will not stay here for production.
    (function() {
        const submitButton = document.querySelector('#sign-up-form > [type=submit]');
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