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
            padding: 30px;
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
            margin-top: 50px;
            min-width: 900px;
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
        .alert {
            margin: 10px 50px 0;
        }
        .preview-email {
            display: none;
            bottom: 10px !important;
            right: 10px;
            position: absolute !important;
            overflow-y: hidden;
            width: 400px;
            z-index: 99;
            background: #f9f7c9;
            border: 1px solid #ccc;
            border-radius-topleft: 5px;
            border-radius-topright: 5px;
            -moz-border-radius-topleft: 5px;
            -moz-border-radius-topright: 5px;
            box-shadow: 5px 10px #888888;
        }
        .preview-email div {
            padding: 10px;
        }
        .popup-header {
            border-bottom: 1px solid;
        }
        .popup-header h5 {
            color: #ccc;
        }
        .popup-header span {
            margin-left: 10px;
        }
        .body-email {
            margin-bottom: 20px;
        }
        .close {
            position: absolute;
            top: 0;
            right: 5px;
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
            <h1 class="primary-header">Setting</h1>
            @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    {{ Session::get('flash_message') }}
                </div>
            @endif
            <div class="notice secondaryTextColor">
                {{ Form::model($setting, array('route' => array('setting.update', $setting->id), 'method' => 'PUT')) }}

                <div class="form-group">
                    {{ Form::label('admin_email', 'Sender Email') }}
                    {{ Form::email('admin_email', null, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('owner_name', 'Sender Name') }}
                    {{ Form::text('owner_name',null, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('email_subject', 'Email Subject') }}
                    {{ Form::text('email_subject',null, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {!! Form::label('email_template', 'Email HTML Content') !!}
                    {!! Form::textarea('email_template',null, ['class' => 'form-control']) !!}
                </div>
                {{ Form::button('Preview', array('class' => 'btn btn-primary preview')) }}
                {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<div class="preview-email">
    <div class="popup-header">
        <h5>From email:</h5><span class="from-email">dasdasda</span>
    </div>
    <div class="popup-header">
        <h5>Sender:</h5><span class="from-name">Luca</span>
    </div>
    <div class="popup-header">
        <h5>Subject:</h5><span class="subject">ANC</span>
    </div>
    <div class="body-email">
    </div>
    <button class="close">x</button>
</div>
<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
<script>
$(function() {
    $('.preview').on('click', function (e) {
        $('.preview-email').css('display','block');
        $('.from-email').html($('#admin_email').val());
        $('.from-name').html($('#owner_name').val());
        $('.subject').html($('#email_subject').val());
        $('.body-email').html($('#email_template').val());
    });
    $('.close').on('click', function (e) {
        $('.preview-email').css('display','none');
    })
});
</script>
</body>
</html>
