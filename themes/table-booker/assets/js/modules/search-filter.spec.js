'use strict';
import SearchFilter from './search-filter';

test('Tests that the selector for the checkboxes is not an empty NodeList.', () => {
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

test('Tests that the selector for the checkboxes is an empty NodeList.', () => {
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

test('Tests the restaurant filter results in only 2 results.', () => {
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

    // Finds the checkbox, checks it, then fires an input event.
    const restaurantCB = document.querySelector('#form-site-search [type=checkbox]');
    restaurantCB.checked = true;
    restaurantCB.dispatchEvent(new Event('input'));

    // Gets the elements that were not filtered out.
    const nonFilteredElems = document.querySelectorAll('#site-search-results > :not([hidden])');

    expect(nonFilteredElems.length).toBe(2);
});

test('Tests the page filter results in only 1 result.', () => {
    document.body.innerHTML = `
        <form id="form-site-search">
            <fieldset>
                <input type="checkbox" value="page" />
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

    // Finds the checkbox, checks it, then fires an input event.
    const restaurantCB = document.querySelector('#form-site-search [type=checkbox]');
    restaurantCB.checked = true;
    restaurantCB.dispatchEvent(new Event('input'));

    // Gets the elements that were not filtered out.
    const nonFilteredElems = document.querySelectorAll('#site-search-results > :not([hidden])');

    expect(nonFilteredElems.length).toBe(1);
});