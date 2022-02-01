'use strict';
import SearchFilter from './search-filter';

test('The that the selector for the checkboxes is not an empty NodeList.', () => {
    document.body.innerHTML = `
        <form id="form-site-search">
            <fieldset>
                <input type="checkbox" value="restaurant" />
            </fieldset>
        </form>

        <ul id="site-search-results">
            <li data-post-type="restaurant"></li>
            <li data-post-type="page"></li>
            <li data-post-type="restaurant"></li>
            <li data-post-type="post"></li>
        </ul>
    `;

    SearchFilter.init();

    expect(SearchFilter.filterCBs.length).not.toBe(0);
});

test('The that the selector for the checkboxes is an empty NodeList.', () => {
    document.body.innerHTML = `
        <form id="form-site-search">
            <fieldset>
            </fieldset>
        </form>

        <ul id="site-search-results">
            <li data-post-type="restaurant"></li>
            <li data-post-type="page"></li>
            <li data-post-type="restaurant"></li>
            <li data-post-type="post"></li>
        </ul>
    `;

    SearchFilter.init();

    expect(SearchFilter.filterCBs.length).toBe(0);
});