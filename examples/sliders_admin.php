<?php
require('qcubed.inc.php');
require('classes/SlidersList.class.php');
require('classes/SliderListSettings.class.php');


error_reporting(E_ALL); // Error engine - always ON!
ini_set('display_errors', TRUE); // Error display - OFF in production env or real server
ini_set('log_errors', TRUE); // Error logging

use QCubed as Q;
use QCubed\Bootstrap as Bs;
use QCubed\Folder;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Project\Application;

/**
 * Class SampleForm
 */
class SampleForm extends Form
{
    protected $nav;

    protected function formCreate()
    {
        parent::formCreate();

        $this->nav = new Q\Plugin\Tabs($this);
        $this->nav->addCssClass('tabbable tabbable-custom');

        $pnlSliderListSettings = new SliderListSettings($this->nav);
        $pnlSliderListSettings->Name = t('Slider list settings');

        $pnlSlidersList = new SlidersList($this->nav);
        $pnlSlidersList->Name = t('Sliders list');
    }
}
SampleForm::run('SampleForm');
