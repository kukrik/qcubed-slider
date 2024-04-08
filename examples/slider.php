<?php
require_once('qcubed.inc.php');
require_once('../src/SlideWrapper.php');

error_reporting(E_ALL); // Error engine - always ON!
ini_set('display_errors', TRUE); // Error display - OFF in production env or real server
ini_set('log_errors', TRUE); // Error logging

use QCubed as Q;
use QCubed\Action\ActionParams;
use QCubed\Project\Application;
use QCubed\Bootstrap as Bs;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Query\QQ;

class ExamplesForm extends Form
{
    protected $objHome;
    protected $objSponsors;
    protected $objSponsor;

    protected function formCreate()
    {
        $intHome = ListOfSliders::load(2);

        $this->objHome = new Q\Plugin\Slider($this);
        $this->objHome->SliderStatus = $intHome->getStatus();
        $this->objHome->createNodeParams([$this, 'Helper_Draw']);
        $this->objHome->setDataBinder('Helper_Bind');
        $this->objHome->addCssClass('slider');
        $this->objHome->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/large';
        $this->objHome->Mode = 'fade';
        //$this->objHome->Captions = true;
        $this->objHome->Auto = true;
        //$this->objHome->AutoControls = true;
        $this->objHome->Controls = true;
        //$this->objHome->Pager = false;
        $this->objHome->SlideWidth = 700;


        $intSponsor = ListOfSliders::load(1);

        $this->objSponsors = new Q\Plugin\Slider($this);
        $this->objSponsors->SliderStatus = $intSponsor->getStatus();
        $this->objSponsors->createNodeParams([$this, 'Helper_Draw']);
        $this->objSponsors->setDataBinder('Helper_Bind');
        $this->objSponsors->addCssClass('slider');
        $this->objSponsors->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/thumbnail';
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

    protected function Helper_Bind()
    {
        $this->objHome->DataSource = Sliders::QueryArray(
            QQ::Equal(QQN::sliders()->GroupId, 2),
            QQ::orderBy(QQN::sliders()->Order)
        );

        $this->objSponsors->DataSource = Sliders::QueryArray(
            QQ::Equal(QQN::sliders()->GroupId, 1),
            QQ::orderBy(QQN::sliders()->Order)
        );
    }

    public function Helper_Draw(Sliders $objSlider)
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
ExamplesForm::Run('ExamplesForm');
