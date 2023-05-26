<?php
    $provider = $this->get("provider")
?>

<div class="pageheader">
    <div class="pageicon"><span class="fa fa-plug"></span></div>
    <div class="pagetitle">
        <div class="row">
            <div class="col-lg-8">
                <h1><?php echo $this->__("headlines.integrations"); ?></h1>
            </div>
        </div>
    </div>
</div>

<div class="maincontent">
    <div class="maincontentinner">

        <?php echo $this->displayNotification(); ?>

        <h3>New Integration</h3>
        <?=$provider->name ?><br />

        <a class="btn btn-primary" href="<?=BASE_URL?>/connector/integration?provider=<?=$provider->id?>&step=connect">Click Here to Connect</a>

    </div>
</div>

<script type="text/javascript">

   jQuery(document).ready(function() {


    });

</script>
