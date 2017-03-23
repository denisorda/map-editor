<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map editor</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/style.css">
    <script src="public/script.js"></script>
    <script
            src="https://code.jquery.com/jquery-2.2.4.js"
            integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
            crossorigin="anonymous"></script>
<!--    <script src="public/jquery-3.1.1.min.js"></script>-->
    <!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
</head>
<body>
<div class="container">
    <div class="text-center dashboard">
        <button type="button" class="btn btn-lg btn-primary" onClick="upload()">Upload Map</button>
        <button type="button" class="btn btn-lg btn-success" onClick="selectLevels()">Open Map</button>
    </div>
    <div class="uploader" style="display: none;">
        <div><a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Back</a></div>
        <span class="btn btn-success fileinput-button">
        <i class="fa fa-plus" aria-hidden="true"> </i>
        <span> Upload level image</span>
            <!-- The file input field used as target for the file upload widget -->
        <form id="fileupload" enctype="multipart/form-data">
            <input type="hidden" name="uploading" value="start">
            <input id="fileupload" type="file" name="files[]">
        </form>
    </span>
        <br>
        <br>
        <!-- The global progress bar -->
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
        </div>
        <div>
            <div class="form-group">
                <label for="world">World number</label>
                <input type="text" class="form-control" name="world">
            </div>
            <div class="form-group">
                <label for="level">Level number</label>
                <input type="text" class="form-control" name="level">
            </div>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-lg btn-primary" onClick="parse()">Parse</button>
        </div>
    </div>
    <div class="level-sprite-container" style="display: none;">
        <h2 class="map-title"></h2>
        <div class="level-sprite col-md-6">
        </div>
        <div class="sprites col-md-6">
        </div>
    </div>

    <div class="open-map-container" style="display: none;">
        <div>
            <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="open-map row">
        </div>
    </div>
</div>

<script id="template-preloader" type="text/template">
    <div>
        <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="text-center spin-container">
        <i class="fa fa-spinner fa-4x fa-spin text-primary" aria-hidden="true"></i>
    </div>
</script>

<script id="template-btnAfterParse" type="text/template">
    <div>
        <a href="/"><i class="fa fa-caret-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="text-center">
        <button type="button" class="btn btn-primary" onClick="saveLevel()">Save Map</button>
        <button type="button" class="btn btn-success" onClick="downloadLevel()">Export Map</button>
        <input type="hidden" name="levelId">
    </div>
</script>

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="public/jQuery-File-Upload-9.17.0/js/vendor/jquery.ui.widget.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="public/jQuery-File-Upload-9.17.0/js/jquery.fileupload-validate.js"></script>
<script>
    /*jslint unparam: true, regexp: true */
    /*global window, $ */
    $(function () {
        'use strict';
        // Change this to the location of your server-side upload handler:
        var url = '/';
        $('#fileupload').fileupload({
            url: url,
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 999000,
            // Enable image resizing, except for Android and Opera,
            // which actually support image resizing, but fail to
            // send Blob objects via XHR requests:
            disableImageResize: true,
            previewMaxWidth: 100,
            previewMaxHeight: 100,
            previewCrop: true
        }).on('fileuploadprogressall', function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }).on('fileuploaddone', function (e, data) {
            $.each(data.result.files, function (index, file) {
                localStorage.setItem('file', file.name);
            });
        }).on('fileuploadfail', function (e, data) {
            $.each(data.files, function (index) {
                console.log(index);
            });
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    });
</script>
</body>
</html>
