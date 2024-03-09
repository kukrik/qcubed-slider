<div class="form-horizontal">
    <div class="row">
        <div class="buttons-heading">
            <?= _r($this->btnAddSlider); ?>
            <?= _r($this->txtTitle); ?>
            <?= _r($this->btnSave); ?>
            <?= _r($this->btnCancel); ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="table-body">
        <div class="row">
            <div class="col-md-1"><?= _r($this->lstRenameItemsPerPage); ?></div>
            <div class="col-md-11" style="text-align: right; margin-bottom: 15px;"><?= _r($this->dtgRenameSliders->Paginator); ?></div>
        </div>
        <?= _r($this->dtgRenameSliders); ?>
    </div>
</div>










