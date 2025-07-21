<?php

namespace QCubed\Plugin;


use QCubed\Control\ControlBase;
use QCubed\Control\FormBase;
use QCubed\ApplicationBase;
use QCubed\Exception\Caller;
use Exception;
use QCubed\Exception\InvalidCast;
use QCubed\Project\Application;
use QCubed\Type;

/**
 * Class SliderSetupAdmin
 *
 * @property integer $WidthInput Default 0. ....
 * @property integer $HeightInput Default 0. ....
 *
 * @package QCubed\Plugin
 */

class SliderSetupAdmin extends SliderSetupAdminGen
{
    protected ?int $intWidthInput = null;
    protected ?int $intHeightInput = null;

    /**
     * Initializes a new instance of the class by setting up the parent object, control ID,
     * and registering the necessary files.
     *
     * @param ControlBase|FormBase $objParentObject The parent object that this control belongs to.
     *                                              Must be an instance of ControlBase or FormBase.
     * @param string|null $strControlId Optional. The ID of the control.
     *                                  If null, an ID will be auto-generated.
     *
     * @return void
     * @throws Caller
     */
    public function  __construct(ControlBase|FormBase $objParentObject, ?string $strControlId = null) {
        parent::__construct($objParentObject, $strControlId);
        $this->registerFiles();
    }

    /**
     * Registers the necessary JavaScript and CSS files for functionality and styling.
     *
     * @return void
     * @throws Caller
     */
    protected function registerFiles(): void
    {
        $this->AddJavascriptFile(QCUBED_SLIDER_ASSETS_URL . "/js/jquery.bxslider.js");
        $this->AddJavascriptFile(QCUBED_SLIDER_ASSETS_URL . "/js/qcubed.slider.js");
        $this->addCssFile(QCUBED_SLIDER_ASSETS_URL . "/css/jquery.bxslider.css");
    }

    /**
     * Returns the HTML for the control.
     *
     * @return string
     * @throws Exception
     */
    protected function getControlHtml(): string
    {
        $strHtml = '';
        $strHtml .= _nl(_indent('<div class="slider-container">', 1));
        $strHtml .= _nl(_indent('<div class="bxslider"></div>', 2));
        $strHtml .= _nl(_indent('</div>', 1));

        return $strHtml;
    }

    /**
     * Generates and retrieves the JavaScript code to be executed at the end of the page load.
     * This method extends the parent's functionality by appending additional JavaScript
     * for handling specific control modifications and events.
     *
     * @return string The compiled JavaScript string, including any parent JavaScript and
     *                the added functionality for handling control modifications related
     *                to width and height inputs.
     * @throws Caller
     */
    public function getEndScript(): string
    {
        $strJS = parent::getEndScript();

        $strCtrlJs = <<<FUNC
            $('.js-update').on('click', function () {
                var widthInput = document.querySelector("#width");
                var heightInput = document.querySelector("#height");
                
                qcubed.recordControlModification("$this->ControlId", "_widthInput", widthInput.value);
                qcubed.recordControlModification("$this->ControlId", "_heightInput", heightInput.value);
            });
FUNC;
        Application::executeJavaScript($strCtrlJs, ApplicationBase::PRIORITY_HIGH);

        return $strJS;
    }

    /**
     * Magic method to retrieve the value of a property.
     * Provides access to specific properties or delegates to the parent if the property is not defined.
     *
     * @param string $strName The name of the property to retrieve.
     *
     * @return mixed The value of the requested property.
     * @throws Caller If the property does not exist or is inaccessible.
     */
    public function __get(string $strName): mixed
    {
        switch ($strName) {
            case 'WidthInput': return $this->intWidthInput;
            case 'HeightInput': return $this->intHeightInput;

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
     * Magic method to set the value of a property.
     *
     * @param string $strName The name of the property to set.
     * @param mixed $mixValue The value to set for the specified property.
     *
     * @return void
     * @throws InvalidCast Thrown when the value cannot be cast to the expected type.
     * @throws Caller Thrown when the parent::__set method encounters an error.
     */
    public function __set(string $strName, mixed $mixValue): void
    {
        switch ($strName) {
            case '_widthInput': // Internal only to output the desired width of the image when clicked
                try {
                    $this->intWidthInput = Type::cast($mixValue, Type::INTEGER);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case '_heightInput': // Internal only to output the desired height of the image when clicked
                try {
                    $this->intHeightInput = Type::cast($mixValue, Type::INTEGER);
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

}