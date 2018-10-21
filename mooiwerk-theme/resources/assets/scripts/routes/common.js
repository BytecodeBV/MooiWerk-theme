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
                const btn = jQuery('#subscribe');
                const msg = jQuery('.newsletter__wrapper .message');
                const input = jQuery('.newsletter__input');
                const label = btn.text();
                const email = input.val();
                const emailReg = /^([\w-.]+@([\w-]+\.)+[\w-]{2,6})?$/;
                msg.html('');
                if (email && emailReg.test(email)) {
                    btn.text('Aanvraag versturen...').attr('disabled', true);
                    jQuery.ajax({
                        url: site_data.ajax_url,
                        type: 'post',
                        data: {
                            action: 'subscribe',
                            email,
                        },
                        success: response => {
                            if (response.success) {
                                input.val('');
                                msg.html(`
                                Bedankt voor het abonneren op onze nieuwsbrief!
                                `);
                            }
                        },
                        error: () => {
                            msg.html(`
                            Wij konden uw aanvraag niet voltooien, probeer het opnieuw.
                            `);
                        },
                        complete: () => {
                            btn.text(label).attr('disabled', false);
                        },
                    });
                } else {
                    msg.html(`
                    Geef een geldig e-mailadres op
                    `);
                }
            });
        });
    },
};
