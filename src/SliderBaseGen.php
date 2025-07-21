<?php

namespace QCubed\Plugin;

use QCubed as Q;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\Type;

/**
 * Class SliderBaseGen
 *
 * @see SliderBase
 * @package QCubed\Plugin
 */

/**
 * ### GENERAL ###
 *
 * @property string $Mode Default: 'horizontal'. Mode Type of transition between slides
 *                  Options: 'horizontal', 'vertical', 'fade'
 * @property integer $Speed Default: 500. Slide transition duration (in ms)
 * @property integer $SlideMargin Default: 0. Margin between each slide
 * @property integer $StartSlide Default: 0. Starting slide index (zero-based)
 * @property boolean $RandomStart Default: false. Start slider on a random slide
 * @property string $SlideSelector Default: ''. Element to use as slides (ex. 'div.slide').
 *                  Note: by default, bxSlider will use all immediate children of the slider element.
 *                  Options: jQuery selector
 * @property boolean $InfiniteLoop Default: true. If true, clicking "Next" while on the last slide will transition
 *                  to the first slide and vice versa
 * @property boolean $HideControlOnEnd Default: false. If true, "Prev" and "Next" controls will receive a class
 *                  disabled when slide is the first or the last.
 *                  Note: Only used when infiniteLoop: false
 * @property string $Easing Default: null. The type of "easing" to use during transitions. If using CSS transitions,
 *                  include a value for the transition-timing-function property. If not using CSS transitions,
 *                  you may include plugins/jquery.easing.1.3.js for many options.
 *                  See http://gsgd.co.uk/sandbox/jquery/easing/</a> for more info.
 *                  Options: if using CSS: 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'cubic-bezier(n,n,n,n)'.
 *                  If not using CSS: 'swing', 'linear' (see the above file for more options)
 * @property boolean $Captions Default: false. Include image captions. Captions are derived from the image's title attribute
 * @property boolean $Ticker Default: false. Use slider in ticker mode (similar to a news ticker).
 * @property boolean $TickerHover Default: false. Ticker will pause when the mouse hovers over the slider.
 *                  Note: this functionality does NOT work if using CSS transitions!
 * @property boolean $AdaptiveHeight Default: false. Dynamically adjust slider height based on each slide's height.
 * @property integer $AdaptiveHeightSpeed Default: 500. Slide height transition duration (in ms).
 *                  Note: only used if adaptiveHeight: true.
 * @property boolean $Video Default: false. If any slides contain video, set this to true.
 *                  Also, include plugins/jquery.fitvids.js
 *                  See http://fitvidsjs.com/</a> for more info.
 * @property boolean $Responsive Default: true. Enable or disable auto resize of the slider.
 *                  Useful if you need to use fixed width sliders.
 * @property boolean $UseCSS If true, Default: true.  CSS transitions will be used for horizontal and vertical slide
 *                  animations (this uses native hardware acceleration). If false, jQuery animate() will be used.
 * @property string $PreloadImages Default: 'visible'. If 'all', preloads all images before starting the slider.
 *                  If 'visible', preloads only images in the initially visible slides before starting the slider
 *                  (tip: use 'visible' if all slides are identical dimensions).
 *                  Options: 'all', 'visible'.
 * @property boolean $TouchEnabled Default: true. If true, slider will allow touch swipe transitions.
 * @property integer $SwipeThreshold Default: 50. Number of pixels a touch swipe needs to exceed in order to execute
 *                  a slide transition. Note: only used if touchEnabled: true.
 * @property boolean $OneToOneTouch Default: true. If true, non-fade slides follow the finger as it swipes.
 * @property boolean $PreventDefaultSwipeX Default: true. If true, the touch screen will not move along the x-axis as the finger swipes.
 * @property string $WrapperClass Default: 'bx-wrapper'. Class to wrap the slider in. Change to prevent
 *                  from using default bxSlider styles.
 *
 * ### PAGER ###
 *
 * @property boolean $Pager Default: true. If true, a pager will be added.
 * @property string $PagerType Default: 'full'. If 'full', a pager link will be generated for each slide.
 *                  If 'short', an x / y pager will be used (ex. 1 / 5).
 *                  Options: 'full', 'short'.
 * @property string $PagerShortSeparator Default:'/ '. If pagerType: 'short', pager will use this value as the separating character
 * @property string $PagerSelector Default: ''. Element used to populate the pager.
 *                  By default, the pager is appended to the bx-viewport.
 *                  Options: jQuery selector
 * @property string $PagerCustom Default: null.  Parent element to be used as the pager. Parent element must contain
 *                  a <a data-slide-index="x"> element for each slide. See an example here. Not for use with dynamic carousels.
 *                  Options: jQuery selector
 * @property object $BuildPager Default: null.  If supplied, a function is called on every slide element, and they returned
 *                  value is used as the pager item markup. See examples (https://bxslider.com/examples) for detailed implementation
 *                  Options: function(slideIndex).
 *
 * ### CONTROLS ###
 *
 * @property boolean $Controls Default: true. If true, "Next" / "Prev" controls will be added.
 * @property string $NextText Default: 'Next'. Text to be used for the "Next" control.
 * @property string $PrevText Default: 'Prev'. Text to be used for the "Prev" control.
 * @property string $NextSelector Default: null. Element used to populate the "Next" control.
 *                  Options: jQuery selector
 * @property string $PrevSelector Default: null. Element used to populate the "Prev" control.
 *                  Options: jQuery selector
 * @property boolean $AutoControls Default: false. If true, "Start" / "Stop" controls will be added.
 * @property string $StartText Default: 'Start'. Text to be used for the "Start" control.
 * @property string $StopText Default: 'Stop'. Text to be used for the "Stop" control.
 * @property boolean $AutoControlsCombine Default: false. When the slideshow is playing, only the "Stop" control is displayed and vice versa
 * @property string $AutoControlsSelector Default: null. Element used to populate the auto controls
 *                  Options: jQuery selector
 * @property boolean $KeyboardEnabled Default: false. Enable keyboard navigation for visible sliders.
 *
 * ### AUTO ###
 *
 * @property boolean $Auto Default: false. Slides will automatically transition.
 * @property boolean $StopAutoOnClick Default: false. Auto will stop on interaction with controls.
 * @property integer $Pause Default: 4000. The amount of time (in ms) between each auto transition.
 * @property boolean $AutoStart Default: true. Auto show starts playing on a load. If false,
 *                  slideshow will start when the "Start" control is clicked.
 * @property string $AutoDirection Default: 'next'. The direction of auto shows slide transitions.
 *                  Options: 'next', 'prev'
 * @property boolean $AutoHover Default: false. Auto show will pause when the mouse hovers over the slider.
 * @property integer $AutoDelay Default: 0. Time (in ms) auto show should wait before starting.
 *
 * ### CAROUSEL ###
 *
 * @property integer $MinSlides Default: 1. The minimum number of slides to be shown. Slides will be sized down
 *                  if the carousel becomes smaller than the original size.
 * @property integer $MaxSlides Default: 1. The maximum number of slides to be shown. Slides will be sized up
 *                  if the carousel becomes larger than the original size.
 * @property integer $MoveSlides Default: 0. The number of slides to move on transition.
 *                  This value must be >= minSlides, and <= maxSlides. If zero (default), the number of fully visible
 *                  slides will be used.
 * @property integer $SlideWidth Default: 0. The width of each slide. This setting is required for all horizontal carousels!
 * @property boolean $ShrinkItems Default: false. The Carousel will only show whole items and shrink the images
 *                  to fit the viewport based on maxSlides/MinSlides.
 *
 * ### ACCESSIBILITY ###
 *
 * @property boolean $AriaHidden Default: true. Adds Aria Hidden attribute to any nonvisible slides.
 *
 * ### CALLBACKS ###
 *
 * Suggestion to use other QCubed-4 options like Application::executeJavaScript() etc...
 *
 * @see https://bxslider.com/
 * @package QCubed\Plugin
 */

