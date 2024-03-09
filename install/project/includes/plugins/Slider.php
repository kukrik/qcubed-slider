<?php

/**
 * The Slider override file. This file gets installed into project/includes/plugins duing the initial installation
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
	public function  __construct($objParentObject, $strControlId = null) {
		parent::__construct($objParentObject, $strControlId);
		$this->registerFiles();
	}

	/**
	 * @throws Caller
	 */

	protected function registerFiles() {
		$this->AddJavascriptFile(QCUBED_SLIDER_ASSETS_URL . "/js/jquery.bxslider.js");
		$this->addCssFile(QCUBED_SLIDER_ASSETS_URL . "/css/jquery.bxslider.css");
		$this->AddCssFile(QCUBED_BOOTSTRAP_CSS); // make sure they know
		$this->AddCssFile(QCUBED_FONT_AWESOME_CSS); // make sure they know
	}
}