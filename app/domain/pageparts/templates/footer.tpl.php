<?php defined('RESTRICTED') or die('Restricted access'); ?>

<?php $this->dispatchTplEvent('beforeFooterOpen'); ?>
<div class="footer">
    <?php $this->dispatchTplEvent('afterFooterOpen'); ?>
    <span style="color:#1b75bb"><?=$language->__("label.version"); ?> <?=$this->get("version");?> (2023-02)</span><br />
    <?php $this->dispatchTplEvent('beforeFooterClose'); ?>
</div>
<?php $this->dispatchTplEvent('afterFooterOpen'); ?>

