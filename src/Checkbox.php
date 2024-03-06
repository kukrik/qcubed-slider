<?php

namespace QCubed\Plugin;

require_once(dirname(dirname(__DIR__)) . '/i18n/i18n-lib.inc.php');
use QCubed\Application\t;

use QCubed as Q;
use QCubed\Css\TextAlignType;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\QString;
use QCubed\Type;
use QCubed\TagStyler;
use QCubed\ModelConnector\Param as QModelConnectorParam;
use QCubed\Html;
use QCubed\Bootstrap as Bs;

/**
 * Class Checkbox
 *
 * Outputs a bootstrap style checkbox, and also takes into account the client's desired theme.
 *
 * @property string $Text is used to display text that is displayed next to the checkbox.  The text is rendered as an html "Label For" the checkbox.
 * @property string $TextAlign specifies if "Text" should be displayed to the left or to the right of the checkbox.
 * @property boolean $Checked specifices whether or not hte checkbox is checked
 * @property boolean $HtmlEntities specifies whether the checkbox text will have to be run through htmlentities or not.
 * @property string $WrapperClass $WrapperClass only sets or returns the CSS class of this wrapped in a div.
 * @property string $WrapperStyle
 * @property string $InputClass $InputClass only sets or returns the CSS class of this input.
 *
 * @property-write boolean $Inline whether checkbox should be displayed inline or wrapped in a div
 * @package QCubed\Plugin
 */
class Checkbox extends Q\Project\Control\ControlBase
{
    /** @var string Tag for rendering the control */
    protected $strTag = 'input';

    // APPEARANCE
    /** @var string Text opposite to the checkbox */
    protected $strText = null;
    /** @var string the alignment of the string */
    protected $strTextAlign = TextAlignType::RIGHT;

    // BEHAVIOR
    /** @var bool Should the htmlentities function be run on the control's text (strText)? */
    protected $blnHtmlEntities = true;

    // MISC
    /** @var bool Determines whether the checkbox is checked? */
    protected $blnChecked = false;

    /**
     * @var  TagStyler for labels of checkboxes. If side-by-side labeling, the styles will be applied to a
     * span that wraps both the checkbox and the label.
     */
    protected $objLabelStyle;

    protected $blnInline = false;
    protected $blnWrapLabel = false;
    protected $strInputClass = null;
    protected $strWrapperClass = null;

    protected $strLabelAttributes;


    //////////
    // Methods
    //////////

    /**
     * Parses the Post Data submitted for the control and sets the values
     * according to the data submitted
     */
    public function parsePostData()
    {
        $val = $this->objForm->checkableControlValue($this->strControlId);
        if ($val !== null) {
            $this->blnChecked = Type::cast($val, Type::BOOLEAN);
        }
    }

    /**
     * Returns the HTML code for the control which can be sent to the client.
     *
     * Note, previous version wrapped this in a div and made the control a block level control unnecessarily. To
     * achieve a block control, set blnUseWrapper and blnIsBlockElement.
     *
     * @return string THe HTML for the control
     */
    protected function getControlHtml()
    {
        $attrOverride = array('type' => 'checkbox', 'name' => $this->strControlId, 'value' => 'true');
        return $this->renderButton($attrOverride);
    }

    /**
     * Render the button code. Broken out to allow QRadioButton to use it too.
     *
     * @param $attrOverride
     * @return string
     */
    protected function renderButton($attrOverride)
    {
        if ($this->blnChecked) {
            $attrOverride['checked'] = 'checked';
        }

        if ($this->strInputClass) {
            $attrOverride['class'] = $this->strInputClass;
        }

        $strText = ($this->blnHtmlEntities) ? QString::htmlEntities($this->strText) : $this->strText;

        if (strlen($this->strText)) {
            $this->strLabelAttributes = ' for="' . $this->strControlId . '"';
        }

        $strCheckHtml = Html::renderLabeledInput(
            $strText,
            $this->strTextAlign == Html::TEXT_ALIGN_LEFT,
            $this->renderHtmlAttributes($attrOverride),
            $this->strLabelAttributes,
            $this->blnWrapLabel
        );
        $strCheckHtml = Html::renderTag('div', $this->renderLabelAttributes(), $strCheckHtml);

        return $strCheckHtml;
    }

    /**
     * Return a styler to style the label that surrounds the control if the control has text.
     * @return TagStyler
     */
    public function getCheckLabelStyler()
    {
        if (!$this->objLabelStyle) {
            $this->objLabelStyle = new TagStyler();
        }
        return $this->objLabelStyle;
    }