class SliderBaseGen extends Q\Control\Panel
{
    /** @var null|string */
    protected ?string $strMode = null;
    /** @var null|integer */
    protected ?int $intSpeed = null;
    /** @var null|integer */
    protected ?int $intSlideMargin = null;
    /** @var null|integer */
    protected ?int $intStartSlide = null;
    /** @var boolean */
    protected ?bool $blnRandomStart = null;
    /** @var null|string */
    protected ?string $strSlideSelector = null;
    /** @var boolean */
    protected ?bool $blnInfiniteLoop = null;
    /** @var boolean */
    protected ?bool $blnHideControlOnEnd = null;
    /** @var null|string */
    protected ?string $strEasing = null;
    /** @var boolean */
    protected ?bool $blnCaptions = null;
    /** @var boolean */
    protected ?bool $blnTicker = null;
    /** @var boolean */
    protected ?bool $blnTickerHover = null;
    /** @var boolean */
    protected ?bool $blnAdaptiveHeight = null;
    /** @var null|integer */
    protected ?int $intAdaptiveHeightSpeed = null;
    /** @var boolean */
    protected ?bool $blnVideo = null;
    /** @var boolean */
    protected ?bool $blnResponsive = null;
    /** @var null|string */
    protected ?string $strPreloadImages = null;
    /** @var boolean */
    protected ?bool $blnTouchEnabled = null;
    /** @var null|integer */
    protected ?int $intSwipeThreshold = null;
    /** @var boolean */
    protected ?bool $blnPreventDefaultSwipeX = null;
    /** @var null|string */
    protected ?string $strWrapperClass = null;

