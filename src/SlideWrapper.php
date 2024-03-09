<?php

namespace QCubed\Plugin;

use QCubed as Q;
use QCubed\Bootstrap as Bs;
use QCubed\Control\FormBase;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\ModelConnector\Param as QModelConnectorParam;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Application;
use QCubed\Type;

/**
 * Class SliderBase
 *
 * @property string $TempUrl Default temp path APP_UPLOADS_TEMP_DIR. If necessary, the temp dir must be specified.
 *
 * @package QCubed\Plugin
 */

class SlideWrapper extends Q\Project\Jqui\Sortable
{
    use Q\Control\DataBinderTrait;

    /** @var string  */
    protected $strTempUrl = APP_UPLOADS_TEMP_URL;
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

        $strHtml .= $this->renderSlide($strParams);

        $this->objDataSource = null;
        return $strHtml;
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

    protected function renderSlide($arrParams)
    {
        $strHtml = '';

        for ($i = 0; $i < count($arrParams); $i++) {
            $intId = $arrParams[$i]['id'];
            $strPath = $arrParams[$i]['path'];

            $strHtml .= _nl('<div id ="' . $this->strControlId . '_' . $this->intId . '" class="image-blocks">');
            $strHtml .= _nl(_indent('<div class="preview">', 1));
            $strHtml .= _nl(_indent('<img src="' . $this->strPath . '">', 2));
            $strHtml .= _nl(_indent('</div>', 1));
            $strHtml .= _nl(_indent('<div class="events">', 1));
            $strHtml .= _nl(_indent('<span class="icon-set reorder"><i class="fa fa-bars"></i></span>', 2));
            $strHtml .= _nl(_indent('<span class="icon-set"><i class="glyphicon glyphicon-pencil "></i></span>', 2));
            $strHtml .= _nl(_indent('<span class="icon-set"><i class="glyphicon glyphicon-trash"></i></span>', 2));
            $strHtml .= _nl(_indent('</div>', 1));
            $strHtml .= _nl('</div>');
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