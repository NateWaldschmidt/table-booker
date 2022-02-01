<form id="form-site-search" action="/" method="get" role="search">
    <label for="site-search">Search</label>
    <input
    type        ="search"
    id          ="site-search"
    name        ="s"
    value       ="<?php the_search_query(); ?>"
    aria-label  ="website"
    autocomplete="on"
    />

    <button
    type      ="submit"
    class     ="btn"
    aria-label="Submit Search"
    title     ="Submit Search"
    >
        Submit Search
    </button>

    <fieldset id="ss-all-filters">
        <legend>All Filters</legend>

        <fieldset data-filter-type="post-type">
            <legend>Result Type</legend>
            <label for="ss-filt-restaurants">
                <input type="checkbox" id="ss-filt-restaurants" value="restaurant">
                Restaurants
            </label>

            <label for="ss-filt-pages">
                <input type="checkbox" id="ss-filt-pages" value="page">
                Pages
            </label>
        </fieldset>

        <fieldset data-filter-type="price-rating">
            <legend>Average Restaurant Meal Price</legend>
            <label for="ss-filt-rest-price-1">
                <input type="checkbox" id="ss-filt-rest-price-1" value="1">
                $00.00 - $19.99
            </label>
            <label for="ss-filt-rest-price-2">
                <input type="checkbox" id="ss-filt-rest-price-2" value="2">
                $20.00 - $39.99
            </label>
            <label for="ss-filt-rest-price-3">
                <input type="checkbox" id="ss-filt-rest-price-3" value="3">
                $40.00 - $59.99
            </label>
            <label for="ss-filt-rest-price-4">
                <input type="checkbox" id="ss-filt-rest-price-4" value="4">
                $60.00+
            </label>
        </fieldset>

        <fieldset data-filter-type="restaurant-category">
            <legend>Restaurant Category</legend>
            <?php
            // Gets the restaurant category options.
            $rest_cats = get_terms(['taxonomy' => 'restaurantcategory']);

            // Adds the checkboxes for each of the restaurant categories.
            if (count($rest_cats) > 0) {
                foreach($rest_cats as $cat): ?>
                    <?php $id = esc_attr($cat->slug); ?>
                    <label for="filter-rest-cat-<?php echo $id; ?>">
                        <input
                        type="checkbox"
                        id="filter-rest-cat-<?php echo $id; ?>"
                        value="<?php echo esc_attr($cat->term_id); ?>"
                        />
                        <?php echo esc_html($cat->name); ?>
                    </label>
                <?php endforeach;
            }
            ?>
        </fieldset>
    </fieldset>
</form>