    /**
     * There is a little bit of a conundrum here. If there is text assigned to the checkbox, we wrap
     * the checkbox in a label. However, in this situation, its unclear what to do with the class and style
     * attributes that are for the checkbox. We are going to let the developer use the label styler to make
     * it clear what their intentions are.
     * @return string
     */
    protected function renderLabelAttributes()
    {
        $objStyler = new TagStyler();
        $attributes = $this->getHtmlAttributes(null, null, ['title']); // copy tooltip to wrapping label
        $objStyler->setAttributes($attributes);
        $objStyler->override($this->getCheckLabelStyler());

        if ($this->WrapperClass) {
            $objStyler->addCssClass($this->WrapperClass);
        }
        if (!$this->Enabled) {
            $objStyler->addCssClass('disabled');    // add the disabled class to the label for styling
        }
        if (!$this->Display) {
            $objStyler->Display = false;
        }
        if ($this->Inline) {
            $objStyler->addCssClass(Bs\Bootstrap::CHECKBOX_INLINE);
        }
        return $objStyler->renderHtmlAttributes();
    }

    /**
     * Checks whether the post data submitted for the control is valid or not
     * Right now it tests whether or not the control was marked as required and then tests whether it
     * was checked or not
     * @return bool
     */
    public function validate()
    {
        if ($this->blnRequired) {
            if (!$this->blnChecked) {
                if ($this->strName) {
                    $this->ValidationError = t($this->strName) . ' ' . t('is required');
                } else {
                    $this->ValidationError = t('Required');
                }
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the current state of the control to be able to restore it later.
     */
    public function getState()
    {
        return array('checked' => $this->Checked);
    }

    /**
     * Restore the  state of the control.
     *
     * @param mixed $state
     */
    public function putState($state)
    {
        if (isset($state['checked'])) {
            $this->Checked = $state['checked'];
        }
    }

    /////////////////////////
    // Public Properties: GET
    /////////////////////////
    /**
     * PHP __get magic method implementation
     * @param string $strName Name of the property
     *
     * @return mixed
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            // APPEARANCE
            case "Text": return $this->strText;
            case "TextAlign": return $this->strTextAlign;
            case "WrapperClass": return $this->strWrapperClass;
            case "InputClass": return $this->strInputClass;
            case "Inline": return $this->blnInline;
            case "HtmlEntities": return $this->blnHtmlEntities;
            case "Checked": return $this->blnChecked;
            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    /////////////////////////
    // Public Properties: SET
    /////////////////////////
    /**
     * PHP __set magic method implementation
     * @param string $strName
     * @param string $mixValue
     *
     * @return void
     * @throws InvalidCast|Caller
     */
    public function __set($strName, $mixValue)
    {
        switch ($strName) {
            case "Text":
                try {
                    $val = Type::cast($mixValue, Type::STRING);
                    if ($val !== $this->strText) {
                        $this->strText = $val;
                        $this->blnModified = true;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "TextAlign":
                try {
                    $val = Type::cast($mixValue, Type::STRING);
                    if ($val !== $this->strTextAlign) {
                        $this->strTextAlign = $val;
                        $this->blnModified = true;
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "HtmlEntities":
                try {
                    $this->blnHtmlEntities = Type::cast($mixValue, Type::BOOLEAN);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Checked":
                try {
                    $val = Type::cast($mixValue, Type::BOOLEAN);
                    if ($val != $this->blnChecked) {
                        $this->blnChecked = $val;
                        $this->addAttributeScript('prop', 'checked', $val);
                    }
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "Inline":
                try {
                    $this->blnInline = Type::cast($mixValue, Type::BOOLEAN);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "WrapperClass":
                try {
                    $this->strWrapperClass = Type::cast($mixValue, Type::STRING);
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
            case "InputClass":
                try {
                    $this->strInputClass = Type::cast($mixValue, Type::STRING);
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
     * Returns an description of the options available to modify by the designer for the code generator.
     *
     * @return QModelConnectorParam[]
     */
    public static function getModelConnectorParams()
    {
        return array_merge(parent::getModelConnectorParams(), array(
            new QModelConnectorParam (get_called_class(), 'Text', 'Label on checkbox', Type::STRING),
            new QModelConnectorParam (get_called_class(), 'TextAlign', 'Left or right alignment of label',
                QModelConnectorParam::SELECTION_LIST,
                array(
                    '\\QCubed\\Css\\TextAlignType::RIGHT' => 'Right',
                    '\\QCubed\\Css\\TextAlignType::LEFT' => 'Left'
                )),
            new QModelConnectorParam (get_called_class(), 'HtmlEntities', 'Whether to apply HTML entities on the label',
                Type::BOOLEAN),
            new QModelConnectorParam (get_called_class(), 'CssClass',
                'The css class(es) to apply to the checkbox and label together', Type::STRING)
        ));
    }

}