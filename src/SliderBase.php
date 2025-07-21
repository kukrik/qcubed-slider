<?php

namespace QCubed\Plugin;

use Exception;
use QCubed as Q;
use QCubed\Control\ControlBase;
use QCubed\Control\FormBase;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\Type;

/**
 * Class SliderBase
 *
 * @property string $TempUrl Default temp path APP_UPLOADS_TEMP_URL. If necessary, the temp dir must be specified.
 * @property string $RootUrl Default root path APP_UPLOADS_URL. If necessary, the temp dir must be specified.
 * @property integer $SliderStatus Default '2'. If the user decides to publish the slider on the frontend.
 *                                 it will be made visible, i.e., the number will be changed to 1.
 * @property string $ListTag Default: 'div'. Depending on the design of the theme, either use 'div' or 'ul'.
 * @property string $ItemTag Default: 'div'. Depending on the design of the theme, either use 'div' or 'li'.
 *                          If true, it is drawn like this:
 *                          'div' or 'li'
 *                          'a target="_blank" href="..."' 'img alt="..." title="..." src="image.jpg" /' '/a'
 *                          '/div' or '/li'
 *                          If false, it is drawn like this:
 *                          'div' o 'li'
 *                          'img alt="..." title="..." src="image.jpg" /'
 *                          '/div' or '/li'
 *
 * @property array $DataSource
 * @package QCubed\Plugin
 */

class SliderBase extends SliderBaseGen
{
    use Q\Control\DataBinderTrait;

    /** @var string */
    protected string $strRootUrl = APP_UPLOADS_URL;
    /** @var string  */
    protected string $strTempUrl = APP_UPLOADS_TEMP_URL;
    /** @var integer */
    protected int $intSliderStatus = 2;
    /** @var string  */
    protected string $strListTag = 'div';
    /** @var string  */
    protected string $strItemTag = 'div>';
    /** @var null|array DataSource, from which the items are picked and rendered */
    protected ?array $objDataSource = null;
    /** @var  callable */
    protected mixed $nodeParamsCallback = null;

    /**
     * Sets the callback function to be used for generating node parameters dynamically.
     *
     * @param callable $callback The callback function to be used for generating node parameters.
     *                           The function should accept relevant arguments and return an array of parameters.
     *
     * @return void
     */
    public function createNodeParams(callable $callback): void
    {
        $this->nodeParamsCallback = $callback;
    }

    /**
     * Processes the provided item and retrieves an array of parameters.
     *
     * @param mixed $objItem The item to be processed. This is passed to a callback function to determine its parameters.
     *
     * @return array Returns an array containing the item parameters, such as id, group_id, order, title, url, path, extension, dimensions, width, height, top, status, post_date, and post_update_date. An exception is thrown if the required callback is not provided.
     * @throws Exception Thrown if the nodeParamsCallback is not set.
     */
    public function getItem(mixed $objItem): array
    {
        if (!$this->nodeParamsCallback) {
            throw new Exception("Must provide a nodeParamsCallback");
        }
        $params = call_user_func($this->nodeParamsCallback, $objItem);

        $intId = '';
        if (isset($params['id'])) {
            $intId = $params['id'];
        }
        $intGroupId = '';
        if (isset($params['group_id'])) {
            $intGroupId = $params['group_id'];
        }
        $intOrder = '';
        if (isset($params['order'])) {
            $intOrder = $params['order'];
        }
        $strTitle = '';
        if (isset($params['title'])) {
            $strTitle = $params['title'];
        }
        $strUrl = '';
        if (isset($params['url'])) {
            $strUrl = $params['url'];
        }
        $strPath = '';
        if (isset($params['path'])) {
            $strPath = $params['path'];
        }
        $strExtension = '';
        if (isset($params['extension'])) {
            $strExtension = $params['extension'];
        }
        $strDimensions = '';
        if (isset($params['dimensions'])) {
            $strDimensions = $params['dimensions'];
        }
        $intWidth = '';
        if (isset($params['width'])) {
            $intWidth = $params['width'];
        }
        $intHeight = '';
        if (isset($params['height'])) {
            $intHeight = $params['height'];
        }
        $intTop = '';
        if (isset($params['top'])) {
            $intTop = $params['top'];
        }
        $intStatus = '';
        if (isset($params['status'])) {
            $intStatus = $params['status'];
        }
        $calPostDate = '';
        if (isset($params['post_date'])) {
            $calPostDate = $params['post_date'];
        }
        $calPostUpdateDate = '';
        if (isset($params['post_update_date'])) {
            $calPostUpdateDate = $params['post_update_date'];
        }

        return [
            'id' => $intId,
            'group_id' => $intGroupId,
            'order' => $intOrder,
            'title' => $strTitle,
            'url' => $strUrl,
            'path' => $strPath,
            'extension' => $strExtension,
            'dimensions' => $strDimensions,
            'width' => $intWidth,
            'height' => $intHeight,
            'top' => $intTop,
            'status' => $intStatus,
            'post_date' => $calPostDate,
            'post_update_date' => $calPostUpdateDate
        ];
    }