    /** @var boolean */
    protected ?bool $blnPager = null;
    /** @var null|string */
    protected ?string $strPagerType = null;
    /** @var null|string */
    protected ?string $strPagerShortSeparator = null;
    /** @var null|string */
    protected ?string $strPagerSelector = null;
    /** @var null|string */
    protected ?string $strPagerCustom = null;
    /** @var null|object */
    protected ?object $objBuildPager = null;

    /** @var boolean */
    protected ?bool $blnControls = null;
    /** @var null|string */
    protected ?string $strNextText = null;
    /** @var null|string */
    protected ?string $strPrevText = null;
    /** @var null|string */
    protected ?string $strNextSelector = null;
    /** @var null|string */
    protected ?string $strPrevSelector = null;
    /** @var boolean */
    protected ?bool $blnAutoControls = null;
    /** @var null|string */
    protected ?string $strStartText = null;
    /** @var null|string */
    protected ?string $strStopText = null;
    /** @var boolean */
    protected ?bool $blnAutoControlsCombine = null;
    /** @var null|string */
    protected ?string $str = null;
    /** @var null|string */
    protected ?string $strAutoControlsSelector = null;
    /** @var boolean */
    protected ?bool $blnKeyboardEnabled = null;

    /** @var boolean */
    protected ?bool $blnAuto = null;
    /** @var boolean */
    protected ?bool $blnStopAutoOnClick = null;
    /** @var null|integer */
    protected ?int $intPause = null;
    /** @var boolean */
    protected ?bool $blnAutoStart = null;
    /** @var null|string */
    protected ?string $strAutoDirection = null;
    /** @var boolean */
    protected ?bool $blnAutoHover = null;
    /** @var null|integer */
    protected ?int $intAutoDelay = null;

    /** @var null|integer */
    protected ?int $intMinSlides = null;
    /** @var null|integer */
    protected ?int $intMaxSlides = null;
    /** @var null|integer */
    protected ?int $intMoveSlides = null;
    /** @var null|integer */
    protected ?int $intSlideWidth = null;
    /** @var boolean */
    protected ?bool $blnShrinkItems = null;

    /** @var boolean */
    protected ?bool $blnAriaHidden = null;

