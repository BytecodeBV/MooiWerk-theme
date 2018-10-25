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
        jQuery(() => {
            jQuery('[data-toggle="tooltip"]').tooltip();
        });

        // submit newsletter
        jQuery(document).ready(() => {
            jQuery('#subscribe').on('click', e => {
                e.preventDefault();
                const email = jQuery('.newsletter__input').val();
                const emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
                if (emailReg.test(email)) {
                    jQuery.ajax({
                        url: site_data.ajax_url,
                        type: 'post',
                        data: {
                            action: 'subscribe',
                            email,
                        },
                        success: response => {
                            if (response.status) {
                                jQuery('.newsletter__wrapper .message').html(`
                                Subscribed
                                `);
                            }
                        },
                    });
                }
            });
        });
    },
};
