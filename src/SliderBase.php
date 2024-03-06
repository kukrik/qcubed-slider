<?php

namespace QCubed\Plugin;

use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\QDateTime;
use QCubed\Type;

/**
 * Class SliderBase
 *
 * @property string $TempPath Default temp path APP_UPLOADS_TEMP_DIR. If necessary, the temp dir must be specified.
 * @property string $ListTag Default: 'div'. Depending on the design of the theme, either use <div> or <ul>.
 * @property string $ItemTag Default: 'div'. Depending on the design of the theme, either use <div> or <li>.
 * @property boolean $IsLink Default: false.
 *                          If true, it is drawn like this:
 *                          <div>or<li>
 *                          <a target="_blank" href="..."><img alt="..." title="..." src="image.jpg" /></a>
 *                          </div>or</li>
 *                          If false, it is drawn like this:
 *                          <div>or<li>
 *                          <img alt="..." title="..." src="image.jpg" />
 *                          </div>or</li>
 *
 * @package QCubed\Plugin
 */

class SliderBase extends SliderBaseGen
{
    use Q\Control\DataBinderTrait;

    /** @var string  */
    protected $strTempUrl = APP_UPLOADS_TEMP_URL;
    /** @var string  */
    protected $strListTag = 'div';
    /** @var string  */
    protected $strItemTag = 'div>';
   /** @var boolean  */
    protected $blnIsLink = false;



    /** @var array DataSource from which the items are picked and rendered */
    protected $objDataSource;
    /** @var  callable */
    protected $nodeParamsCallback = null;

    public function createNodeParams(callable $callback)
    {
        $this->nodeParamsCallback = $callback;
    }

    /**
     * Uses HTML callback to get each loop in the original array. Relies on the NodeParamsCallback
     * to return information on how to draw each node.
     *
     * @param mixed $objItem
     * @return string
     * @throws \Exception
     */
    public function getItem($objItem)
    {
        if (!$this->nodeParamsCallback) {
            throw new \Exception("Must provide an nodeParamsCallback");
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
        $strDimensions = '';
        if (isset($params['dimensions'])) {
            $strDimensions = $params['dimensions'];
        }
        $intWidth = '';
        if (isset($params['width'])) {
            $intWidth = $params['width'];
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

        $vars = [
            'id' => $intId,
            'group_id' => $intGroupId,
            'order' => $intOrder,
            'title' => $strTitle,
            'url' => $strUrl,
            'path' => $strPath,
            'dimensions' => $strDimensions,
            'width' => $intWidth,
            'top' => $intTop,
            'status' => $intStatus,
            'post_date' => $calPostDate,
            'post_update_date' => $calPostUpdateDate
        ];

        return $vars;
    }

    /**
     * Fix up possible embedded reference to the form.
     */
    public function sleep()
    {
        $this->nodeParamsCallback = Q\Project\Control\ControlBase::sleepHelper($this->nodeParamsCallback);
        parent::sleep();
    }

    /**
     * The object has been unserialized, so fix up pointers to embedded objects.
     * @param \QCubed\Control\FormBase $objForm
     */
    public function wakeup(FormBase $objForm)
    {
        parent::wakeup($objForm);
        $this->nodeParamsCallback = Q\Project\Control\ControlBase::wakeupHelper($objForm, $this->nodeParamsCallback);
    }

    /**
     * @throws Caller
     */
    public function dataBind()
    {
        // Run the DataBinder (if applicable)
        if (($this->objDataSource === null) && ($this->hasDataBinder()) && (!$this->blnRendered)) {
            try {
                $this->callDataBinder();
            } catch (Caller $objExc) {
                $objExc->incrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * Returns the HTML for the control.
     *
     * @return string
     */
    protected function getControlHtml()
    {
        $this->dataBind();
        $strParams = [];
        $strHtml = "";

        if ($this->objDataSource) {
            foreach ($this->objDataSource as $objObject) {
                $strParams[] = $this->getItem($objObject);
            }
        }

        //$strHtml .= "<div data-nanogallery2 ='";
        //$strHtml .= json_encode($this->makePHPOptions(),JSON_UNESCAPED_SLASHES);

        $strHtml .= '<' . $this->ListTag . ' id="' . $this->ControlId . '">';
        $strHtml .= $this->renderImage($strParams);
        $strHtml .= '</' . $this->ListTag .'>';

        $this->objDataSource = null;
        return $strHtml;
    }

    protected function renderImage($arrParams)
    {
        $strHtml = '';

        for ($i = 0; $i < count($arrParams); $i++) {
            $intId = $arrParams[$i]['id'];
            $strTitle = $arrParams[$i]['title'];
            $strUrl = $arrParams[$i]['url'];
            $strPath = $arrParams[$i]['path'];
            $intWidth = $arrParams[$i]['width'];
            $intTop = $arrParams[$i]['top'];
            $intStatus = $arrParams[$i]['status'];

            if ($intStatus == 1) {
                $strHtml .= '<' . $this->ItemTag . '>';

                if ($this->IsLink == false) {
                    $strHtml .= '<img';
                    $strHtml .= ' alt=""';

                    if ($strTitle) {
                        $strHtml .= ' title="' . $strTitle . '"';
                    }

                    $strHtml .= ' src="' . $strPath . '"';
                    $strHtml .= ' />';
                } else {
                    $strHtml .= '<a target="_blank" href="' . $strUrl  . '">';
                    $strHtml .= '<img';

                    if ($intWidth || $intTop) {
                        $strHtml .= ' style="';
                    }

                    if ($intWidth) {
                        $strHtml .= 'width:';
                        $strHtml .= $intWidth . 'px;';
                    }

                    if ($intTop) {
                        $strHtml .= 'margin-top:';
                        $strHtml .= $intTop . 'px;';
                    }

                    $strHtml .= '"';
                    $strHtml .= ' alt=""';

                    if ($strTitle) {
                        $strHtml .= ' title="' . $strTitle . '"';
                    }

                    $strHtml .= ' src="' . $strPath . '"';
                    $strHtml .= ' />';
                    $strHtml .= '</a>';
                }

                $strHtml .= '</' . $this->ItemTag . '>';
            } else {
                $strHtml .= '';
            }
        }

        return $strHtml;
    }

    /**
     * @param string $strName
     * @return bool|mixed|null|string
     * @throws Caller
     */
    public function __get($strName)
    {
        switch ($strName) {
            case 'TempUrl': return $this->strTempUrl;
            case 'ListTag': return $this->strListTag;
            case 'ItemTag': return $this->strItemTag;
            case 'IsLink': return $this->blnIsLink;
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

    public function __set($strName, $mixValue)
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
            case "IsLink":
                try {
                    $this->blnIsLink = Type::Cast($mixValue, Type::BOOLEAN);
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