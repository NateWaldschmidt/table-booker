<form action="/" method="get" role="search">
    <label for="site-search">Search</label>
    <input
    type        ="search"
    name        ="s"
    id          ="site-search"
    value       ="<?php the_search_query(); ?>"
    aria-label  ="website"
    autocomplete="on"
    />
    
    <button
    type      ="submit"
    aria-label="Submit Search"
    title     ="Submit Search"
    >
        Submit Search
    </button>
</form>