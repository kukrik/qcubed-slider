<?php

use QCubed as Q;
use QCubed\Action\Ajax;
use QCubed\Action\Terminate;
use QCubed\Bootstrap as Bs;
use QCubed\Event\Change;
use QCubed\Event\EnterKey;
use QCubed\Event\Input;
use QCubed\Folder;
use QCubed\Project\Control\ControlBase;
use QCubed\Project\Control\FormBase as Form;
use QCubed\Project\Application;
use QCubed\Action\ActionParams;
use QCubed\Project\Control\Paginator;
use QCubed\Query\Condition\ConditionInterface as QQCondition;
use QCubed\Control\ListItem;
use QCubed\Query\QQ;
use QCubed\QString;

class SliderListSettings extends Q\Control\Panel
{
    public $dlgModal1;
    public $dlgModal2;
    public $dlgModal3;
    public $dlgToastr1;
    public $dlgToastr2;

    public $btnAddSlider;
    public $btnSave;
    public $btnCancel;
    public $lblTitle;
    public $txtTitle;

    protected $dtgSliders;
    protected $lstItemsPerPage;
    protected $txtFilter;

    protected $dtgRenameSliders;
    protected $lstRenameItemsPerPage;

    public $btnRenameSave;
    public $btnRenameCancel;
    public $txtRenameTitle;
    public $lstRenameStatus;

    protected $intChangeSliderId = null;
    protected $intDeleteId = null;


    protected $strTemplate = 'SliderListSettings.tpl.php';

