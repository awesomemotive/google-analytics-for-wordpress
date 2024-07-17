window.MonsterInsights_WooCommerce_Marketing = window.MonsterInsights_WooCommerce_Marketing || {};

(function (window, document, $, app) {
    'use strict';

    app.interval;

    /**
     * Add Analytics Box
     *
     * @since 2.1.0
     *
     * @returns {void}
     */
    app.insertAnalyticsBox = function () {
        // When the Marketing Hub was introduced in 4.1, the class
        // name used for their cards was ".woocommerce-card". Here
        // we'll check for that first and use it if found. Otherwise,
        // we'll use the current class name.
        var earlyCard = $('.woocommerce-card:nth-child(2)');
        var card = earlyCard.length ? earlyCard : $('.components-card:nth-child(2)');
        var newCard = $(document.getElementById('monsterinsights-wcm-components-card'));

        if ( card.length ) {
            console.log('showed');
            card.after( newCard.show() );
        }
    };

    app.initBox = function () {
        // If react app loaded.
        if ( $('.woocommerce-marketing-overview').length ||
            $('.woocommerce-marketing-overview-multichannel').length
        ) {
            if (app.interval) {
                clearInterval(app.interval);
            }
            app.insertAnalyticsBox();
        }
    };

    app.init = function () {
        // We have to wait for the Woo React app to finish before
        // we can insert our box, So we'll keep trying until we get
        // what we're looking for.
        app.interval = setInterval(() => app.initBox(), 1000);
        app.initBox();
    };

    $(app.init);
})(window, document, jQuery, window.MonsterInsights_WooCommerce_Marketing);
