jest.mock('./search-filter');

test('Test the SearchFilter.filter() call on the event of input.', () => {
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

    const SearchFilter = require('./search-filter');
    SearchFilter.init();

    SearchFilter.filter.mockImplementation();

    const cb = document.querySelector('[type=checkbox]');
    cb.click();

    expect(SearchFilter.filter).toBeCalled();
});