    /**
     * Generates an associative array of jQuery plugin options based on the instance's properties.
     * Each property is checked for nullability, and if it has a value, it is added to the option array.
     * Extends the parent class's functionality by including additional configurable parameters specific to this implementation.
     *
     * @return array An associative array representing the jQuery plugin options and their corresponding values.
     */
    protected function makeJqOptions(): array
    {
        $jqOptions = parent::MakeJqOptions();
        if (!is_null($val = $this->Mode)) {$jqOptions['mode'] = $val;}
        if (!is_null($val = $this->Speed)) {$jqOptions['speed'] = $val;}
        if (!is_null($val = $this->SlideMargin)) {$jqOptions['slideMargin'] = $val;}
        if (!is_null($val = $this->StartSlide)) {$jqOptions['startSlide'] = $val;}
        if (!is_null($val = $this->RandomStart)) {$jqOptions['randomStart'] = $val;}
        if (!is_null($val = $this->SlideSelector)) {$jqOptions['slideSelector'] = $val;}
        if (!is_null($val = $this->InfiniteLoop)) {$jqOptions['infiniteLoop'] = $val;}
        if (!is_null($val = $this->HideControlOnEnd)) {$jqOptions['hideControlOnEnd'] = $val;}
        if (!is_null($val = $this->Easing)) {$jqOptions['easing'] = $val;}
        if (!is_null($val = $this->Captions)) {$jqOptions['captions'] = $val;}
        if (!is_null($val = $this->Ticker)) {$jqOptions['ticker'] = $val;}
        if (!is_null($val = $this->TickerHover)) {$jqOptions['tickerHover'] = $val;}
        if (!is_null($val = $this->AdaptiveHeight)) {$jqOptions['adaptiveHeight'] = $val;}
        if (!is_null($val = $this->AdaptiveHeightSpeed)) {$jqOptions['adaptiveHeightSpeed'] = $val;}
        if (!is_null($val = $this->Video)) {$jqOptions['video'] = $val;}
        if (!is_null($val = $this->Responsive)) {$jqOptions['responsive'] = $val;}
        if (!is_null($val = $this->PreloadImages)) {$jqOptions['preloadImages'] = $val;}
        if (!is_null($val = $this->TouchEnabled)) {$jqOptions['touchEnabled'] = $val;}
        if (!is_null($val = $this->SwipeThreshold)) {$jqOptions['swipeThreshold'] = $val;}
        if (!is_null($val = $this->PreventDefaultSwipeX)) {$jqOptions['preventDefaultSwipeX'] = $val;}
        if (!is_null($val = $this->WrapperClass)) {$jqOptions['wrapperClass'] = $val;}

        if (!is_null($val = $this->Pager)) {$jqOptions['pager'] = $val;}
        if (!is_null($val = $this->PagerType)) {$jqOptions['pagerType'] = $val;}
        if (!is_null($val = $this->PagerShortSeparator)) {$jqOptions['pagerShortSeparator'] = $val;}
        if (!is_null($val = $this->PagerSelector)) {$jqOptions['pagerSelector'] = $val;}
        if (!is_null($val = $this->PagerCustom)) {$jqOptions['pagerCustom'] = $val;}
        if (!is_null($val = $this->BuildPager)) {$jqOptions['buildPager'] = $val;}

        if (!is_null($val = $this->Controls)) {$jqOptions['controls'] = $val;}
        if (!is_null($val = $this->NextText)) {$jqOptions['nextText'] = $val;}
        if (!is_null($val = $this->PrevText)) {$jqOptions['prevText'] = $val;}
        if (!is_null($val = $this->NextSelector)) {$jqOptions['nextSelector'] = $val;}
        if (!is_null($val = $this->PrevSelector)) {$jqOptions['prevSelector'] = $val;}
        if (!is_null($val = $this->AutoControls)) {$jqOptions['autoControls'] = $val;}
        if (!is_null($val = $this->StartText)) {$jqOptions['startText'] = $val;}
        if (!is_null($val = $this->StopText)) {$jqOptions['stopText'] = $val;}
        if (!is_null($val = $this->AutoControlsCombine)) {$jqOptions['autoControlsCombine'] = $val;}
        if (!is_null($val = $this->AutoControlsSelector)) {$jqOptions['autoControlsSelector'] = $val;}
        if (!is_null($val = $this->KeyboardEnabled)) {$jqOptions['keyboardEnabled'] = $val;}

        if (!is_null($val = $this->Auto)) {$jqOptions['auto'] = $val;}
        if (!is_null($val = $this->StopAutoOnClick)) {$jqOptions['stopAutoOnClick'] = $val;}
        if (!is_null($val = $this->Pause)) {$jqOptions['pause'] = $val;}
        if (!is_null($val = $this->AutoStart)) {$jqOptions['autoStart'] = $val;}
        if (!is_null($val = $this->AutoDirection)) {$jqOptions['autoDirection'] = $val;}
        if (!is_null($val = $this->AutoHover)) {$jqOptions['autoHover'] = $val;}
        if (!is_null($val = $this->AutoDelay)) {$jqOptions['autoDelay'] = $val;}

        if (!is_null($val = $this->MinSlides)) {$jqOptions['minSlides'] = $val;}
        if (!is_null($val = $this->MaxSlides)) {$jqOptions['maxSlides'] = $val;}
        if (!is_null($val = $this->MoveSlides)) {$jqOptions['moveSlides'] = $val;}
        if (!is_null($val = $this->SlideWidth)) {$jqOptions['slideWidth'] = $val;}
        if (!is_null($val = $this->ShrinkItems)) {$jqOptions['shrinkItems'] = $val;}

        if (!is_null($val = $this->AriaHidden)) {$jqOptions['ariaHidden'] = $val;}
        return $jqOptions;
    }

