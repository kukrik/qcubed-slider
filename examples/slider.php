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
    protected $objSponsors;

    protected $dlgSorter;


    protected $intSonsorsId;
    protected $objSponsor;


    protected function formCreate()
    {
        $this->intSonsorsId = ListOfSliders::load(27);
        $this->objSponsor = Sliders::load(27);

        $this->objSponsors = new Q\Plugin\Slider($this);
        $this->objSponsors->createNodeParams([$this, 'Sponsors_Draw']);
        $this->objSponsors->setDataBinder('Sponsors_Bind');
        $this->objSponsors->addCssClass('slider');
        $this->objSponsors->UseWrapper = false;
        $this->objSponsors->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/thumbnail';
        $this->objSponsors->IsLink = true;
        $this->objSponsors->Width = $this->objSponsor->Width ?? null;
        $this->objSponsors->Top = $this->objSponsor->Top ?? null;
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

        $this->dlgSorter = Q\Plugin\SlideWrapper($this);
        $this->dlgSorter->createNodeParams([$this, 'Sliders_Draw']);
        $this->dlgSorter->setDataBinder('Sliders_Bind');
        $this->dlgSorter->Handle = '.reorder';
        $this->dlgSorter->Items = 'div';
        $this->dlgSorter->addAction(new QC\Jqui\Event\SortableStop(), new Q\Action\AjaxControl($this,'sortable_stop'));


    }

    protected function Sponsors_Bind()
    {
        $this->objSponsors->DataSource = Sliders::QueryArray(
            QQ::Equal(QQN::sliders()->GroupId, $this->intSonsorsId),
            QQ::orderBy(QQN::sliders()->Order)
        );
    }

    public function Sponsors_Draw(Sliders $objSlider)
    {
        $a['id'] = $objSlider->Id;
        $a['group_id'] = $objSlider->GroupId;
        $a['order'] = $objSlider->Order;
        $a['title'] = $objSlider->Title;
        $a['url'] = $objSlider->Url;
        $a['path'] = $objSlider->Path;
        $a['dimensions'] = $objSlider->Dimensions;
        $a['width'] = $objSlider->Width;
        $a['top'] = $objSlider->Top;
        $a['status'] = $objSlider->Status;
        return $a;
    }

    protected function Sliders_Bind()
    {
        $this->dlgSorter->DataSource = Sliders::QueryArray(
            QQ::Equal(QQN::sliders()->GroupId, $this->intSonsorsId),
            QQ::orderBy(QQN::sliders()->Order)
        );
    }

    public function Sliders_Draw(Sliders $objSlider)
    {
        $a['id'] = $objSlider->Id;
        $a['group_id'] = $objSlider->GroupId;
        $a['order'] = $objSlider->Order;
        $a['title'] = $objSlider->Title;
        $a['url'] = $objSlider->Url;
        $a['path'] = $this->dlgSorter->TempUrl . '/thumbnail' . $objSlider->Path;
        $a['dimensions'] = $objSlider->Dimensions;
        $a['width'] = $objSlider->Width;
        $a['top'] = $objSlider->Top;
        $a['status'] = $objSlider->Status;
        return $a;
    }

    public function sortable_stop(ActionParams $params) {
        $arr = $this->dlgSorter->ItemArray;
        foreach ($arr as $order => $cids) {
            $cid = explode('_',  $cids);
            $id = end($cid);

            $objSorter = Sliders::load($id);
            $objSorter->setOrder($order);
            $objSorter->save();
        }
    }


}
ExamplesForm::Run('ExamplesForm');