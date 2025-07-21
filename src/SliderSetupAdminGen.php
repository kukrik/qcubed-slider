<?php

    namespace QCubed\Plugin;

    use QCubed\Control\Panel;
    use QCubed\Exception\Caller;
    use QCubed\Exception\InvalidCast;
    use QCubed\Type;

    /**
     * Class SliderSetupAdminGen
     *
     * @see SliderSetupAdmin
     * @package QCubed\Plugin
     */

    /**
     * @property string $TempUrl Default temp path APP_UPLOADS_TEMP_URL. If necessary, the temp url must be specified.
     * @property string $RootUrl Default temp path APP_UPLOADS_URL. If necessary, the root url must be specified.
     * @property integer $SelectedGroup Default null.
     * @property string $SliderOptions Default null.
 *
     * @package QCubed\Plugin
     */

    class SliderSetupAdminGen extends Panel
    {
        protected ?string $strRootUrl = null;
        protected ?string $strTempUrl = null;
        protected ?int $intSelectedGroup = null;
        protected ?array $arrSliderOptions = null;

        /**
         * Generate an array of jQuery options based on the component's properties.
         *
         * @return array The array of jQuery options derived from the component's properties.
         */
        protected function makeJqOptions(): array
        {
            $jqOptions = null;
            if (!is_null($val = $this->RootUrl)) {$jqOptions['rootUrl'] = $val;}
            if (!is_null($val = $this->TempUrl)) {$jqOptions['tempUrl'] = $val;}
            if (!is_null($val = $this->SelectedGroup)) {$jqOptions['selectedGroup'] = $val;}
            if (!is_null($val = $this->SliderOptions)) {$jqOptions['sliderOptions'] = $val;}
            return $jqOptions;
        }

        /**
         * Retrieves the jQuery setup function name used for initializing the handler.
         *
         * @return string The name of the jQuery setup function.
         */
        public function getJqSetupFunction(): string
        {
            return 'sliderSetupAdmin';
        }

        /**
         * Magic getter method to retrieve property values based on the provided property name.
         *
         * @param string $strName The name of the property to retrieve.
         *
         * @return mixed Returns the corresponding property value, JSON-encoded data for arrays,
         *               or delegates to the parent::__get for undefined properties.
         * @throws Caller Throws an exception if the property is undefined and the parent::__get also fails.
         */
        public function __get(string $strName): mixed
        {
            switch ($strName) {
                case 'RootUrl': return $this->strRootUrl;
                case 'TempUrl': return $this->strTempUrl;
                case 'SelectedGroup': return $this->intSelectedGroup;;
                case 'SliderOptions': return $this->arrSliderOptions;

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
         * Handles setting the value of a property dynamically based on the provided name and value.
         *
         * @param string $strName The name of the property to set.
         * @param mixed $mixValue The value to assign to the specified property. It will be validated and cast to the appropriate type.
         *
         * @return void
         * @throws InvalidCast Throws an exception if the value cannot be cast to the expected type.
         * @throws Caller Throws an exception if the property is not recognized or cannot be set.
         */
        public function __set(string $strName, mixed $mixValue): void
        {
            switch ($strName) {
                case 'RootUrl':
                    try {
                        $this->strRootUrl = Type::Cast($mixValue, Type::STRING);
                        $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'rootUrl', $this->strRootUrl);
                        break;
                    } catch (InvalidCast $objExc) {
                        $objExc->incrementOffset();
                        throw $objExc;
                    }
                case 'TempUrl':
                    try {
                        $this->strTempUrl = Type::Cast($mixValue, Type::STRING);
                        $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'tempUrl', $this->strTempUrl);
                        break;
                    } catch (InvalidCast $objExc) {
                        $objExc->incrementOffset();
                        throw $objExc;
                    }
                case 'SelectedGroup':
                    try {
                        $this->intSelectedGroup = Type::Cast($mixValue, Type::INTEGER);
                        $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'selectedGroup', $this->intSelectedGroup);;
                        break;
                    } catch (InvalidCast $objExc) {
                        $objExc->incrementOffset();
                        throw $objExc;
                    }

                case 'SliderOptions':
                    try {
                        $this->arrSliderOptions = Type::Cast($mixValue, Type::ARRAY_TYPE);
                        $this->addAttributeScript($this->getJqSetupFunction(), 'option', 'sliderOptions', $this->arrSliderOptions);
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