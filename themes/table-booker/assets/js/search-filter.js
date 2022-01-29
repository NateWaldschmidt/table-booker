/**
 * Filters the results of the search after they
 * have been displayed to the user.
 * 
 * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 */
(function() {
    /** @var { NodeList } filterCBs All of the checkboxes used to filter results. */
    const filterCBs = document.querySelectorAll('#form-site-search > fieldset [type=checkbox]');

    /** @var { NodeList } searchResults All of the <li> elements that resulted from search. */
    const searchResults = document.querySelectorAll('#site-search-results > li');

    // Adds the event listener for when the checkboxes are toggled.
    filterCBs?.forEach((cb) => {
        cb.addEventListener('input', () => {
            filterResults();
        });
    });

    /**
     * Gets a list of the toggled filters, then uses that
     * to go through the posts in search of the 
     * data-post-type attribute.  The ones that don't 
     * exist in the Set of filters are removed.
     * 
     * @author Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
     */
    function filterResults() {
        /** @var { Set } filters The toggled filters to check against.*/
        let filters = new Set();

        filterCBs?.forEach((cb) => {
            if (cb.checked == true) {
                filters.add(cb.value);
            }
        })

        searchResults.forEach((liElem) => {
            if (filters.size <= 0) {
                liElem.hidden = false;
            }
            if (filters.has(liElem.getAttribute('data-post-type'))) {
                liElem.hidden = false;
            } else {
                liElem.hidden = true;
            }
        })
    }
})();