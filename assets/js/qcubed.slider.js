(function ($) {
    /**
     * jQuery plugin to initialize and configure an admin slider.
     * Sets up slider functionality with options specific to the admin view.
     *
     * This method is chainable and allows for the addition of slider-related
     * properties and events tailored for administrative settings.
     *
     * @function
     * @name $.fn.sliderSetupAdmin
     * @returns {jQuery} The jQuery object, allowing for method chaining.
     */
    $.fn.sliderSetupAdmin = function (options) {
        options = $.extend({
            rootUrl: null,
            tempUrl: null,
            selectedGroup: null,
            sliderOptions: null
        }, options)

        let sliderInstance = null;

        /**
         * Renders a slider using the provided slide data.
         * Removes existing slides and initializes the slider with new content.
         *
         * @param {Array} slideData - Array containing slide objects. Each slide object may include the following properties:
         * @param {string} [slideData[].url] - The URL the slide links to (if any).
         * @param {string} [slideData[].title] - The title or alt text for the slide.
         * @param {string} [slideData[].path] - The file path of the slide image.
         * @param {string} [slideData[].extension] - The file extension of the image (e.g., 'svg', 'jpg', 'png', etc.).
         * @param {number|string} [slideData[].width] - The width of the slide in pixels.
         * @param {number|string} [slideData[].height] - The height of the slide in pixels.
         * @param {number|string} [slideData[].top] - The margin-top value for the slide.
         *
         * @return {void} - Returns nothing, creates and initializes the slider in the DOM.
         */
        function renderSlider(slideData) {
            const sliderList = $('.bxslider');

            sliderList.empty();

            for (const slide of slideData) {
                if (options.selectedGroup && slide.groupId !== options.selectedGroup) continue;

                const url = slide.url || '';
                const title = slide.title || '';
                const path = slide.path || '';
                const extension = slide.extension || '';
                const width = slide.width || '';
                const height = slide.height || '';
                const top = slide.top || '';

                let style = '';
                if (width) style += `width:${width}px;`;
                if (height) style += `height:${height}px;`;
                if (top) style += `margin-top:${top}px;`;

                let imageHtml = '';

                if (extension === 'svg') {
                    imageHtml += `<div class="svg-container"${style ? ' style="' + style + '"' : ''}>`;
                    if (url) imageHtml += `<a href="${url}" target="_blank">`;
                    imageHtml += `<img src="${options.rootUrl}${path}" alt="${title}" title="${title}" />`;
                    if (url) imageHtml += `</a>`;
                    imageHtml += `</div>`;
                } else {
                    if (url) imageHtml += `<a href="${url}" target="_blank">`;
                    imageHtml += `<img src="${options.tempUrl}${path}" alt="${title}" title="${title}"${style ? ' style="' + style + '"' : ''} />`;
                    if (url) imageHtml += `</a>`;
                }

                sliderList.append(`<div>${imageHtml}</div>`);
            }

            if (sliderInstance) {
                sliderInstance.destroySlider();
            }
            sliderInstance = sliderList.bxSlider(options.sliderOptions);
        }

        /**
         * Fetches slider data from a JSON endpoint and renders the slider on the page.
         * The function uses a delayed call to simulate an asynchronous operation.
         *
         * @return {void} This function does not return a value. It performs the asynchronous operation to fetch data and passes it to the renderSlider function.
         */
        function fetchAndRenderSlider() {
            setTimeout(function() {
                $.getJSON('../assets/php/slider_json.php', function(data) {
                    renderSlider(data);
                }, 100);
            })
        }

        $('.js-refresh-slider').on('click change sortstop', function() {
            fetchAndRenderSlider();
        });

        $(function(){
            fetchAndRenderSlider();
        });

        return this;
    }
})(jQuery);

