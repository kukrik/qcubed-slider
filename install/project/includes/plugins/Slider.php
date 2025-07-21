<?php

/**
 * The Slider override file. This file gets installed into project/includes/plugins during the initial installation
 * of the plugin. After that, it is not touched. Feel free to modify this file as needed.
 */

namespace QCubed\Plugin;

use QCubed\Exception\Caller;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase;

/**
 * SliderBase constructor
 *
 * @param ControlBase|FormBase|null $objParentObject
 * @param null|string $strControlId
 */

class Slider extends SliderBase
{
    /**
     * Constructor for initializing the object.
     *
     * @param ControlBase|FormBase $objParentObject The parent object that controls or contains this object.
     * @param string|null $strControlId Optional control ID. If null, a unique ID will be generated.
     *
     * @return void
     * @throws Caller
     */
    public function  __construct(ControlBase|FormBase $objParentObject, ?string $strControlId = null) {
        parent::__construct($objParentObject, $strControlId);
        $this->registerFiles();
    }

    /**
     * Registers JavaScript and CSS files required for the slider functionality.
     *
     * This method includes necessary JavaScript and CSS files for the slider,
     * Bootstrap, and Font Awesome to ensure proper styling and functionality.
     *
     * @return void
     * @throws Caller
     */
    protected function registerFiles(): void
    {
        $this->AddJavascriptFile(QCUBED_SLIDER_ASSETS_URL . "/js/jquery.bxslider.js");
        $this->addCssFile(QCUBED_SLIDER_ASSETS_URL . "/css/jquery.bxslider.css");
        $this->AddCssFile(QCUBED_BOOTSTRAP_CSS); // make sure they know
        $this->AddCssFile(QCUBED_FONT_AWESOME_CSS); // make sure they know
    }
}