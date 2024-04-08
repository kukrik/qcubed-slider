<?php

use QCubed as Q;
use QCubed\Action\Ajax;
use QCubed\Action\Terminate;
use QCubed\Bootstrap as Bs;
use QCubed\Plugin\FileFinder;
use QCubed\Event\Change;
use QCubed\Event\EnterKey;
use QCubed\Event\Input;
use QCubed\Exception\Caller;
use QCubed\Exception\InvalidCast;
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
use QCubed\Type;

class SliderManager extends Q\Control\Panel
{

    public $dlgModal1;
    public $dlgToastr1;
    public $dlgToastr2;
    public $dlgToastr3;
    public $dlgToastr4;
    public $dlgToastr5;
    public $dlgToastr6;

    public $btnAddImage;
    public $btnChangeStatus;
    public $lblPublishingSlider;
    public $lstPublishingSlider;
    public $btnPublishingUpdate;
    public $btnPublishingCancel;

    public $btnRefresh;
    public $btnBack;
    public $dlgSorter;
    public $objTestSlider;
    public $txtTitle;
    public $txtUrl;
    public $lblDimensions;
    public $txtWidth;
    public $lblCross;
    public $txtHeight;
    public $txtTop;
    public $lstStatusSlider;
    public $calPostUpdateDate;
    public $btnUpdate;
    public $btnCancel;

    protected $intId;
    protected $intClick;
    protected $objSlider;
    protected $objListOfSlider;

    /** @var string */
    protected $strRootPath = APP_UPLOADS_DIR;
    /** @var string */
    protected $strTempUrl = APP_UPLOADS_TEMP_URL . '/_files/thumbnail';
    protected $strDateTimeFormat = 'd.m.Y H:i';
    protected $strTemplate = 'SliderManager.tpl.php';

