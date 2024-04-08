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

class SlidersListAdmin extends Q\Control\Panel
{
    protected $dtgSliders;
    protected $lstItemsPerPage;
    protected $txtFilter;

    protected $strTemplate = 'SlidersListAdmin.tpl.php';

    public function __construct($objParentObject, $strControlId = null)
    {
        try {
            parent::__construct($objParentObject, $strControlId);
        } catch (\QCubed\Exception\Caller $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        $this->createTable();
    }

    protected function createTable()
    {
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

        ////////////////////////////

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

        $clauses[] = $this->dtgSliders->OrderByClause;
        //$clauses[] = $this->dtgSliders->LimitClause;

        $this->dtgSliders->DataSource = ListOfSliders::loadAll($clauses);
    }
}