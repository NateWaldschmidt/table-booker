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
        /** @var { NodeList } fieldsets A nodelist consisting of fieldsets. */
        const fieldsets = document.querySelectorAll('#ss-all-filters > fieldset');

        // Loops the different fieldsets and stores the checkboxes in the object.
        SearchFilter.filterCBs = new Map();
        fieldsets.forEach((fs) => {
            let type = fs.getAttribute('data-filter-type');

            if (type != '') {
                SearchFilter.filterCBs.set(type, fs.querySelectorAll('[type=checkbox]'));

                // Adds the event listeners.
                SearchFilter.filterCBs.get(type).forEach((cb) => {
                    cb.addEventListener('input', () => {
                        SearchFilter.filter();
                    });
                });
            }
        });

        console.log(SearchFilter.filterCBs);

        this.searchResults = document.querySelectorAll('#site-search-results > li');
    },

    /**
     * 
     * 
     * @memberof  SearchFilter
     * @namespace SearchFilter.filter
     */
    filter: function() {
        const filterOut = new Set();
        const keep = new Set();

        // (1) Little dense here but essentially loops all fieldsets and then their nested checkboxes.
        // (2) It checks each checkbox if it is checked.
        // (3) If it is checked then it checks for the attributes determined by the fieldset's key in the map.
        // (4) If the attribute matches, it needs to be shown. If not then it needs to be hidden.
        // (5) This works on the OR logic, if it is a restaurant or it is a pizza category it will show.
        this.filterCBs.forEach((fieldset, key) => {
            fieldset.forEach(cb => {
                if (cb.checked) {
                    this.searchResults.forEach(res => {
                        if (res.getAttribute(`data-${key}`) != cb.value) {
                            filterOut.add(res);
                        } else {
                            keep.add(res);
                        }
                    });
                }
            });
        });

        // Checks if there is anything to filter out.
        if (filterOut.size == 0){
            this.searchResults.forEach(res => {
                res.hidden = false;
            });
        } else {
            filterOut.forEach(res => {
                res.hidden = true;
            });

            keep.forEach(res => {
                res.hidden = false;
            });
        }
    }
}

export default SearchFilter;