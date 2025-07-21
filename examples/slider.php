<?php
require_once('qcubed.inc.php');
require_once('../src/SlideWrapper.php');

error_reporting(E_ALL); // Error engine - always ON!
ini_set('display_errors', TRUE); // Error display - OFF in production env or real server
ini_set('log_errors', TRUE); // Error logging

use QCubed as Q;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Query\QQ;

    /**
     *
     */
    class ExamplesForm extends Form
{
    protected Q\Plugin\Slider $objHome;
    protected Q\Plugin\Slider $objSponsors;
    //protected $objSponsor;

    /**
     * Initializes and configures two slider components for the application.
     *
     * The method sets up a main slider and a sponsor slider, assigns
     * their respective data using predefined configurations, and applies
     * custom properties for functionality, styling, and behavior.
     *
     * @return void This function does not return any value.
     * @throws Caller
     * @throws InvalidCast
     */
    protected function formCreate(): void
    {
        $intHome = SlidersList::load(1);

        $this->objHome = new Q\Plugin\Slider($this);
        $this->objHome->SliderStatus = $intHome->getStatus();
        $this->objHome->createNodeParams([$this, 'Helper_Draw']);
        $this->objHome->setDataBinder('Helper_Bind');
        $this->objHome->addCssClass('slider');
        $this->objHome->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/large';
        $this->objHome->RootUrl = APP_UPLOADS_URL;
        $this->objHome->Mode = 'fade';
        $this->objHome->Captions = true;
        $this->objHome->Auto = true;
        //$this->objHome->AutoControls = true;
        $this->objHome->Controls = true;
        //$this->objHome->Pager = true;
        $this->objHome->SlideWidth = 700;


        $intSponsor = SlidersList::load(2);

        $this->objSponsors = new Q\Plugin\Slider($this);
        $this->objSponsors->SliderStatus = $intSponsor->getStatus();
        $this->objSponsors->createNodeParams([$this, 'Helper_Draw']);
        $this->objSponsors->setDataBinder('Helper_Bind');
        $this->objSponsors->addCssClass('slider');
        $this->objSponsors->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/thumbnail';
        $this->objSponsors->RootUrl = APP_UPLOADS_URL;
        $this->objSponsors->Auto = true;
        $this->objSponsors->Pager = false;
        $this->objSponsors->Speed = 2000;
        $this->objSponsors->TouchEnabled = true;
        $this->objSponsors->Controls = false;
        $this->objSponsors->TickerHover = true;
        $this->objSponsors->MinSlides = 4;
        $this->objSponsors->MaxSlides = 5;
        $this->objSponsors->MoveSlides = 1;
        $this->objSponsors->SlideWidth = 200;
        $this->objSponsors->SlideMargin = 50;
    }

    /**
     * Binds data to the objHome and objSponsors properties using the Sliders data source.
     *
     * Retrieves slider data with specific group IDs and orders the data accordingly.
     *
     * @return void
     * @throws Caller
     * @throws InvalidCast
     */
    protected function Helper_Bind(): void
    {
        $this->objHome->DataSource = Sliders::queryArray(
            QQ::Equal(QQN::sliders()->GroupId, 1),
            QQ::Clause(QQ::orderBy(QQN::sliders()->Order)
            ));

        $this->objSponsors->DataSource = Sliders::queryArray(
            QQ::Equal(QQN::sliders()->GroupId, 2),
            QQ::Clause(QQ::orderBy(QQN::sliders()->Order)
            ));
    }

    /**
     * Converts the properties of the given Sliders object into an associative array.
     *
     * @param Sliders $objSlider The Sliders object containing the data to be extracted.
     *
     * @return array An associative array containing the slider's attributes such as id, group_id, order, title, url, path, extension, dimensions, width, height, top, and status.
     */
    public function Helper_Draw(Sliders $objSlider): array
    {
        $a['id'] = $objSlider->Id;
        $a['group_id'] = $objSlider->GroupId;
        $a['order'] = $objSlider->Order;
        $a['title'] = $objSlider->Title;
        $a['url'] = $objSlider->Url;
        $a['path'] = $objSlider->Path;
        $a['extension'] = $objSlider->Extension;
        $a['dimensions'] = $objSlider->Dimensions;
        $a['width'] = $objSlider->Width;
        $a['height'] = $objSlider->Height;
        $a['top'] = $objSlider->Top;
        $a['status'] = $objSlider->Status;
        return $a;
    }
}
ExamplesForm::run('ExamplesForm');