<?php
/**
 * Provides a front-end form for users to create
 * new restaurants.  This is intended to be 
 * un-styled to allow for theme styling.
 * 
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 * @return string HTML to be used in the shortcode.
 */
function tb_new_restaurant_form():string {
    // Enqueue the Javascript.
    wp_enqueue_script('tb_new_restaurant');
    add_filter(
        'script_loader_tag',
        function($tag, $handle, $src) {
            if ($handle != 'tb_new_restaurant') {
                return $tag;
            }
            
            $tag = '<script type="module" src="'.esc_url($src).'"></script>';
            return $tag;
        },
        10,
        3
    );

    // Structure
    ob_start(); ?>
        <div class="popup-message" hidden>
            <p>Success!</p>
        </div>
        <form id="tb-form-new-restaurant" enctype="multipart/form-data">
            <?php // Nonce for REST API use. ?>
            <script>const tbRestaurantNonce = '<?php echo esc_js(wp_create_nonce('wp_rest')); ?>';</script>

            <fieldset>
                <legend>Primary Information</legend>

                <label class="tb-form-label" for="restaurant-title">Name</label>
                <input
                type="text"
                id="restaurant-title"
                class="tb-form-input"
                name="restaurant-title"
                />

                <label class="tb-form-label" for="restaurant-description">Description</label>
                <textarea
                id="restaurant-description"
                class="tb-form-ta"
                name="restaurant-description"
                cols="30"
                rows="10"
                ></textarea>

                <label class="tb-form-label" for="restaurant-pricing">Average Meal Price</label>
                <select
                id="restaurant-pricing"
                class="tb-form-select"
                name="restaurant-pricing"
                >
                    <option value="1">$0.00-$14.99</option>
                    <option value="2">$15.00-$29.99</option>
                    <option value="3">$30.00-$44.99</option>
                    <option value="4">$45.00-$+</option>
                </select>

                <label class="tb-form-label" for="restaurant-photo">Restaurant Photo</label>
                <input
                type="file"
                id="restaurant-photo"
                class="tb-form-input"
                name="restaurant-photo"
                />
            </fieldset>

            <fieldset>
                <legend>Location information</legend>

                <label class="tb-form-label" for="restaurant-street-1">Street Address</label>
                <input
                type="text"
                id="restaurant-street-1"
                class="tb-form-input"
                name="restaurant-street-1"
                />

                <label class="tb-form-label" for="restaurant-street-2">Additional Address Information</label>
                <input
                type="text"
                id="restaurant-street-2"
                class="tb-form-input"
                name="restaurant-street-2" 
                />

                <label class="tb-form-label" for="restaurant-city">City</label>
                <input class="tb-form-input"
                type="text"
                id="restaurant-city"
                name="restaurant-city"
                />

                <label class="tb-form-label" for="restaurant-zip">Zip</label>
                <input class="tb-form-input"
                type="text"
                id="restaurant-zip"
                name="restaurant-zip"
                />

                <label class="tb-form-label" for="restaurant-state">State</label>
                <input class="tb-form-input"
                type="text"
                id="restaurant-state"
                name="restaurant-state"
                />

                <label class="tb-form-label" for="restaurant-country">Country</label>
                <input class="tb-form-input"
                type="text"
                id="restaurant-country"
                name="restaurant-country"
                />
            </fieldset>

            <fieldset>
                <legend>Contact Information</legend>

                <label class="tb-form-label" for="restaurant-phone-primary">Main Phone Number</label>
                <input
                type="tel"
                id="restaurant-phone-primary"
                class="tb-form-input"
                name="restaurant-phone-primary"
                />

                <label class="tb-form-label" for="restaurant-website">Website</label>
                <input
                type="url"
                id="restaurant-website"
                class="tb-form-input"
                name="restaurant-website"
                />
            </fieldset>

            <button type="submit">Create Restaurant</button>
        </form>
    <?php return ob_get_clean();
}
add_shortcode('tb-new-restaurant-form', 'tb_new_restaurant_form');