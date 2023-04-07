<?php
    defined('RESTRICTED') or die('Restricted access');
?>

<h4 class="widgettitle title-light"><?php echo $this->__("subtitles.delete") ?></h4>

<form method="post" class="formModal">
    <?php $this->dispatchTplEvent('afterFormOpen'); ?>
    <p><?php echo $this->__('text.confirm_event_deletion'); ?></p><br />
    <?php $this->dispatchTplEvent('beforeSubmitButton'); ?>
    <input type="submit" value="<?php echo $this->__('buttons.yes_delete'); ?>" name="del" class="button" />
    <a class="btn btn-primary" href="<?=BASE_URL ?>/calendar/showMyCalendar"><?php echo $this->__('buttons.back'); ?></a>
    <?php $this->dispatchTplEvent('beforeFormClose'); ?>
</form>

