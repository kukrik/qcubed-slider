<?php
require('qcubed.inc.php');
require('classes/SlidersList.class.php');


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

        $objListOfSliders = ListOfSliders::loadAll();

        foreach ($objListOfSliders as $listOfSlider) {
            if (ListOfSliders::countByAdminStatus(1) == 1) {
                Application::redirect('slider_edit.php' . '?id=' . $listOfSlider->getId());
            } else {
                $this->nav = new Q\Plugin\Tabs($this);
                $this->nav->addCssClass('tabbable tabbable-custom');

                $pnlSlidersList = new SlidersList($this->nav);
                $pnlSlidersList->Name = t('Carousels list');
            }
        }
    }
}
SampleForm::run('SampleForm');