    /**
     * Fix up a possible embedded reference to the form.
     */
    public function sleep(): array
    {
        $this->nodeParamsCallback = ControlBase::sleepHelper($this->nodeParamsCallback);
        return parent::sleep();
    }

    /**
     * The object has been unserialized, so fix up pointers to embedded objects.
     * @param FormBase $objForm
     */
    public function wakeup(FormBase $objForm): void
    {
        parent::wakeup($objForm);
        $this->nodeParamsCallback = ControlBase::wakeupHelper($objForm, $this->nodeParamsCallback);
    }

    /**
     * Generates the HTML output for the control after processing the data source.
     *
     * This method binds data to the control, processes items from the data source,
     * and generates the corresponding HTML based on the slider status and parameters.
     *
     * @return string Returns the generated HTML string for the control. If no valid data source is provided or the
     *     slider status is inactive, an empty string is returned.
     * @throws Caller
     * @throws Exception
     */
    protected function getControlHtml(): string
    {
        $this->dataBind();
        $strParams = [];
        $strHtml = "";

        if ($this->objDataSource) {
            foreach ($this->objDataSource as $objObject) {
                $strParams[] = $this->getItem($objObject);
            }
        }

        if ($this->intSliderStatus !== 2) {
            $strHtml .= $this->renderTag('div', null, null, $this->renderSlide($strParams));
        } else {
            $strHtml .= '';
        }

        return $strHtml;
    }

