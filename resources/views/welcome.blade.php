<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Auto sending email by CSV</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            /*align-items: center;*/
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        body {
            font-family: "aktiv-grotesk",sans-serif;
        }
        .button {
            position: relative;
            margin: 0;
            border: none;
            color: #fff;
            display: inline-block;
            padding: 0 64px;
            height: 44px;
            cursor: pointer;
            transition: background-color .2s;
            font-size: 15px;
            font-weight: 500;
            outline: 0 !important;
            user-select: none;
            white-space: nowrap;
        }
        .primary-header {
            margin-left: 50px;
        }
        .secondaryTextColor {
            color: #9daab6;
        }
        .notice {
            border: 3px #e8e8e8 dotted;
            margin: 40px;
            padding: 64px;
        }
        .uploader-stage .file-uploader {
            float: left;
            margin-right: 64px;
        }
        .button.primary {
            background-color: #4a90e2;
        }
        .button.primary:focus, .button.primary:hover {
            background-color: #1b5dab;
        }
        .button {
            height: 44px;
        }
        .uploader-stage .file-uploader p {
            text-align: center;
        }
        .button.primary:active {
            background-color: #14457f;
        }
        .paragraph {
            font-size: 16px;
            line-height: 1.5;
            text-align: left;
        }
        .borderRadius, .button, .flatfile-root, .flatfile-modal {
            border-radius: 4px;
        }
        .uploader-stage {
            max-width: 1400px;
            margin-top: 100px;
        }
        #progress_bar {
            margin: 0;
            padding: 5px;
            border: 1px solid #4a90e2;
            font-size: 14px;
            color: #fff;
            clear: both;
            opacity: 0;
            -moz-transition: opacity 1s linear;
            -o-transition: opacity 1s linear;
            -webkit-transition: opacity 1s linear;
        }
        #progress_bar.loading {
            opacity: 1.0;
        }
        #progress_bar .percent {
            background-color: #4a90e2;
            height: auto;
            width: 0;
        }
        .action-btn {
            margin: 20px 0 0;
            text-align: center;
            display: none;
        }
        #error-message {
            color: #ff8936;
            margin-top: 7px;
        }
        #success-message {
            color: #2ab27b;
            font-weight: bold;
            display: none;
        }
        #overlay{
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(359deg);
            }
        }
        .result {
            margin: 0 50px;
        }
        .result .dataCount {
            font-weight: 600;
        }
        .pull-right {
            float: right;
        }
        .header-result-bar {
            margin: 10px 0;
        }
        .action-send-mail {
            display: none;
        }
        .send-email-message {
            margin-top: 10px;
            margin-right: 10px;
            color: #44cc5b;
        }
        .header ul li {
            display: inline-block;
            padding: 10px;
        }
        .mid {
            width: 1200px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="mid">
        <div class="header">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/setting/1/edit">Setting</a></li>
            </ul>
        </div>
        <div class="wrapper uploader-stage">

        <h1 class="primary-header">Bulk Send Emails</h1>
        <div tabindex="0"><input accept=".csv,.tsv" type="file" class="inputFile" autocomplete="off" tabindex="-1" style="display: none;"></div>
        <div class="notice secondaryTextColor">
            <div class="file-uploader">
                <button class="button primary icon-upload">Upload data from file</button>
                <p class="secondaryTextColor">.csv spreadsheets accepted.</p>
            </div>
            <p class="notice-right paragraph secondaryTextColor">
                You can upload any .csv file with any set of columns as long as it has 1 record per row.
                The next step will allow you to match your spreadsheet columns to the right data points.
                You'll be able to clean up or remove any corrupted data before finalizing your report.
            </p>
            <div id="file-info"></div>
            <div id="progress_bar"><div class="percent">0%</div></div>
            <div id="error-message"></div>
            <div id="success-message">Send mail successful.</div>
            <div class="action-btn"><button class="button primary process-file">Upload</button></div>
        </div>
        <div class="result">
            <div class="header-result-bar">
                <span class="dataCount"></span>
                <button class="button primary pull-right action-send-mail">Send email</button>
                <span class="send-email-message pull-right"></span>
                <div class="clearfix" style="clear: both;"></div>
            </div>
            <div id="dataList"></div>
        </div>
    </div>
    </div>
</div>
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
<script type="application/javascript">
    $(function() {
        var reader;
        var fileInput = '';
        var progress = document.querySelector('.percent');
        var dataTemp = [];
        $('.action-send-mail').on('click', function (e) {

            var conf = confirm("You are about send all email in list below. Are you sure to continue?");
            if (conf == true && dataTemp.length > 0) {
                var formData = new FormData();
                formData.append('data', JSON.stringify(dataTemp));
                formData.append('_token', "{{ csrf_token() }}");
                $.ajax({
                    url: '/sendmail',
                    data: formData,
                    method: 'POST',
                    contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                    processData: false, // NEEDED, DON'T OMIT THIS
                    beforeSend: function() {
                        $("#overlay").fadeIn(300);
                    },
                    success: function (data) {
                        var res = JSON.parse(data);
                        console.log(res);
                        if (res.total > 0) {
                            $('.send-email-message').html('Total ' + res.total + ' email(s) has been sent.');
                            setTimeout(function () {
                                $('.send-email-message').html('');
                            }, 5000);
                        }
                        $("#overlay").fadeOut(300);
                    }
                });
            }
        });
        $('.icon-upload').on('click', function (e) {
            $('.inputFile').trigger('click');
        });
        $('.process-file').on('click', function (e) {
            if (!checkInvalidFile()) return false;
            dataTemp = [];
            var formData = new FormData();
            formData.append('file', fileInput);
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('type', 'csv');
            $.ajax({
                url: '/upload',
                data: formData,
                method: 'POST',
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                beforeSend: function() {
                    $("#overlay").fadeIn(300);
                },
                success: function (data) {
                    var res = JSON.parse(data);
                    console.log(res);
                    // if (res.empty) {
                    //     $('#success-message').html('No email in sheet.');
                    // }
                    // $('#success-message').css('display','block');
                    var content = '';
                    if (res.data && res.total > 0) {
                        dataTemp = res.data;
                        content += '<table class="table table-striped" id="dataTable">';
                        var header = '<thead>';
                        var body = '<tbody>';
                        for (var i = 0; i < res.data.length; i++) {
                            body += '<tr>';
                            for(var j = 0; j < res.data[i].length; j++) {
                                if (i == 0) {
                                    header += '<th>'+res.data[i][j]+'</th>';
                                } else {
                                    body += '<td>'+res.data[i][j]+'</td>';
                                }
                            }
                            body += '</tr>';
                        }
                        content += header + body + '</table>';
                        $('.action-send-mail').css('display', 'block');
                    } else {
                        $('.action-send-mail').css('display', 'none');
                    }
                    $('.dataCount').html('Found ' + res.total + ' email(s) in sheet.')
                    $('#dataList').html(content);
                    $("#overlay").fadeOut(300);
                }
            });
        });
        $('.inputFile').on('change', function (evt) {
            fileInput = '';
            console.log('change');
            evt.stopPropagation();
            evt.preventDefault();
            var file = evt.target.files[0];
            var output = [];
            output.push('<li style="color: #51d08a;"><strong>', escape(file.name), '</strong> (', file.type || 'n/a', ') - ',
                file.size, ' bytes, last modified: ',
                file.lastModifiedDate ? file.lastModifiedDate.toLocaleDateString() : 'n/a',
                '</li>');
            document.getElementById('file-info').innerHTML = '<ul>' + output.join('') + '</ul>';
            $('.action-btn').css('display','none');
            // Reset progress indicator on new file selection.
            progress.style.width = '0%';
            progress.textContent = '0%';

            reader = new FileReader();
            reader.onerror = errorHandler;
            reader.onprogress = updateProgress;
            reader.onabort = function(e) {
                alert('File read cancelled');
            };
            reader.onloadstart = function(e) {
                document.getElementById('progress_bar').className = 'loading';
            };
            reader.onload = function(e) {
                // Ensure that the progress bar displays 100% at the end.
                progress.style.width = '100%';
                progress.textContent = '100%';
                $('.action-btn').css('display','block');
                fileInput = file;
                // setTimeout("document.getElementById('progress_bar').className='';", 2000);
            };

            // Read in the image file as a binary string.
            reader.readAsBinaryString(file);
        });

        function checkInvalidFile() {
            if (fileInput != '') {
                var type = fileInput.name.split('.').pop();
                if (type == 'csv') {
                    return true;
                }
            }
            document.getElementById('error-message').innerHTML = 'Invalid File. Please upload csv file again.';
            setTimeout(function () {
                document.getElementById('error-message').innerHTML = '';
            }, 2000);
            return false;
        }
        function updateProgress(evt) {
            // evt is an ProgressEvent.
            if (evt.lengthComputable) {
                var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
                // Increase the progress bar length.
                if (percentLoaded < 100) {
                    progress.style.width = percentLoaded + '%';
                    progress.textContent = percentLoaded + '%';
                }
            }
        }
        function errorHandler(evt) {
            switch(evt.target.error.code) {
                case evt.target.error.NOT_FOUND_ERR:
                    alert('File Not Found!');
                    break;
                case evt.target.error.NOT_READABLE_ERR:
                    alert('File is not readable');
                    break;
                case evt.target.error.ABORT_ERR:
                    break; // noop
                default:
                    alert('An error occurred reading this file.');
            };
        }
    });
</script>
</body>
</html>
