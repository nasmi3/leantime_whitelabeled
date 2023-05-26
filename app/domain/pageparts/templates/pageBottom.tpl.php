<?php
defined('RESTRICTED') or die('Restricted access');

$appSettings = $this->get('appSettings');
?>

<?php if (isset($_SESSION['do_cron'])) { ?>
    <script>
        var req = new XMLHttpRequest();
        req.open("GET", "<?=BASE_URL?>/cron/run",true);
        req.send(null);


    </script>
<?php } ?>

<?php if(isset($_SESSION['userdata'])) { ?>
    <script>
        //5 min keep alive timer
        setInterval(function(){
            jQuery.get(leantime.appUrl+'/auth/keepAlive');
        }, 300000);
    </script>
<?php } ?>

<?php $this->dispatchTplEvent('beforeBodyClose'); ?>

<script src="<?=BASE_URL?>/js/compiled-footer.<?php echo $appSettings->appVersion; ?>.min.js"> </script>
</body>
</html>