    public function __construct($objParentObject, $strControlId = null)
    {
        try {
            parent::__construct($objParentObject, $strControlId);
        } catch (\QCubed\Exception\Caller $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        $this->createTable();
        $this->createInputs();
        $this->createButtons();
        $this->createModals();
        $this->createToastr();
    }

    protected function createTable()
    {
        // TO THE USER

        $this->dtgSliders = new Q\Plugin\VauuTable($this);
        $this->dtgSliders->CssClass = "table vauu-table table-hover table-responsive";

        $col = $this->dtgSliders->createNodeColumn(t('Title'), QQN::ListOfSliders()->Title);

        $col = $this->dtgSliders->createNodeColumn(t('Status'), QQN::ListOfSliders()->StatusObject);
        $col->HtmlEntities = false;

        $col = $this->dtgSliders->createNodeColumn(t('Created'), QQN::ListOfSliders()->PostDate);
        $col->Format = 'DD.MM.YYYY hhhh:mm:ss';

        $col = $this->dtgSliders->createNodeColumn(t('Modified'), QQN::ListOfSliders()->PostUpdateDate);
        $col->Format = 'DD.MM.YYYY hhhh:mm:ss';

        $this->dtgSliders->Paginator = new Bs\Paginator($this);
        $this->dtgSliders->Paginator->LabelForPrevious = t('Previous');
        $this->dtgSliders->Paginator->LabelForNext = t('Next');
        $this->dtgSliders->ItemsPerPage = 10;

        $this->dtgSliders->UseAjax = true;
        $this->dtgSliders->SortColumnIndex = 2;
        $this->dtgSliders->SortDirection = -1;
        $this->dtgSliders->setDataBinder('dtgSliders_Bind', $this);
        $this->dtgSliders->RowParamsCallback = [$this, 'dtgSliders_GetRowParams'];
        $this->dtgSliders->addAction(new Q\Event\CellClick(0, null, Q\Event\CellClick::rowDataValue('value')),
            new Q\Action\AjaxControl($this,'dtgSlidersRow_Click'));

        $this->lstItemsPerPage = new Q\Plugin\Select2($this);
        $this->lstItemsPerPage->addCssFile(QCUBED_FILEUPLOAD_ASSETS_URL . '/css/select2-web-vauu.css');
        $this->lstItemsPerPage->MinimumResultsForSearch = -1;
        $this->lstItemsPerPage->Theme = 'web-vauu';
        $this->lstItemsPerPage->Width = '100%';
        $this->lstItemsPerPage->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_SINGLE;
        $this->lstItemsPerPage->SelectedValue = $this->dtgSliders->ItemsPerPage;
        $this->lstItemsPerPage->addItems(array(10, 25, 50, 100));
        $this->lstItemsPerPage->AddAction(new Change(), new Q\Action\AjaxControl($this,'lstItemsPerPage_Change'));

        $this->txtFilter = new Bs\TextBox($this);
        $this->txtFilter->Placeholder = t('Search...');
        $this->txtFilter->TextMode = Q\Control\TextBoxBase::SEARCH;
        $this->txtFilter->setHtmlAttribute('autocomplete', 'off');
        $this->txtFilter->addCssClass('search-box');
        $this->addFilterActions();


        // FOR A DEVELOPER OR WEBMASTER

        $this->dtgRenameSliders = new Q\Plugin\VauuTable($this);
        $this->dtgRenameSliders->CssClass = "table vauu-table table-hover table-responsive";

        $col = $this->dtgRenameSliders->createCallableColumn(t('Id'), [$this, 'Id_render']);
        $col->CellStyler->Width = '5%';

        $col = $this->dtgRenameSliders->createCallableColumn(t('Title'), [$this, 'Title_render']);
        $col->HtmlEntities = false;
        $col->CellStyler->Width = '25%';

        $col = $this->dtgRenameSliders->createCallableColumn(t('Status'), [$this, 'Status_render']);
        $col->HtmlEntities = false;
        $col->CellStyler->Width = '20%';

        $col = $this->dtgRenameSliders->createCallableColumn(t('Created'), [$this, 'Created_render']);
        $col->Format = 'DD.MM.YYYY hhhh:mm:ss';
        $col->CellStyler->Width = '15%';

        $col = $this->dtgRenameSliders->createCallableColumn(t('Modified'), [$this, 'Modified_render']);
        $col->Format = 'DD.MM.YYYY hhhh:mm:ss';
        $col->CellStyler->Width = '15%';

        $col = $this->dtgRenameSliders->createCallableColumn(t('Actions'), [$this, 'Change_render']);
        $col->HtmlEntities = false;
        $col->CellStyler->Width = '20%';

        $this->dtgRenameSliders->Paginator = new Bs\Paginator($this);
        $this->dtgRenameSliders->Paginator->LabelForPrevious = t('Previous');
        $this->dtgRenameSliders->Paginator->LabelForNext = t('Next');
        $this->dtgRenameSliders->ItemsPerPage = 10;

        $this->dtgRenameSliders->UseAjax = true;
        $this->dtgRenameSliders->SortColumnIndex = 0;
        //$this->dtgRenameSliders->SortDirection = -1;
        $this->dtgRenameSliders->setDataBinder('dtgSliders_Bind', $this);

        $this->lstRenameItemsPerPage = new Q\Plugin\Select2($this);
        $this->lstRenameItemsPerPage->addCssFile(QCUBED_FILEUPLOAD_ASSETS_URL . '/css/select2-web-vauu.css');
        $this->lstRenameItemsPerPage->MinimumResultsForSearch = -1;
        $this->lstRenameItemsPerPage->Theme = 'web-vauu';
        $this->lstRenameItemsPerPage->Width = '100%';
        $this->lstRenameItemsPerPage->SelectionMode = Q\Control\ListBoxBase::SELECTION_MODE_SINGLE;
        $this->lstRenameItemsPerPage->SelectedValue = $this->dtgRenameSliders->ItemsPerPage;
        $this->lstRenameItemsPerPage->addItems(array(10, 25, 50, 100));
        $this->lstRenameItemsPerPage->AddAction(new Change(), new Q\Action\AjaxControl($this,'lstItemsPerPage_Change'));
    }

    protected function createInputs()
    {
        $this->txtTitle = new Bs\TextBox($this);
        $this->txtTitle->Placeholder = t('The title of the new carousel');
        $this->txtTitle->MaxLength = ListOfSliders::TitleMaxLength;
        $this->txtTitle->setHtmlAttribute('autocomplete', 'off');
        $this->txtTitle->setCssStyle('float', 'left');
        $this->txtTitle->setCssStyle('margin-right', '10px');
        $this->txtTitle->CrossScripting = Bs\TextBox::XSS_HTML_PURIFIER;
        $this->txtTitle->Width = '30%';
        $this->txtTitle->Display = false;
        $this->txtTitle->AddAction(new Q\Event\EnterKey(), new Q\Action\AjaxControl($this,'btnSave_Click'));
        $this->txtTitle->addAction(new Q\Event\EnterKey(), new Q\Action\Terminate());
        $this->txtTitle->AddAction(new Q\Event\EscapeKey(), new Q\Action\AjaxControl($this,'btnCancel_Click'));
        $this->txtTitle->addAction(new Q\Event\EscapeKey(), new Q\Action\Terminate());

        $this->txtRenameTitle = new Bs\TextBox($this->dtgSliders);
        $this->txtRenameTitle->setHtmlAttribute('required', 'required');
        $this->txtRenameTitle->CrossScripting = Bs\TextBox::XSS_HTML_PURIFIER;

        $this->lstRenameStatus = new Q\Plugin\RadioList($this->dtgSliders);
        $this->lstRenameStatus->addItems([1 => t('Publiched'), 2 => t('Hidden')]);
        $this->lstRenameStatus->ButtonGroupClass = 'radio radio-orange radio-inline';
    }

    public function createButtons()
    {
        $this->btnAddSlider = new Q\Plugin\Button($this);
        $this->btnAddSlider->Text = t(' Add carousel');
        $this->btnAddSlider->Glyph = 'fa fa-plus';
        $this->btnAddSlider->CssClass = 'btn btn-orange';
        $this->btnAddSlider->addWrapperCssClass('center-button');
        $this->btnAddSlider->setCssStyle('float', 'left');
        $this->btnAddSlider->setCssStyle('margin-right', '10px');
        $this->btnAddSlider->CausesValidation = false;
        $this->btnAddSlider->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnAddSlider_Click'));

        $this->btnSave = new Q\Plugin\Button($this);
        $this->btnSave->Text = t('Save');
        $this->btnSave->CssClass = 'btn btn-orange';
        $this->btnSave->setCssStyle('float', 'left');
        $this->btnSave->setCssStyle('margin-right', '10px');
        $this->btnSave->addWrapperCssClass('center-button');
        $this->btnSave->Display = false;
        $this->btnSave->PrimaryButton = true;
        $this->btnSave->CausesValidation = true;
        $this->btnSave->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnSave_Click'));

        $this->btnCancel = new Q\Plugin\Button($this);
        $this->btnCancel->Text = t('Cancel');
        $this->btnCancel->CssClass = 'btn btn-default';
        $this->btnCancel->setCssStyle('float', 'left');
        $this->btnCancel->addWrapperCssClass('center-button');
        $this->btnCancel->Display = false;
        $this->btnCancel->CausesValidation = false;
        $this->btnCancel->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnCancel_Click'));

        $this->btnRenameSave = new Bs\Button($this->dtgSliders);
        $this->btnRenameSave->Text = t('Save');
        $this->btnRenameSave->CssClass = 'btn btn-orange';
        $this->btnRenameSave->PrimaryButton = true;
        $this->btnRenameSave->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnRenameSave_Click'));

        $this->btnRenameCancel = new Bs\Button($this->dtgSliders);
        $this->btnRenameCancel->Text = t('Cancel');
        $this->btnRenameCancel->CausesValidation = false;
        $this->btnRenameCancel->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnRenameCancel_Click'));
    }

    public function createModals()
    {
        $this->dlgModal1 = new Bs\Modal($this);
        $this->dlgModal1->Title = t('Tip');
        $this->dlgModal1->Text = t('<p style="margin-top: 15px;">Carousel cannot be created without name!</p>');
        $this->dlgModal1->HeaderClasses = 'btn-darkblue';
        $this->dlgModal1->addCloseButton(t("I close the window"));
        $this->dlgModal1->addAction(new \QCubed\Event\DialogButton(), new \QCubed\Action\AjaxControl($this, 'restoreTitle_Click'));
        $this->dlgModal1->addAction(new Bs\Event\ModalHidden(), new \QCubed\Action\AjaxControl($this, 'restoreTitle_Click'));

        $this->dlgModal2 = new Bs\Modal($this);
        $this->dlgModal2->Title = t("Warning");
        $this->dlgModal2->Text = t('<p style="line-height: 25px; margin-bottom: 2px;">Cannot create a carousel with the same name!</p>');
        $this->dlgModal2->HeaderClasses = 'btn-danger';
        $this->dlgModal2->addCloseButton(t("I understand"));
        $this->dlgModal2->addAction(new \QCubed\Event\DialogButton(), new \QCubed\Action\AjaxControl($this, 'restoreTitle_Click'));
        $this->dlgModal2->addAction(new Bs\Event\ModalHidden(), new \QCubed\Action\AjaxControl($this, 'restoreTitle_Click'));

        $this->dlgModal3 = new Bs\Modal($this);
        $this->dlgModal3->Title = t('Warning');
        $this->dlgModal3->Text = t('<p style="line-height: 25px; margin-bottom: 2px;"><strong>Are you sure that you have deleted 
                                    the embed code on the front end before?</strong></p>
                                <p style="line-height: 25px; margin-bottom: 2px;">If so, then there are 2 options, 
                                    either completely delete together with the previously selected images or hide this carousel.</p>
                                <p style="line-height: 25px; margin-bottom: -3px; color: #ff0000;"><strong>Once deleted, it cannot be undone!</strong></p>');
        $this->dlgModal3->HeaderClasses = 'btn-danger';
        $this->dlgModal3->addButton(t("I accept"), 'This file has been permanently deleted', false, false, null,
            ['class' => 'btn btn-orange']);
        $this->dlgModal3->addCloseButton(t("I'll cancel"));
        $this->dlgModal3->addAction(new \QCubed\Event\DialogButton(), new \QCubed\Action\AjaxControl($this, 'deleteItem_Click'));
    }

    public function createToastr()
    {
        $this->dlgToastr1 = new Q\Plugin\Toastr($this);
        $this->dlgToastr1->AlertType = Q\Plugin\Toastr::TYPE_SUCCESS;
        $this->dlgToastr1->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr1->Message = t('<strong>Well done!</strong> The carousel has been created and saved.');
        $this->dlgToastr1->ProgressBar = true;

        $this->dlgToastr2 = new Q\Plugin\Toastr($this);
        $this->dlgToastr2->AlertType = Q\Plugin\Toastr::TYPE_SUCCESS;
        $this->dlgToastr2->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr2->Message = t('<strong>Well done!</strong> The carousel has been updated and saved.');
        $this->dlgToastr2->ProgressBar = true;
    }

    public function dtgSliders_GetRowParams($objRowObject, $intRowIndex)
    {
        $strKey = $objRowObject->primaryKey();
        $params['data-value'] = $strKey;
        return $params;
    }

    protected function dtgSlidersRow_Click(ActionParams $params)
    {
        $intSliderId = intval($params->ActionParameter);
        Application::redirect('slider_edit.php' . '?id=' . $intSliderId);
    }

    protected function lstItemsPerPage_Change(ActionParams $params)
    {
        $this->dtgSliders->refresh();
    }

    protected function addFilterActions()
    {
        $this->txtFilter->addAction(new Input(300), new Q\Action\AjaxControl($this, 'filterChanged'));
        $this->txtFilter->addActionArray(new EnterKey(),
            [
                new Q\Action\AjaxControl($this, 'FilterChanged'),
                new Terminate()
            ]
        );
    }

    protected function filterChanged()
    {
        $this->dtgSliders->refresh();
    }

    public function dtgSliders_Bind()
    {
        $strSearchValue = $this->txtFilter->Text;
        $strSearchValue = trim($strSearchValue);

        if (is_null($strSearchValue) || $strSearchValue === '') {
            $objCondition = QQ::all();
        } else {
            $objCondition = QQ::orCondition(
                QQ::like(QQN::ListOfSliders()->Title, "%" . $strSearchValue . "%"),
                QQ::like(QQN::ListOfSliders()->PostDate, "%" . $strSearchValue . "%"),
                QQ::like(QQN::ListOfSliders()->PostUpdateDate, "%" . $strSearchValue . "%")
            );
        }

        $this->dtgSliders->TotalItemCount = ListOfSliders::countAll();
        $this->dtgRenameSliders->TotalItemCount = ListOfSliders::countAll();

        $objClauses = array();
        if ($objClause = $this->dtgSliders->OrderByClause)
            $objClauses[] = $objClause;
        if ($objClause = $this->dtgSliders->LimitClause)
            $objClauses[] = $objClause;

        $this->dtgSliders->DataSource = ListOfSliders::queryArray($objCondition, $objClauses);
        $this->dtgRenameSliders->DataSource = ListOfSliders::queryArray($objCondition, $objClauses);
    }

    protected function lstIRenametemsPerPage_Change(ActionParams $params)
    {
        $this->dtgSliders->refresh();
    }

    public function Id_render(ListOfSliders $objListOfSliders)
    {
        return $objListOfSliders->Id;
    }

    public function Title_render(ListOfSliders $objListOfSliders)
    {
        if ($objListOfSliders->Id == $this->intChangeSliderId) {
            return $this->txtRenameTitle->render(false);
        } else {
            return wordwrap($objListOfSliders->Title, 25, "\n", true);
        }
    }

    public function Status_render(ListOfSliders $objListOfSliders)
    {
        if ($objListOfSliders->Id == $this->intChangeSliderId) {
            return $this->lstRenameStatus->render(false);
        } else {
            return $objListOfSliders->AdminStatusObject;
        }
    }

    public function Created_render(ListOfSliders $objListOfSliders)
    {
        return $objListOfSliders->PostDate;
    }

    public function Modified_render(ListOfSliders $objListOfSliders)
    {
        return $objListOfSliders->PostUpdateDate;
    }

    protected function btnAddSlider_Click(ActionParams $params)
    {
        $this->txtTitle->Display = true;
        $this->btnSave->Display = true;
        $this->btnCancel->Display = true;
        $this->txtTitle->Text = null;
        $this->txtTitle->focus();
        $this->btnAddSlider->Enabled = false;
    }

    protected function btnSave_Click(ActionParams $params)
    {
        $objSlidersArray = ListOfSliders::loadAll();
        $scanned_Titles = [];
        foreach ($objSlidersArray as $slider) {
            if ($slider->getTitle()) {
                $scanned_Titles[] = $slider->getTitle();
            }
        }

        if (!$this->txtTitle->Text) {
            $this->dlgModal1->showDialogBox();
        } else {
            if (in_array(trim($this->txtTitle->Text), $scanned_Titles)) {
                $this->dlgModal2->showDialogBox();
                $this->txtTitle->focus();
            } else {
                $objSlider = new ListOfSliders();
                $objSlider->setTitle(trim($this->txtTitle->Text));
                $objSlider->setStatus(1);
                $objSlider->setPostDate(Q\QDateTime::Now());
                $objSlider->save();

                $this->txtTitle->Display = false;
                $this->btnSave->Display = false;
                $this->btnCancel->Display = false;
                $this->btnAddSlider->Enabled = true;
                $this->txtTitle->Text = null;
                $this->dlgToastr1->notify();
                $this->dtgSliders->refresh();
            }
        }
    }

    protected function btnCancel_Click(ActionParams $params)
    {
        $this->txtTitle->Display = false;
        $this->btnSave->Display = false;
        $this->btnCancel->Display = false;
        $this->btnAddSlider->Enabled = true;
        $this->txtTitle->Text = null;
    }

    public function restoreTitle_Click(ActionParams $params)
    {
        Application::executeControlCommand($this->txtTitle->ControlId, 'focus');
    }

    public function Change_render(ListOfSliders $objListOfSliders)
    {
        if ($objListOfSliders->Id == $this->intChangeSliderId) {
            return $this->btnRenameSave->render(false) . ' ' . $this->btnRenameCancel->render(false);
        } else {
            $btnChangeId = 'btnChange' . $objListOfSliders->Id;
            $btnChange = $this->Form->getControl($btnChangeId);
            if (!$btnChange) {
                $btnChange = new Bs\Button($this->dtgRenameSliders, $btnChangeId);
                $btnChange->Text = t('Change');
                $btnChange->ActionParameter = $objListOfSliders->Id;
                $btnChange->CssClass = 'btn btn-orange';
                $btnChange->CausesValidation = false;
                $btnChange->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnChange_Click'));

            }
            $btnDeleteId = 'btnDelete' . $objListOfSliders->Id;
            $btnDelete = $this->Form->getControl($btnDeleteId);
            if (!$btnDelete) {
                $btnDelete = new Bs\Button($this->dtgRenameSliders, $btnDeleteId);
                $btnDelete->Text = t('Delete');
                $btnDelete->ActionParameter = $objListOfSliders->Id;
                $btnDelete->CausesValidation = false;
                $btnDelete->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnDelete_Click'));
            }

            if ($this->intChangeSliderId) {
                $btnChange->Enabled = false;
                $btnDelete->Enabled = false;
            } else {
                $btnChange->Enabled = true;
                $btnDelete->Enabled = true;
            }

            return $btnChange->render(false) . ' ' . $btnDelete->render(false);
        }
    }

    protected function btnChange_Click(ActionParams $params)
    {
        $this->intChangeSliderId = intval($params->ActionParameter);
        $objSlider = ListOfSliders::load($this->intChangeSliderId);

        $this->txtRenameTitle->Text = $objSlider->getTitle();
        $this->lstRenameStatus->SelectedValue = $objSlider->getAdminStatus();
        Application::executeControlCommand($this->txtRenameTitle->ControlId, 'focus');

        $this->dtgRenameSliders->refresh();
    }

    protected function btnDelete_Click(ActionParams $params)
    {
        $this->intDeleteId = intval($params->ActionParameter);
        $this->dlgModal3->showDialogBox();
    }

    protected function deleteItem_Click(ActionParams $params)
    {
        $objGallery = ListOfSliders::load($this->intDeleteId);
        $this->dlgModal3->hideDialogBox();
    }

    protected function btnRenameSave_Click(ActionParams $params)
    {
        $objSlider = ListOfSliders::load($this->intChangeSliderId);

        $objSlidersArray = ListOfSliders::loadAll();
        $scanned_Titles = [];
        foreach ($objSlidersArray as $slider) {
            if ($slider->getTitle()) {
                $scanned_Titles[] = $slider->getTitle();
            }
        }

        if (!$this->txtRenameTitle->Text) {
            $this->dlgModal1->showDialogBox();
        } else if ((trim($this->txtRenameTitle->Text) == $objSlider->getTitle()) &&
            ($this->lstRenameStatus->SelectedValue !== $objSlider->getAdminStatus())) {
            $this->updateSlider($objSlider);
        } else  if (in_array(trim($this->txtRenameTitle->Text), $scanned_Titles)) {
            $this->dlgModal2->showDialogBox();
            $this->txtRenameTitle->Text = $objSlider->getTitle();
        } else {
            $this->updateSlider($objSlider);
        }
    }

    protected function updateSlider($objSlider)
    {
        $objSlider = ListOfSliders::loadById($objSlider->getId());
        $objSlider->setTitle(trim($this->txtRenameTitle->Text));
        $objSlider->setAdminStatus($this->lstRenameStatus->SelectedValue);
        $objSlider->setPostUpdateDate(Q\QDateTime::Now());
        $objSlider->save();

        $this->dlgToastr2->notify();
        $this->intChangeSliderId = null;
        $this->dtgRenameSliders->refresh();
    }

    protected function btnRenameCancel_Click(ActionParams $params)
    {
        $this->intChangeSliderId = null;
        $this->dtgRenameSliders->refresh();
    }
}