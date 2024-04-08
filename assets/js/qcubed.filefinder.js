(function ($) {
    $.fn.fileFinder = function (options) {
        options = $.extend({
            url: null,
            targetPopupName: null,
            popupWidth: null,
            popupHeight: null,
        }, options)

        // Get a reference to the custom button or link

        const targetPopupName = options.targetPopupName;

        const customSelector = document.querySelectorAll('[data-popup="' + targetPopupName + '"]');

        for (let i = 0, len = customSelector.length; i < len; i++) {
            // Event management
            customSelector[i].addEventListener("click", launchPopup);
        }

        // The code below here uses the CKeditor 4 plugin popup: https://ckeditor.com/cke4/addon/powrpopup

        function launchPopup( url, width, height, settings ) {
            width = options.popupWidth || '95%';
            height = options.popupHeight || '95%';

            if ( typeof width == 'string' && width.length > 1 && width.substr( width.length - 1, 1 ) == '%' )
                width = parseInt( window.screen.width * parseInt( width, 10 ) / 100, 10 );

            if ( typeof height == 'string' && height.length > 1 && height.substr( height.length - 1, 1 ) == '%' )
                height = parseInt( window.screen.height * parseInt( height, 10 ) / 100, 10 );

            if ( width < 640 )
                width = 640;

            if ( height < 420 )
                height = 420;

            var top = parseInt( ( window.screen.height - height ) / 2, 10 ),
                left = parseInt( ( window.screen.width - width ) / 2, 10 );

            settings = ( settings || 'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes' ) + ',width=' + width +
                ',height=' + height +
                ',top=' + top +
                ',left=' + left;

            var popupWindow = window.open( '', null, settings, true );

            // Blocked by a popup blocker.
            if ( !popupWindow )
                return false;

            try {
                // Chrome is problematic with moveTo/resizeTo, but it's not really needed here (https://dev.ckeditor.com/ticket/8855).
                var ua = navigator.userAgent.toLowerCase();
                if ( ua.indexOf( ' chrome/' ) == -1 ) {
                    popupWindow.moveTo( left, top );
                    popupWindow.resizeTo( width, height );
                }
                popupWindow.focus();
                popupWindow.location.href = options.url;
            } catch ( e ) {
                popupWindow = window.open( options.url, null, settings, true );
            }

            return true;
        }

        return this;
    }
})(jQuery);