    /**
     * Retrieves the name of the jQuery setup function associated with the component.
     * This function specifies the jQuery plugin to be initialized for the component.
     *
     * @return string The name of the jQuery setup function.
     */
    public function getJqSetupFunction(): string
    {
        return 'bxSlider';
    }


    /**
     * Magic method to retrieve the value of a property dynamically by its name.
     * Maps property names to their corresponding internal variables within the class.
     * For unknown properties, it attempts to call the parent::__get method
     * and handles exceptions by incrementing the offset of the exception before re-throwing it.
     *
     * @param string $strName The name of the property being accessed.
     *
     * @return mixed The value of the specified property, or the corresponding parent property value if handled by the parent class.
     * @throws Caller If the property is not defined in the current or parent class.
     */
    public function __get(string $strName): mixed
    {
        switch ($strName) {
            case 'Mode': return $this->strMode;
            case 'Speed': return $this->intSpeed;
            case 'SlideMargin': return $this->intSlideMargin;
            case 'StartSlide': return $this->intStartSlide;
            case 'RandomStart': return $this->blnRandomStart;
            case 'SlideSelector': return $this->strSlideSelector;
            case 'InfiniteLoop': return $this->blnInfiniteLoop;
            case 'HideControlOnEnd': return $this->blnHideControlOnEnd;
            case 'Easing': return $this->strEasing;
            case 'Captions': return $this->blnCaptions;
            case 'Ticker': return $this->blnTicker;
            case 'TickerHover': return $this->blnTickerHover;
            case 'AdaptiveHeight': return $this->blnAdaptiveHeight;
            case 'AdaptiveHeightSpeed': return $this->intAdaptiveHeightSpeed;
            case 'Video': return $this->blnVideo;
            case 'Responsive': return $this->blnResponsive;
            case 'PreloadImages': return $this->strPreloadImages;
            case 'TouchEnabled': return $this->blnTouchEnabled;
            case 'SwipeThreshold': return $this->intSwipeThreshold;
            case 'PreventDefaultSwipeX': return $this->blnPreventDefaultSwipeX;
            case 'WrapperClass': return $this->strWrapperClass;

            case 'Pager': return $this->blnPager;
            case 'PagerType': return $this->strPagerType;
            case 'PagerShortSeparator': return $this->strPagerShortSeparator;
            case 'PagerSelector': return $this->strPagerSelector;
            case 'PagerCustom': return $this->strPagerCustom;
            case 'BuildPager': return $this->objBuildPager;

            case 'Controls': return $this->blnControls;
            case 'NextText': return $this->strNextText;
            case 'PrevText': return $this->strPrevText;
            case 'NextSelector': return $this->strNextSelector;
            case 'PrevSelector': return $this->strPrevSelector;
            case 'AutoControls': return $this->blnAutoControls;
            case 'StartText': return $this->strStartText;
            case 'StopText': return $this->strStopText;
            case 'AutoControlsCombine': return $this->blnAutoControlsCombine;
            case 'AutoControlsSelector': return $this->strAutoControlsSelector;
            case 'KeyboardEnabled': return $this->blnKeyboardEnabled;

            case 'Auto': return $this->blnAuto;
            case 'StopAutoOnClick': return $this->blnStopAutoOnClick;
            case 'Pause': return $this->intPause;
            case 'AutoStart': return $this->blnAutoStart;
            case 'AutoDirection': return $this->strAutoDirection;
            case 'AutoHover': return $this->blnAutoHover;
            case 'AutoDelay': return $this->intAutoDelay;

            case 'MinSlides': return $this->intMinSlides;
            case 'MaxSlides': return $this->intMaxSlides;
            case 'MoveSlides': return $this->intMoveSlides;
            case 'SlideWidth': return $this->intSlideWidth;
            case 'ShrinkItems': return $this->blnShrinkItems;

            case 'AriaHidden': return $this->blnAriaHidden;

            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * Handles dynamic property assignment and updates the corresponding jQuery option via attribute scripts.
     * Validates and casts the provided value for a specific property, throwing an exception for invalid input types.
     *
     * @param string $strName The name of the property to set.
     * @param mixed $mixValue The value to assign to the specified property. The value is validated and cast to an appropriate type.
     *
     * @return void
     *
     * @throws Caller
     * @throws InvalidCast If the provided value cannot be appropriately cast to the expected type.
     */
    public function __set(string $strName, mixed $mixValue): void
    {
        switch ($strName) {
            case 'Mode':
                try {
                    $this->strMode = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'mode', $this->strMode);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Speed':
                try {
                    $this->intSpeed = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'speed', $this->intSpeed);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'SlideMargin':
                try {
                    $this->intSlideMargin = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'slideMargin', $this->intSlideMargin);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'StartSlide':
                try {
                    $this->intStartSlide = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'startSlide', $this->intStartSlide);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'RandomStart':
                try {
                    $this->blnRandomStart = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'randomStart', $this->blnRandomStart);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'SlideSelector':
                try {
                    $this->strSlideSelector = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'slideSelector', $this->strSlideSelector);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'InfiniteLoop':
                try {
                    $this->blnInfiniteLoop = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'infiniteLoop', $this->blnInfiniteLoop);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'HideControlOnEnd':
                try {
                    $this->blnHideControlOnEnd = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'hideControlOnEnd', $this->blnHideControlOnEnd);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Easing':
                try {
                    $this->strEasing = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'easing', $this->strEasing);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Captions':
                try {
                    $this->blnCaptions = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'captions', $this->blnCaptions);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Ticker':
                try {
                    $this->blnTicker = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'ticker', $this->blnTicker);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'TickerHover':
                try {
                    $this->blnTickerHover = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'tickerHover', $this->blnTickerHover);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AdaptiveHeight':
                try {
                    $this->blnAdaptiveHeight = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'adaptiveHeight', $this->blnAdaptiveHeight);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AdaptiveHeightSpeed':
                try {
                    $this->intAdaptiveHeightSpeed = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'adaptiveHeightSpeed', $this->intAdaptiveHeightSpeed);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Video':
                try {
                    $this->blnVideo = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'video', $this->blnVideo);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Responsive':
                try {
                    $this->blnResponsive = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'responsive', $this->blnResponsive);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PreloadImages':
                try {
                    $this->strPreloadImages = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'preloadImages', $this->strPreloadImages);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'TouchEnabled':
                try {
                    $this->blnTouchEnabled = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'touchEnabled', $this->blnTouchEnabled);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'SwipeThreshold':
                try {
                    $this->intSwipeThreshold = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'swipeThreshold', $this->intSwipeThreshold);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PreventDefaultSwipeX':
                try {
                    $this->blnPreventDefaultSwipeX = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'preventDefaultSwipeX', $this->blnPreventDefaultSwipeX);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'WrapperClass':
                try {
                    $this->strWrapperClass = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'wrapperClass', $this->strWrapperClass);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Pager':
                try {
                    $this->blnPager = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'pager', $this->blnPager);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PagerType':
                try {
                    $this->strPagerType = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'pagerType', $this->strPagerType);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PagerShortSeparator':
                try {
                    $this->strPagerShortSeparator = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'pagerShortSeparator', $this->strPagerShortSeparator);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PagerSelector':
                try {
                    $this->strPagerSelector = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'pagerSelector', $this->strPagerSelector);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PagerCustom':
                try {
                    $this->strPagerCustom = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'pagerCustom', $this->strPagerCustom);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'BuildPager':
                try {
                    $this->objBuildPager = Type::Cast($mixValue, Type::OBJECT);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'buildPager', $this->objBuildPager);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Controls':
                try {
                    $this->blnControls = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'controls', $this->blnControls);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'NextText':
                try {
                    $this->strNextText = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'nextText', $this->strNextText);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PrevText':
                try {
                    $this->strPrevText = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'prevText', $this->strPrevText);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'NextSelector':
                try {
                    $this->strNextSelector = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'nextSelector', $this->strNextSelector);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'PrevSelector':
                try {
                    $this->strPrevSelector = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'prevSelector', $this->strPrevSelector);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoControls':
                try {
                    $this->blnAutoControls = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoControls', $this->blnAutoControls);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'StartText':
                try {
                    $this->strStartText = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'startText', $this->strStartText);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'StopText':
                try {
                    $this->strStopText = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'stopText', $this->strStopText);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoControlsCombine':
                try {
                    $this->blnAutoControlsCombine = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoControlsCombine', $this->blnAutoControlsCombine);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoControlsSelector':
                try {
                    $this->strAutoControlsSelector = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoControlsSelector', $this->strAutoControlsSelector);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'KeyboardEnabled':
                try {
                    $this->blnKeyboardEnabled = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'keyboardEnabled', $this->blnKeyboardEnabled);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'Auto':
                try {
                    $this->blnAuto = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'auto', $this->blnAuto);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'StopAutoOnClick':
                try {
                    $this->blnStopAutoOnClick = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'stopAutoOnClick', $this->blnStopAutoOnClick);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'Pause':
                try {
                    $this->intPause = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'pause', $this->intPause);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoStart':
                try {
                    $this->blnAutoStart = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoStart', $this->blnAutoStart);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoDirection':
                try {
                    $this->strAutoDirection = Type::Cast($mixValue, Type::STRING);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoDirection', $this->strAutoDirection);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoHover':
                try {
                    $this->blnAutoHover = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoHover', $this->blnAutoHover);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'AutoDelay':
                try {
                    $this->intAutoDelay = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'autoDelay', $this->intAutoDelay);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'MinSlides':
                try {
                    $this->intMinSlides = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'minSlides', $this->intMinSlides);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'MaxSlides':
                try {
                    $this->intMaxSlides = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'maxSlides', $this->intMaxSlides);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'MoveSlides':
                try {
                    $this->intMoveSlides = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'moveSlides', $this->intMoveSlides);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'SlideWidth':
                try {
                    $this->intSlideWidth = Type::Cast($mixValue, Type::INTEGER);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'slideWidth', $this->intSlideWidth);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case 'ShrinkItems':
                try {
                    $this->blnShrinkItems = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'shrinkItems', $this->blnShrinkItems);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            case 'AriaHidden':
                try {
                    $this->blnAriaHidden = Type::Cast($mixValue, Type::BOOLEAN);
                    $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'ariaHidden', $this->blnAriaHidden);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    parent::__set($strName, $mixValue);
                    break;
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * Retrieves an array of model connector parameters by merging the parent class's parameters
     * with additional parameters specific to the current implementation.
     *
     * @return array An associative array containing the merged model connector parameters.
     */
    public static function getModelConnectorParams(): array
    {
        return array_merge(parent::GetModelConnectorParams(), array());
    }
}


