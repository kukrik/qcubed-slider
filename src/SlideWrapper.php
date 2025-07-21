<?php

    namespace QCubed\Plugin;


    use QCubed as Q;
    use QCubed\Control\ControlBase;
    use QCubed\Control\FormBase;
    use QCubed\ApplicationBase;
    use QCubed\Exception\Caller;
    use QCubed\Exception\InvalidCast;
    use Exception;
    use QCubed\Project\Application;
    use QCubed\Type;

    /**
     * Class SlideWrapper
     *
     * @property string $TempUrl Default temp path APP_UPLOADS_TEMP_URL. If necessary, the temp dir must be specified.
     * @property string $RootUrl Default root path APP_UPLOADS_URL If necessary, the temp dir must be specified.
     * @property array DataSource
     *
     * @package QCubed\Plugin
     */

    class SlideWrapper extends Q\Project\Jqui\Sortable
    {
        use Q\Control\DataBinderTrait;

        /** @var string */
        protected string $strRootPath = APP_UPLOADS_DIR;
        /** @var string */
        protected string $strRootUrl = APP_UPLOADS_URL;
        /** @var string */
        protected string $strTempPath = APP_UPLOADS_TEMP_DIR;
        /** @var string */
        protected string $strTempUrl = APP_UPLOADS_TEMP_URL;
        /** @var null|array DataSource, from which the items are picked and rendered */
        protected ?array $objDataSource = null;
        /** @var  callable */
        protected mixed $nodeParamsCallback = null;
        /** @var  callable */
        protected mixed $cellParamsCallback = null;

        /**
         * Sets the callback function to generate node parameters dynamically.
         * The provided callback should return an array of parameters used
         * for creating or configuring nodes.
         *
         * @param callable $callback The callback function responsible for returning node parameters.
         *
         * @return void
         */
        public function createNodeParams(callable $callback): void
        {
            $this->nodeParamsCallback = $callback;
        }

        /**
         * Sets a callback function to generate parameters for rendering buttons.
         *
         * @param callable $callback The callback function that defines the parameters for rendering the buttons.
         *
         * @return void
         */
        public function createRenderButtons(callable $callback): void
        {
            $this->cellParamsCallback = $callback;
        }

        /**
         * Retrieves item information based on the provided object.
         * The method uses a callback function to extract parameters and maps them
         * into a structured array.
         *
         * @param mixed $objItem The object used to retrieve and construct item data.
         *
         * @return array|string An array containing item details such as id, group_id, order,
         *                      title, URL, path, extension, dimensions, width, top, status,
         *                      post_date, and post_update_date. Throws an exception if the
         *                      callback is not provided.
         *
         * @throws \Exception If the nodeParamsCallback property is not set.
         */
        public function getItem(mixed $objItem): array|string
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
                'top' => $intTop,
                'status' => $intStatus,
                'post_date' => $calPostDate,
                'post_update_date' => $calPostUpdateDate
            ];
        }

        /**
         * Retrieves object data based on the provided input object.
         * The method utilizes a callback function to process the input and retrieve the data.
         *
         * @param mixed $objItem The input object used to process and retrieve the data.
         *
         * @return mixed The resulting data retrieved by the callback function.
         *
         * @throws \Exception If the cellParamsCallback property is not set.
         */
        public function getObject(mixed $objItem): mixed
        {
            if (!$this->cellParamsCallback) {
                throw new Exception("Must provide a cellParamsCallback");
            }
            return call_user_func($this->cellParamsCallback, $objItem);
        }

        /**
         * Fix up a possible embedded reference to the form.
         */
    public function sleep(): array
    {
        $this->nodeParamsCallback = ControlBase::sleepHelper($this->nodeParamsCallback);
        $this->cellParamsCallback = ControlBase::sleepHelper($this->cellParamsCallback);
        return parent::sleep();
    }

        /**
         * The object has been unserialized, so fix up pointers to embedded objects.
         * @param \QCubed\Control\FormBase $objForm
         */
    public function wakeup(FormBase $objForm): void
    {
        parent::wakeup($objForm);
        $this->nodeParamsCallback = ControlBase::wakeupHelper($objForm, $this->nodeParamsCallback);
        $this->cellParamsCallback = ControlBase::wakeupHelper($objForm, $this->cellParamsCallback);
    }

        /**
         * Generates the HTML markup for the control based on the provided data source.
         * The method binds data, processes objects, and renders the resulting HTML content
         * using specified callbacks and rendering functions.
         *
         * @return string The generated HTML markup for the control. Returns empty string
         *                if no data source is provided.
         * @throws Caller
         * @throws Exception
         */
        protected function getControlHtml(): string
        {
            $this->dataBind();
            $strParams = [];
            $strObjects = [];
            $strHtml = "";

            if ($this->objDataSource) {
                foreach ($this->objDataSource as $objObject) {
                    $strParams[] = $this->getItem($objObject);
                    if ($this->cellParamsCallback) {
                        $strObjects[] = $this->getObject($objObject);
                    }
                }
            }

            $strHtml .= $this->renderTag('div', null, null, $this->renderSlide($strParams, $strObjects));

            return $strHtml;
        }

        /**
         * Executes the data binding process by invoking the DataBinder if applicable.
         * This method ensures that the data source is populated by calling the DataBinder.
         * An exception is thrown if an issue occurs during the execution of the DataBinder.
         *
         * @return void This method does not return any value.
         *
         * @throws Caller If an error occurs during the execution of the DataBinder.
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
         * Renders a series of slides based on provided parameters and objects.
         * Combines slide data from parameters and objects to generate an HTML
         * representation of slides, including information such as preview images,
         * events, and status.
         *
         * @param array $arrParams An array of parameters for each slide, where each
         *                         element may include values such as id, path, extension,
         *                         and status.
         * @param array $arrObjects An array of objects corresponding to the parameters,
         *                          used for additional HTML rendering through callbacks.
         *
         * @return string A generated string of HTML representing the rendered slides,
         *                including structural and visual information.
         */
        protected function renderSlide(array $arrParams, array $arrObjects): string
        {
            $strHtml = '';
            $strRenderCellHtml = '';

            for ($i = 0; $i < count($arrParams); $i++) {
                $intId = $arrParams[$i]['id'];
                $strPath = $arrParams[$i]['path'];
                $strExtension = $arrParams[$i]['extension'];
                $intStatus = $arrParams[$i]['status'];

                if ($this->cellParamsCallback) {
                    $strRenderCellHtml = $arrObjects[$i];
                }

                if ($intStatus !== 2) {
                    $strHtml .= _nl('<div id ="' . $this->strControlId . '_' . $intId . '" data-value="' . $intId . '" class="image-blocks">');
                } else {
                    $strHtml .= _nl('<div id ="' . $this->strControlId . '_' . $intId . '" data-value="' . $intId . '" class="image-blocks inactivated">');
                }

                $strHtml .= _nl(_indent('<div class="preview">', 1));

                if ($strExtension !== "svg") {
                    $strHtml .= _nl(_indent('<img src="' . $this->TempUrl . $strPath . '">', 2));
                } else {
                    $strHtml .= _nl(_indent('<img src="' . $this->RootUrl . $strPath . '">', 2));
                }

                $strHtml .= _nl(_indent('</div>', 1));
                $strHtml .= _nl(_indent('<div class="events">', 1));
                $strHtml .= _nl(_indent('<span class="icon-set reorder"><i class="fa fa-bars"></i></span>', 2));

                if ($this->cellParamsCallback) {
                    $strHtml .= _nl(_indent($strRenderCellHtml, 2));
                }

                $strHtml .= _nl(_indent('</div>', 1));
                $strHtml .= _nl('</div>');
            }

            return $strHtml;

        }

        /**
         * Generated method overrides the built-in Control method, causing it to not redraw completely. We restore
         * its functionality here.
         */
        public function refresh(): void
        {
            parent::refresh();
            ControlBase::refresh();
        }

        /**
         * Magic method to retrieve the value of a protected or undefined property.
         * Returns predefined property values or delegates the request to the parent class.
         *
         * @param string $strName The name of the property to retrieve.
         *
         * @return mixed Returns the value of the requested property. Possible values include:
         *               - RootPath: The root path string.
         *               - RootUrl: The root URL string.
         *               - TempPath: The temporary path string.
         *               - TempUrl: The temporary URL string.
         *               - DataSource: The associated data source object.
         *               If the property is not defined in the current class, it attempts to fetch it from the parent class.
         *
         * @throws Caller If the property is not defined in the parent class and cannot be resolved.
         */
        public function __get(string $strName): mixed
        {
            switch ($strName) {
                case 'RootPath': return $this->strRootPath;
                case 'RootUrl': return $this->strRootUrl;
                case 'TempPath': return $this->strTempPath;
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

        /**
         * Dynamically assigns a value to a property based on the given name.
         * The method validates and casts the value where necessary and marks
         * the object as modified if a property is successfully updated.
         *
         * @param string $strName The name of the property to be set.
         * @param mixed $mixValue The value assigned to the specified property. May vary depending on the property's type.
         *
         * @return void This method does not return any value. It updates the object's properties or throws an exception upon failure.
         *
         * @throws InvalidCast If the value provided cannot be cast to the expected type.
         * @throws Caller If the property name is invalid or cannot be assigned in the parent class.
         */
        public function __set(string $strName, mixed $mixValue): void
        {
            switch ($strName) {
                case "RootPath":
                    try {
                        $this->strRootPath = Type::cast($mixValue, Type::STRING);
                        $this->blnModified = true;
                        break;
                    } catch (InvalidCast $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
                case "RootUrl":
                    try {
                        $this->strRootUrl = Type::cast($mixValue, Type::STRING);
                        $this->blnModified = true;
                        break;
                    } catch (InvalidCast $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
                case "TempPath":
                    try {
                        $this->strTempPath = Type::cast($mixValue, Type::STRING);
                        $this->blnModified = true;
                        break;
                    } catch (InvalidCast $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
                case "TempUrl":
                    try {
                        $this->strTempUrl = Type::cast($mixValue, Type::STRING);
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