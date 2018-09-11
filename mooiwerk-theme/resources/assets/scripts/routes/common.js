/* global site_data */
export default {
    init() {
        // JavaScript to be fired on all pages
        document
            .getElementById('search_input')
            .addEventListener('keypress', event => {
                const clickedEl = event.target;
                if (event.keyCode === 13) {
                    const keyword = clickedEl.value.replace(' ', '+');
                    window.location = `${site_data.home}?s=${keyword}`;
                }
            });
    },
    finalize() {
        // JavaScript to be fired on all pages, after page specific JS is fired
    },
};
