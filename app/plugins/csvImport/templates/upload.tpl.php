<?php
    $maxSize = \leantime\core\fileupload::getMaximumFileUploadSize();
    $moduleId = $_GET['id'] ?? '';
?>

<div id="fileManager">

    <?php echo $this->displayNotification() ?>

    <div class="uploadWrapper" style="width:300px">

        <form id="upload-form">

        <div class="extra" style="margin-top:5px;"></div>
        <div class="fileUploadDrop">
            <p><i><?=$this->__("text.drop_files"); ?></i></p>
            <div class="file-upload-input" style="margin:auto;  display:inline-block"></div>
        </div>

        <!-- Progress bar #1 -->
        <div class="input-progress"></div>

        <div class="input-error"></div>

        </form>

    </div>

</div>



<script>

    if (typeof uppy === 'undefined') {


        const uppy = new Uppy.Uppy({
            debug: false,
            autoProceed: true,
            restrictions: {
                maxFileSize: <?=$maxSize?>
            }
        });

        uppy.use(Uppy.DropTarget, { target: '#fileManager' });

        uppy.use(Uppy.FileInput, {
            target: '.file-upload-input',
            pretty: true,
            locale: {
                strings: {
                    chooseFiles: ' Browse',
                }
            }
        });

        uppy.use(Uppy.XHRUpload, {
            endpoint: '<?=BASE_URL ?>/csvImport/upload',
            formData: true,
            fieldName: 'file',
        });

        uppy.use(Uppy.StatusBar, {
            target: '.input-progress',
            hideUploadButton: false,
            hideAfterFinish: false,
        });

        //uppy.use(Uppy.Webcam, { target: '.extra' });
        //uppy.use(Uppy.ProgressBar, { target: '.input-progress', hideAfterFinish: true });

        //uppy.use(Uppy.Audio, { target: '.extra', showRecordingLength: true });
        //uppy.use(Uppy.ScreenCapture, { target: '.extra' });

        uppy.use(Uppy.Form, { target: '#upload-form' });
        //uppy.use(Uppy.ImageEditor, { target: '.extra' });
        // Allow dropping files on any element or the whole document
        // Optimize images
       // uppy.use(Uppy.Compressor);

        /*
        uppy.use(Uppy.ThumbnailGenerator, {
            id: 'ThumbnailGenerator',
            thumbnailWidth: 200,
            thumbnailHeight: 200,
            thumbnailType: 'image/jpeg',
            waitForThumbnailsBeforeUpload: false,
        });

        uppy.on('thumbnail:generated', (file, preview) => {
            const img = document.createElement('img')
            img.src = preview;
            img.width = 100;
            document.body.appendChild(img);

        });*/

        // Upload
        uppy.on("restriction-failed", (file, error) => {

            jQuery(".input-error").html("<span class='label-important'>"+error+"</span>");
            return false
        });

        uppy.on('upload-success', (file, response) => {

            jQuery(".input-error").text('');

            window.location.href = "<?=BASE_URL?>/connector/integration?provider=csv_importer&step=entity&integrationId="+response.body.id;

        });


    }

</script>

