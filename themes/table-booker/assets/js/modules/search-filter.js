/**
 * @namespace SearchFilter
 * @author    Nathaniel Waldschmidt Nathaniel.Waldsch@gmail.com
 * @property  { NodeList } filterCBsAll of the checkboxes used to filter results.
 * @property  { NodeList } searchResults All of the <li> elements that resulted from search.
 */
const SearchFilter = {
    filterCBs: undefined,
    searchResults: undefined,
    init: function() {
        // Sets the properties.
        this.filterCBs = document.querySelectorAll('#form-site-search > fieldset [type=checkbox]');
        this.searchResults = document.querySelectorAll('#site-search-results > li');

        this.filterCBs?.forEach((cb) => {
            cb.addEventListener('input', () => {
                SearchFilter.filter();
            });
        });
    },

    /**
     * Gets a list of the toggled filters, then uses that
     * to go through the posts in search of the 
     * data-post-type attribute.  The ones that don't 
     * exist in the Set of filters are removed.
     * 
     * @memberof  SearchFilter
     * @namespace SearchFilter.filter
     */
    filter: function() {
        /** @var { Set } filters The toggled filters to check against.*/
        const filters = new Set();
    
        // Checks to see which checkboxes are toggled and need to be filtered.
        this.filterCBs?.forEach((cb) => {
            if (cb.checked == true) {
                filters.add(cb.value);
            }
        });
    
        // Go through the elements and hide elements based on the filters.
        this.searchResults.forEach((liElem) => {
            if (filters.size <= 0) {
                liElem.hidden = false;
            } else if (filters.has(liElem.getAttribute('data-post-type'))) {
                liElem.hidden = false;
            } else {
                liElem.hidden = true;
            }
        })
    }
}

export default SearchFilter;