    /**
     * Binds data to the object by executing the data binder if applicable.
     *
     * @return void This method performs data binding by calling the data binder function if certain conditions are met. It does nothing if the data source is already set or the object has already been rendered. Throws an exception if the data binder call encounters an error.
     * @throws Caller Thrown if an error occurs during the execution of the data binder.
     */
    public function dataBind(): void
    {
        // Run the DataBinder (if applicable)
        if (($this->objDataSource == null) && ($this->hasDataBinder()) && (!$this->blnRendered)) {
            try {
                $this->callDataBinder();
            } catch (Caller $objExc) {
                $objExc->incrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * Renders HTML for a set of slides based on the provided parameters.
     *
     * @param array $arrParams An array of slide parameters. Each element must include data such as:
     *                         - 'status' (int): Determines if the slide should be rendered (1 for active).
     *                         - 'url' (string): The URL to which the slide redirects.
     *                         - 'title' (string): The title for the slide, used in the alt and title attributes.
     *                         - 'path' (string): The file path of the image.
     *                         - 'extension' (string): The file extension of the image (e.g., 'SVG').
     *                         - 'height' (int): The height of the slide in pixels.
     *                         - 'top' (int): The top margin of the slide in pixels.
     *
     * @return string Returns the rendered HTML string for the slides. Only slides with a 'status' of 1 are rendered.
     */
    protected function renderSlide(array $arrParams): string
    {
        $strHtml = '';

        foreach ($arrParams as $params) {
            if ($params['status'] != 1) {
                continue;
            }

            $strHtml .= '<' . $this->ItemTag;

            $strUrl = $params['url'] ?? '';
            $strTitle = $params['title'] ?? '';
            $strPath = $params['path'] ?? '';
            $strExtension = $params['extension'] ?? '';
            $intHeight = $params['height'] ?? '';
            $intTop = $params['top'] ?? '';

            if ($strExtension === 'svg') {
                $strHtml .= '<div class="svg-container"';
                $strHtml .= (!empty($intHeight) || !empty($intTop)) ? ' style="' : '';

                if (!empty($intHeight)) {
                    $strHtml .= 'height:' . $intHeight . 'px;';
                }

                if (!empty($intTop)) {
                    $strHtml .= 'margin-top:' . $intTop . 'px;';
                }

                $strHtml .= (!empty($intHeight) || !empty($intTop)) ? '"' : '';

                $strHtml .= '>';

                if (!empty($strUrl)) {
                    $strHtml .= '<a href="' . $strUrl . '" target="_blank">';
                }

                $strHtml .= '<img src="' . $this->RootUrl . $strPath . '" alt="' . $strTitle . '" title="' . $strTitle . '" />';

                if (!empty($strUrl)) {
                    $strHtml .= '</a>';
                }

                $strHtml .= '</div>';
            } else {
                if (!empty($strUrl)) {
                    $strHtml .= '<a href="' . $strUrl . '" target="_blank">';
                }

                $strHtml .= '<img src="' . $this->TempUrl . $strPath . '" alt="' . $strTitle . '" title="' . $strTitle . '"';

                $strHtml .= (!empty($intHeight) || !empty($intTop)) ? ' style="' : '';

                if (!empty($intHeight)) {
                    $strHtml .= 'height:' . $intHeight . 'px;';
                }

                if (!empty($intTop)) {
                    $strHtml .= 'margin-top:' . $intTop . 'px;';
                }

                $strHtml .= (!empty($intHeight) || !empty($intTop)) ? '" />' : ' />';

                if (!empty($strUrl)) {
                    $strHtml .= '</a>';
                }
            }

            $strHtml .= _nl('</' . $this->ItemTag);
        }

        return $strHtml;
    }

    /**
     * Magic method to retrieve the value of an inaccessible or undefined property.
     *
     * @param string $strName The name of the property being accessed.
     *
     * @return mixed Returns the value of the requested property if it exists, including TempUrl, RootUrl, SliderStatus, ListTag, ItemTag, and DataSource. If the property does not match these predefined keys, it attempts to retrieve the property from the parent class. Throws an exception if the property is not found.
     * @throws Caller Thrown if the property is not available in the current or parent class.
     */
    public function __get(string $strName): mixed
    {
        switch ($strName) {
            case 'TempUrl': return $this->strTempUrl;
            case 'RootUrl': return $this->strRootUrl;
            case 'SliderStatus': return $this->intSliderStatus;
            case 'ListTag': return $this->strListTag;
            case 'ItemTag': return $this->strItemTag;
            case "DataSource": return $this->objDataSource;

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
     * Sets the value of a property dynamically based on the provided name and value.
     *
     * @param string $strName The name of the property to be set.
     * @param mixed $mixValue The value to assign to the specified property.
     *
     * @return void
     * @throws InvalidCast Thrown if the value cannot be cast to the expected type for certain properties.
     * @throws Caller Thrown if the parent::__set method fails to handle the property name.
     */
    public function __set(string $strName, mixed $mixValue): void
    {
        switch ($strName) {
            case "TempUrl":
                try {
                    $this->strTempUrl = Type::Cast($mixValue, Type::STRING);
                    $this->blnModified = true;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            case "RootUrl":
                try {
                    $this->strRootUrl = Type::Cast($mixValue, Type::STRING);
                    $this->blnModified = true;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            case "SliderStatus":
                try {
                    $this->intSliderStatus = Type::Cast($mixValue, Type::INTEGER);
                    $this->blnModified = true;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            case "ListTag":
                try {
                    $this->strListTag = Type::Cast($mixValue, Type::STRING);
                    $this->blnModified = true;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            case "ItemTag":
                try {
                    $this->strItemTag = Type::Cast($mixValue, Type::STRING);
                    $this->blnModified = true;
                    break;
                } catch (InvalidCast $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            case "DataSource":
                $this->blnModified = true;
                $this->objDataSource = $mixValue;
                break;

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