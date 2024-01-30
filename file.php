<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- File browse input field -->
    <div class="form-group">
        <label><b>Select File:</b></label>
        <input type="file" class="form-control" id="fileInput">
    </div>

    <!-- List the selected files -->
    <div id="fileList"></div>

    <!-- Upload button -->
    <div class="form-group">
        <a id="uploadBtn" href="javascript:;" class="btn btn-success">Upload</a>
    </div>

    <div class="progress"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/plupload/3.1.5/plupload.full.min.js"></script>
    <script>
        // Define Plupload uploader with configuration options
        var uploader = new plupload.Uploader({
            runtimes : 'html5,flash,silverlight,html4',
            browse_button : 'fileInput', // you can pass an id...
            url : 'upload.php',
            flash_swf_url : 'plupload/js/Moxie.swf',
            silverlight_xap_url : 'plupload/js/Moxie.xap',
            multi_selection: false,
            
            filters : {
                max_file_size : '30000mb',
                mime_types: [
                    {title : "Image files", extensions : "jpg,jpeg,gif,png"},
                    {title : "Video files", extensions : "mp4,avi,mpeg,mpg,mov,wmv"},
                    {title : "Zip files", extensions : "zip"},
                    {title : "Document files", extensions : "pdf,docx,xlsx"}
                ]
            },

            init: {
                PostInit: function() {
                    document.getElementById('fileList').innerHTML = '';

                    document.getElementById('uploadBtn').onclick = function() {
                        if (uploader.files.length < 1) {
                            document.getElementById('statusResponse').innerHTML = '<p style="color:#EA4335;">Please select a file to upload.</p>';
                            return false;
                        }else{
                            uploader.start();
                            return false;
                        }
                    };
                },

                FilesAdded: function(up, files) {
                    plupload.each(files, function(file) {
                        document.getElementById('fileList').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    });
                },

                UploadProgress: function(up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                    document.querySelector(".progress").innerHTML = '<div class="progress-bar" style="width: '+file.percent+'%;">'+file.percent+'%</div>';
                },
                
                FileUploaded: function(up, file, result) {
                    var responseData = result.response.replace('"{', '{').replace('}"', '}');
                    var objResponse = JSON.parse(responseData);
                    document.getElementById('statusResponse').innerHTML = '<p style="color:#198754;">' + objResponse.result.message + '</p>';
                },

                Error: function(up, err) {
                    document.getElementById('statusResponse').innerHTML = '<p style="color:#EA4335;">Error #' + err.code + ': ' + err.message + '</p>';
                }
            }
        });

        // Initialize Plupload uploader
        uploader.init();
    </script>
</body>
</html>