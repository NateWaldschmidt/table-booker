<?php
// THIS IS AN EXAMPLE, NOT INTENDED FOR DEVELOPMENT YET.

/**
 * @link   https://developer.wordpress.org/plugins/security/
 * @link   https://developer.wordpress.org/plugins/shortcodes/
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 * @param  Array  $atts    The array of attributes passed in from the user's shortcode.
 * @param  String $content The string of content put between an opening and closing shortcode tag.
 * @param  String $tag     The shortcode's name.
 */
function tb_new_reservation_form($atts = [], $content = null, $tag) {
    // Changes all values and to lower case.
    $atts = array_change_key_case($atts, CASE_LOWER);

    // Sets default attributes that are overridden by user attributes.
    $tb_atts = shortcode_atts(
        [
            'heading_level' => 1,
            'title'         => 'Create a New Reservation.',
            'submit_title'  => 'Submit Reservation Request',
        ],
        $atts,
        $tag
    );

    // Validates the input of the heading level.
    if (!is_int($tb_atts['heading_level']) || $tb_atts['heading_level'] <= 0 || $tb_atts['heading_level'] > 6) {
        $tb_atts['heading_level'] = 1;
    }

    // Formats as a true heading tag.
    $tb_atts['heading_level'] = "h{$tb_atts['heading_level']}";

    ob_start(); ?>
        <form>
            <?php // The heading for the form. ?>
            <<?php echo esc_html($tb_atts['heading_level']); ?> class="tb-heading">
                <?php echo esc_html($tb_atts['title']); ?>
            </<?php echo esc_html($tb_atts['heading_level']); ?>>

            <?php // The paragraph describing the form if it was supplied. ?>
            <?php if (!is_null($content)): ?>
                <p class="tb-description">
                    <?php echo esc_html($content); ?>
                </p>
            <?php endif; ?>

            <input id="tb-reservation-name" name="tb-reservation-name" type="text">

            <button id="tb-res-submit" class="tb-btn-submit" name="tb-res-submit" type="submit">
                <?php echo esc_html($tb_atts['submit_title']); ?>
            </button>

            <script>
                // NOT FUNCTIONING FOR REAL.
                document.getElementById('tb-res-submit').closest('form').addEventListener('submit', (e) => {
                    e.preventDefault();
                    
                    const fd = new FormData(e.target.closest('form'));
                    const xhr = new XMLHttpRequest();

                    xhr.addEventListener('load', (e) => {
                        alert(e.target.responseText);
                    });

                    xhr.open('post', `<?= esc_url(bloginfo('url')); ?>/wp-json/tb/v1/reservation/${document.getElementById('tb-reservation-name').value}`);
                    xhr.send(fd);
                });
            </script>
        </form>
    <?php // Always return.
    return ob_get_clean();
}

// This should be within the shortcode init method in the tb class for production.
add_shortcode('tb-new-reservation-form', 'tb_new_reservation_form');

/**
 * Registers and handles an endpoint for processing reservation requests.
 * 
 * @link   https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 * @author Nathaniel Waldschmidt
 */
function tb_handle_submission(WP_REST_Request $request) {
    $param = $request['id'];

    $response = new WP_REST_Response($param);
    $response->set_status(201);

    return $response;
}
add_action('rest_api_init', function() {
    register_rest_route( 'tb/v1', '/reservation/(?P<id>\d+)', [
        'methods'  => 'post',
        'callback' => 'tb_handle_submission'
    ]);
});