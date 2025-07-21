<?php
    require('qcubed.inc.php');
    require('classes/SlidersListAdmin.class.php');

    error_reporting(E_ALL); // Error engine - always ON!
    ini_set('display_errors', TRUE); // Error display - OFF in production env or real server
    ini_set('log_errors', TRUE); // Error logging


    use QCubed\Bootstrap as Bs;
    use QCubed\Exception\Caller;
    use QCubed\Project\Control\FormBase as Form;


    /**
     * Class SampleForm
     */
    class SampleForm extends Form
    {
        protected Bs\Tabs $nav;

        /**
         * Initializes the form and its components.
         * Sets up the navigation tabs and adds corresponding panels for managing carousels.
         *
         * @return void
         * @throws Caller
         */
        protected function formCreate(): void
        {
            parent::formCreate();

            $this->nav = new Bs\Tabs($this);
            $this->nav->addCssClass('tabbable tabbable-custom');

            $pnlSlidersList = new SlidersListAdmin($this->nav);
            $pnlSlidersList->Name = t('Carousels list');
        }
    }
    SampleForm::run('SampleForm');
