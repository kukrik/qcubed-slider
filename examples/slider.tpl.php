<?php
require(QCUBED_CONFIG_DIR . '/header.inc.php'); ?>

<?php $this->RenderBegin(); ?>

<div class="instructions">
    <h1 class="instruction_title" style="padding-bottom: 15px;">Simple examples: Sliders</h1>
</div>

<div class="container" style="width: 70%">
    <div class="row" style="padding-top: 30px;">

        <div style="margin-bottom: 25px;">
            <?= _r($this->objSponsors); ?>
        </div>

        <?= _r($this->dlgSorter); ?>

    </div>
</div>

<?php $this->RenderEnd(); ?>

<?php require(QCUBED_CONFIG_DIR . '/footer.inc.php'); ?>
