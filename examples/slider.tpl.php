<?php
    require(QCUBED_CONFIG_DIR . '/header.inc.php'); ?>

<?php $this->RenderBegin(); ?>

<style>
    .svg-container img {height: 100%;}
    .bx-wrapper img {max-height: 450px;}
</style>

<div class="instructions">
    <h1 class="instruction_title" style="padding-bottom: 15px;">Simple examples: Sliders</h1>
</div>
<div class="container" style="width: 70%">
    <div class="row" style="padding-top: 30px;">
        <div style="margin: auto auto 25px;width: 670px;">
            <?= _r($this->objHome); ?>
        </div>
        <div style="margin-bottom: 25px;">
            <?= _r($this->objSponsors); ?>
        </div>
    </div>
</div>

<?php $this->RenderEnd(); ?>

<?php require(QCUBED_CONFIG_DIR . '/footer.inc.php'); ?>
