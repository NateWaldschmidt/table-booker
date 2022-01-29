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

    <fieldset>
        <legend>Filter</legend>
        <label for="ss-filt-restaurants">
            <input type="checkbox" name="filter-restaurants" id="ss-filt-restaurants" value="restaurant">
            Restaurants
        </label>

        <label for="ss-filt-pages">
            <input type="checkbox" name="filter-pages" id="ss-filt-pages" value="page">
            Pages
        </label>
    </fieldset>
</form>