    public function __construct($objParentObject, $strControlId = null)
    {
        try {
            parent::__construct($objParentObject, $strControlId);
        } catch (\QCubed\Exception\Caller $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        $this->intId = Application::instance()->context()->queryStringItem('id');
        if (strlen($this->intId)) {
            $this->objSlider = Sliders::load($this->intId);
            $this->objListOfSlider = ListOfSliders::load($this->intId);
        } else {
            // does nothing
        }

        $this->createInputs();
        $this->createButtons();
        $this->createModals();
        $this->createToastr();
        $this->createSorter();
        $this->createSlider();
    }

    protected function createInputs()
    {
        $this->lblPublishingSlider = new Bs\Label($this);
        $this->lblPublishingSlider->Text = $this->objListOfSlider->getStatusObject();
        $this->lblPublishingSlider->HtmlEntities = false;
        $this->lblPublishingSlider->setCssStyle('float', 'left');
        $this->lblPublishingSlider->setCssStyle('margin-top', '7px');
        $this->lblPublishingSlider->setCssStyle('margin-left', '20px');

        $this->lstPublishingSlider = new Q\Plugin\RadioList($this);
        $this->lstPublishingSlider->addItems([1 => t('Public slider'), 2 => t('Hidden slider')]);
        $this->lstPublishingSlider->SelectedValue = $this->objListOfSlider->getStatus();
        $this->lstPublishingSlider->Display = false;
        $this->lstPublishingSlider->ButtonGroupClass = 'radio radio-orange radio-inline';
        $this->lstPublishingSlider->setCssStyle('float', 'left');
        $this->lstPublishingSlider->setCssStyle('margin-left', '15px');

        $this->txtTitle = new Bs\TextBox($this);
        $this->txtTitle->Placeholder = t('Title');
        $this->txtTitle->setHtmlAttribute('autocomplete', 'off');
        $this->txtTitle->CrossScripting = Bs\TextBox::XSS_HTML_PURIFIER;

        $this->txtUrl = new Bs\TextBox($this);
        $this->txtUrl->Placeholder = t('Url');
        $this->txtUrl->setHtmlAttribute('autocomplete', 'off');

        $this->lblDimensions = new Bs\TextBox($this);
        $this->lblDimensions->ReadOnly = true;
        $this->lblDimensions->setCssStyle('margin-top', '10px');

        $this->txtWidth = new Bs\TextBox($this, 'width');
        $this->txtWidth->Placeholder = t('Width');
        $this->txtWidth->addCssClass('no-spinners');
        $this->txtWidth->setCssStyle('margin-top', '10px');
        $this->txtWidth->setCssStyle('float', 'left');
        $this->txtWidth->Width = '45%';
        $this->txtWidth->TextMode = Q\Control\TextBoxBase::NUMBER;

        $this->lblCross = new Bs\Label($this);
        $this->lblCross->Text = 'x';
        $this->lblCross->setCssStyle('margin-top', '15px');
        $this->lblCross->setCssStyle('margin-left', '10px');
        $this->lblCross->setCssStyle('margin-right', '10px');
        $this->lblCross->setCssStyle('float', 'left');
        $this->lblCross->Width = '3%';

        $this->txtHeight = new Bs\TextBox($this, 'height');
        $this->txtHeight->Placeholder = t('Height');
        $this->txtHeight->addCssClass('no-spinners');
        $this->txtHeight->setCssStyle('margin-top', '10px');
        $this->txtHeight->setCssStyle('float', 'left');
        $this->txtHeight->Width = '45%';
        $this->txtHeight->ReadOnly = true;
        $this->txtHeight->TextMode = Q\Control\TextBoxBase::NUMBER;

        $this->txtTop = new Bs\TextBox($this);
        $this->txtTop->Placeholder = t('Top');
        $this->txtTop->addCssClass('no-spinners');
        $this->txtTop->setCssStyle('margin-top', '10px');
        $this->txtTop->setCssStyle('float', 'left');
        $this->txtTop->Width = '17%';
        $this->txtTop->TextMode = Q\Control\TextBoxBase::NUMBER;

        $this->lstStatusSlider = new Q\Plugin\RadioList($this);
        $this->lstStatusSlider->addItems([1 => t('Published'), 2 => t('Hidden')]);
        $this->lstStatusSlider->ButtonGroupClass = 'radio radio-orange edit radio-inline';
        $this->lstStatusSlider->setCssStyle('margin-left', '15px');
        $this->lstStatusSlider->setCssStyle('float', 'left');
        $this->lstStatusSlider->Width = '48%';

        $this->calPostUpdateDate = new Bs\Label($this);
        $this->calPostUpdateDate->setCssStyle('font-weight', 'normal');
        $this->calPostUpdateDate->setCssStyle('margin-top', '18px');
        $this->calPostUpdateDate->setCssStyle('margin-left', '10px');
        $this->calPostUpdateDate->setCssStyle('float', 'left');
    }

    public function createButtons()
    {
        $this->btnAddImage = new Bs\Button($this);
        $this->btnAddImage->Text = t(' Add images');
        $this->btnAddImage->CssClass = 'btn btn-orange overlay';
        $this->btnAddImage->Width = '100%';
        $this->btnAddImage->setCssStyle('margin-bottom', '20px');
        $this->btnAddImage->CausesValidation = false;
        $this->btnAddImage->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnAddImage_Click'));

        $this->btnChangeStatus = new Bs\Button($this);
        $this->btnChangeStatus->Text = t(' Change status');
        $this->btnChangeStatus->CssClass = 'btn btn-orange';
        $this->btnChangeStatus->setCssStyle('float', 'left');
        $this->btnChangeStatus->CausesValidation = false;
        $this->btnChangeStatus->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnChangeStatus_Click'));

        $this->btnPublishingUpdate = new Bs\Button($this);
        $this->btnPublishingUpdate->Text = t('Update');
        $this->btnPublishingUpdate->CssClass = 'btn btn-orange';
        $this->btnPublishingUpdate->Display = false;
        $this->btnPublishingUpdate->setCssStyle('float', 'left');
        $this->btnPublishingUpdate->setCssStyle('margin-left', '10px');
        $this->btnPublishingUpdate->PrimaryButton = true;
        $this->btnPublishingUpdate->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnPublishingUpdate_Click'));

        $this->btnPublishingCancel = new Bs\Button($this);
        $this->btnPublishingCancel->Text = t('Cancel');
        $this->btnPublishingCancel->CssClass = 'btn btn-default';
        $this->btnPublishingCancel->Display = false;
        $this->btnPublishingCancel->setCssStyle('float', 'left');
        $this->btnPublishingCancel->setCssStyle('margin-left', '10px');
        $this->btnPublishingCancel->CausesValidation = false;
        $this->btnPublishingCancel->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnPublishingCancel_Click'));

        $this->btnRefresh = new Bs\Button($this);
        $this->btnRefresh->Glyph = 'fa fa-refresh';
        $this->btnRefresh->Tip = true;
        $this->btnRefresh->ToolTip = t('Refresh');
        $this->btnRefresh->CssClass = 'btn btn-darkblue';
        $this->btnRefresh->CausesValidation = false;
        $this->btnRefresh->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnRefresh_Click'));

        $this->btnBack = new Bs\Button($this);
        $this->btnBack->Text = t('Back');
        $this->btnBack->CssClass = 'btn btn-default';
        $this->btnBack->setCssStyle('margin-left', '10px');
        $this->btnBack->CausesValidation = false;
        $this->btnBack->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnBack_Click'));

        $this->btnUpdate = new Bs\Button($this);
        $this->btnUpdate->Text = t('Update');
        $this->btnUpdate->CssClass = 'btn btn-orange';
        $this->btnUpdate->setCssStyle('margin-top', '20px');
        $this->btnUpdate->PrimaryButton = true;
        $this->btnUpdate->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnUpdate_Click'));

        $this->btnCancel = new Bs\Button($this);
        $this->btnCancel->Text = t('Cancel');
        $this->btnCancel->CssClass = 'btn btn-default';
        $this->btnCancel->setCssStyle('margin-top', '20px');
        $this->btnCancel->setCssStyle('margin-left', '10px');
        $this->btnCancel->CausesValidation = false;
        $this->btnCancel->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this, 'btnCancel_Click'));
    }

    public function createModals()
    {
        $this->dlgModal1 = new Bs\Modal($this);
        $this->dlgModal1->Text = t('<p style="line-height: 25px; margin-bottom: 2px;">Are you sure you want to permanently delete this image along with its data?</p>
                                <p style="line-height: 25px; margin-bottom: -3px;">Can\'t undo it afterwards!</p>');
        $this->dlgModal1->Title = 'Warning';
        $this->dlgModal1->HeaderClasses = 'btn-danger';
        $this->dlgModal1->addButton("I accept", 'This file has been permanently deleted', false, false, null,
            ['class' => 'btn btn-orange']);
        $this->dlgModal1->addCloseButton(t("I'll cancel"));
        $this->dlgModal1->addAction(new \QCubed\Event\DialogButton(), new \QCubed\Action\AjaxControl($this, 'deleteItem_Click'));
    }

    public function createToastr()
    {
        $this->dlgToastr1 = new Q\Plugin\Toastr($this);
        $this->dlgToastr1->AlertType = Q\Plugin\Toastr::TYPE_SUCCESS;
        $this->dlgToastr1->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr1->Message = t('<strong>Well done!</strong> The data for this image has been updated successfully.');
        $this->dlgToastr1->ProgressBar = true;

        $this->dlgToastr2 = new Q\Plugin\Toastr($this);
        $this->dlgToastr2->AlertType = Q\Plugin\Toastr::TYPE_ERROR;
        $this->dlgToastr2->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr2->Message = t('<strong>Sorry!</strong> Failed to update data for this image.');
        $this->dlgToastr2->ProgressBar = true;

        $this->dlgToastr3 = new Q\Plugin\Toastr($this);
        $this->dlgToastr3->AlertType = Q\Plugin\Toastr::TYPE_SUCCESS;
        $this->dlgToastr3->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr3->Message = t('<strong>Well done!</strong> Deleting this image data was successful.');
        $this->dlgToastr3->ProgressBar = true;

        $this->dlgToastr4 = new Q\Plugin\Toastr($this);
        $this->dlgToastr4->AlertType = Q\Plugin\Toastr::TYPE_ERROR;
        $this->dlgToastr4->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr4->Message = t('<strong>Sorry!</strong> Deleting this image data failed.');
        $this->dlgToastr4->ProgressBar = true;

        $this->dlgToastr5 = new Q\Plugin\Toastr($this);
        $this->dlgToastr5->AlertType = Q\Plugin\Toastr::TYPE_SUCCESS;
        $this->dlgToastr5->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr5->Message = t('<strong>Well done!</strong> This slider is now public!');
        $this->dlgToastr5->ProgressBar = true;

        $this->dlgToastr6 = new Q\Plugin\Toastr($this);
        $this->dlgToastr6->AlertType = Q\Plugin\Toastr::TYPE_SUCCESS;
        $this->dlgToastr6->PositionClass = Q\Plugin\Toastr::POSITION_TOP_CENTER;
        $this->dlgToastr6->Message = t('<strong>Well done!</strong> This slider is now hidden!');
        $this->dlgToastr6->ProgressBar = true;
    }

    public function createSorter()
    {
        $this->dlgSorter = new Q\Plugin\SlideWrapper($this);
        $this->dlgSorter->createNodeParams([$this, 'Sorter_Draw']);
        $this->dlgSorter->createRenderButtons([$this, 'Buttons_Draw']);
        $this->dlgSorter->setDataBinder('Sorter_Bind', $this);
        $this->dlgSorter->addCssClass('sortable');
        $this->dlgSorter->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/thumbnail';
        $this->dlgSorter->Placeholder = 'placeholder';
        $this->dlgSorter->Handle = '.reorder';
        $this->dlgSorter->Items = 'div.image-blocks';
        $this->dlgSorter->addAction(new Q\Jqui\Event\SortableStop(), new Q\Action\AjaxControl($this, 'sortable_stop'));
        $this->dlgSorter->watch(QQN::sliders());
    }

    public function createSlider()
    {
        $this->objTestSlider = new Q\Plugin\SliderSetupAdmin($this);
        $this->objTestSlider->createNodeParams([$this, 'Sorter_Draw']);
        $this->objTestSlider->setDataBinder('Sorter_Bind');
        $this->objTestSlider->addCssClass('slider');

        if ($this->intId == 2) {
            $this->objTestSlider->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/large';
            $this->objTestSlider->Mode = 'fade';
            //$this->objTestSlider->Captions = true;
            $this->objTestSlider->Auto = true;
            //$this->objTestSlider->AutoControls = true;
            $this->objTestSlider->Controls = true;
            $this->objTestSlider->Pager = false;
            $this->objTestSlider->SlideWidth = 500;
        }

        if ($this->intId == 1) {
            $this->objTestSlider->TempUrl = APP_UPLOADS_TEMP_URL . '/_files/thumbnail';
            $this->objTestSlider->Auto = true;
            $this->objTestSlider->Pager = false;
            $this->objTestSlider->Speed = 2000;
            $this->objTestSlider->TouchEnabled = true;
            $this->objTestSlider->Controls = false;
            $this->objTestSlider->TickerHover = true;
            $this->objTestSlider->MinSlides = 4;
            $this->objTestSlider->MaxSlides = 5;
            $this->objTestSlider->MoveSlides = 1;
            $this->objTestSlider->SlideWidth = 200;
            $this->objTestSlider->SlideMargin = 50;
        }
    }

    public function Sorter_Bind()
    {
        $this->dlgSorter->DataSource = Sliders::QueryArray(
            QQ::Equal(QQN::sliders()->GroupId, $this->intId),
            QQ::orderBy(QQN::sliders()->Order)
        );

        $this->objTestSlider->DataSource = Sliders::QueryArray(
            QQ::Equal(QQN::sliders()->GroupId, $this->intId),
            QQ::orderBy(QQN::sliders()->Order)
        );
    }

    public function Sorter_Draw(Sliders $objSlider)
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

    public function Buttons_Draw(Sliders $objSlider)
    {
        $strEditId = 'btnEdit' . $objSlider->Id;

        if (!$btnEdit = $this->Form->getControl($strEditId)) {
            $btnEdit = new Bs\Button($this->dlgSorter, $strEditId);
            $btnEdit->Glyph = 'glyphicon glyphicon-pencil';
            $btnEdit->Tip = true;
            $btnEdit->ToolTip = t('Edit');
            $btnEdit->CssClass = 'btn btn-icon btn-xs edit';
            $btnEdit->ActionParameter = $objSlider->Id;
            $btnEdit->UseWrapper = false;
            $btnEdit->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnEdit_Click'));
        }

        $strDeleteId = 'btnDelete' . $objSlider->Id;

        if (!$btnDelete = $this->Form->getControl($strDeleteId)) {
            $btnDelete = new Bs\Button($this->dlgSorter, $strDeleteId);
            $btnDelete->Glyph = 'glyphicon glyphicon-trash';
            $btnDelete->Tip = true;
            $btnDelete->ToolTip = t('Delete');
            $btnDelete->CssClass = 'btn btn-icon btn-xs delete';
            $btnDelete->ActionParameter = $objSlider->Id;
            $btnDeleteUseWrapper = false;
            $btnDelete->addAction(new Q\Event\Click(), new Q\Action\AjaxControl($this,'btnDelete_Click'));
        }

        return $btnEdit->render(false) . $btnDelete->render(false);
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

    protected function btnAddImage_Click(ActionParams $params)
    {
        $_SESSION['finder_id'] = $this->intId;
        Application::redirect('finder_slider.php');
    }

    protected function btnChangeStatus_Click(ActionParams $params)
    {
        $this->btnChangeStatus->Enabled = false;
        $this->lblPublishingSlider->Display = false;
        $this->lstPublishingSlider->Display = true;
        $this->btnPublishingUpdate->Display = true;
        $this->btnPublishingCancel->Display = true;
    }

    protected function btnPublishingUpdate_Click(ActionParams $params)
    {
        $objListOfSliders = ListOfSliders::load($this->intId);
        $beforeStatus = $objListOfSliders->getStatus();

        $objListOfSliders->setStatus($this->lstPublishingSlider->SelectedValue);
        $objListOfSliders->setPostUpdateDate(Q\QDateTime::Now());
        $objListOfSliders->save();

        $this->btnChangeStatus->Enabled = true;
        $this->lblPublishingSlider->Display = true;
        $this->lstPublishingSlider->Display = false;
        $this->btnPublishingUpdate->Display = false;
        $this->btnPublishingCancel->Display = false;

        $this->lblPublishingSlider->Text = $objListOfSliders->getStatusObject();

        if ($beforeStatus !== $objListOfSliders->getStatus()) {
            if ($objListOfSliders->getStatus() == 1) {
                $this->dlgToastr5->notify();
            } else {
                $this->dlgToastr6->notify();
            }
        }
    }

    protected function btnPublishingCancel_Click(ActionParams $params)
    {
        $this->btnChangeStatus->Enabled = true;
        $this->lblPublishingSlider->Display = true;
        $this->lstPublishingSlider->Display = false;
        $this->btnPublishingUpdate->Display = false;
        $this->btnPublishingCancel->Display = false;
    }

    protected function btnRefresh_Click(ActionParams $params)
    {
        $this->objTestSlider->refresh();
    }

    protected function btnBack_Click(ActionParams $params)
    {
        Application::executeJavaScript("javascript:history.go(-1)");
    }

    protected function btnEdit_Click(ActionParams $params)
    {
        $this->txtWidth->Text = '';
        $this->txtHeight->Text = '';

        $intEditId = intval($params->ActionParameter);
        $objEdit = Sliders::load($intEditId);
        $this->intClick = intval($intEditId);

        $this->txtTitle->Text = $objEdit->Title;
        $this->txtUrl->Text = $objEdit->Url;
        $this->lblDimensions->Text = $objEdit->Dimensions;
        $this->txtWidth->Text = $objEdit->Width;
        $this->txtHeight->Text = $objEdit->Height;
        $this->txtTop->Text = $objEdit->Top;
        $this->lstStatusSlider->SelectedValue = $objEdit->Status;

        if (!$objEdit->PostUpdateDate) {
            $date = $objEdit->PostDate ? $objEdit->PostDate->qFormat('DD.MM.YYYY hhhh:mm:ss') : null;
        } else {
            $date = $objEdit->PostUpdateDate ? $objEdit->PostUpdateDate->qFormat('DD.MM.YYYY hhhh:mm:ss') : null;
        }

        $this->calPostUpdateDate->Text = $date;

        Application::executeJavaScript("
        
            var widthInput = $('#width');
            var heightInput = $('#height');
        
            $(\"[data-value='{$intEditId}']\").addClass('activated');
            $(\"[data-value='{$intEditId}']\").removeClass('inactivated');
            $('.slider-setting-wrapper').removeClass('hidden');

            var img = $('#{$this->dlgSorter->ControlId}_{$intEditId} img');
            var img_width = $('#{$this->dlgSorter->ControlId}_{$intEditId} img')[0].naturalWidth 

            widthInput.val(img_width);
            heightInput.val(img[0].naturalHeight);
            
            widthInput.val(img[0].naturalWidth);
            heightInput.val(img[0].naturalHeight);

            var aspectRatio = img[0].naturalWidth / img[0].naturalHeight;
            
            widthInput.on('keyup', function() {
                    var height = widthInput.val() / aspectRatio;
                    heightInput.val(Math.floor(height));
            });

//            heightInput.on('keyup', function() {
//                var width = heightInput.val() * aspectRatio;
//                widthInput.val(Math.floor(width));                 
//            });
       ");
    }

    protected function btnUpdate_Click(ActionParams $params)
    {
        $objUpdate = Sliders::load($this->intClick);
        $objUpdate->setTitle($this->txtTitle->Text);
        $objUpdate->setUrl($this->txtUrl->Text);
        $objUpdate->setWidth($this->txtWidth->Text);
        $objUpdate->setTop($this->txtTop->Text);
        $objUpdate->setStatus($this->lstStatusSlider->SelectedValue);
        $objUpdate->setPostUpdateDate(Q\QDateTime::Now());
        $objUpdate->save();

        if (is_file($this->strRootPath . $objUpdate->getPath())) {
            $this->dlgToastr1->notify();
        } else {
            $this->dlgToastr2->notify();
        }

        $this->txtTitle->refresh();
        $this->txtUrl->refresh();
        $this->calPostUpdateDate->Text = $objUpdate->PostUpdateDate->qFormat('DD.MM.YYYY hhhh:mm:ss');

        Application::executeJavaScript(sprintf("
             $(\"[data-value='{$this->intClick}']\").addClass('activated');
            //$('.slider-setting-wrapper').addClass('hidden');  
       "));

        $this->objTestSlider->refresh();
    }

    protected function btnDelete_Click(ActionParams $params)
    {
        $this->intClick = intval($params->ActionParameter);
        $this->dlgModal1->showDialogBox();
    }

    protected function deleteItem_Click(ActionParams $params)
    {
        $objSliders = Sliders::load($this->intClick);

        $objSlider = Sliders::loadById($objSliders->getId());
        $objSlider->delete();

        if ($objSliders->getId() !== $objSliders) {
            $this->dlgToastr3->notify();
        } else {
            $this->dlgToastr4->notify();
        }

        Application::executeJavaScript(sprintf("
            $('.slider-setting-wrapper').addClass('hidden');  
       "));

        $objFile = Files::loadById($objSliders->getFileId());
        $objFile->setLockedFile($objFile->getLockedFile() - 1);
        $objFile->save();

        $this->dlgModal1->hideDialogBox();
    }

    protected function btnCancel_Click(ActionParams $params)
    {
        Application::executeJavaScript(sprintf("
            jQuery(\"[data-value='{$this->intClick}']\").removeClass('activated');
            jQuery('.slider-setting-wrapper').addClass('hidden');  
       "));
    }






}
