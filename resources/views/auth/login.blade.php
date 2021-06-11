<!DOCTYPE html>
<!--
* CoreUI Free Laravel Bootstrap Admin Template
* @version v2.0.1
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->

<html lang="en">
<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>CoreUI Free Bootstrap Admin Template</title>
    <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Icons-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/free.min.css') }}" rel="stylesheet"> <!-- icons -->
    <link href="{{ asset('css/flag-icon.min.css') }}" rel="stylesheet"> <!-- icons -->
    <!-- Main styles for this application-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('css')
    <link href="{{ asset('css/coreui-chartjs.css') }}" rel="stylesheet">
</head>
<body class="c-app flex-row align-items-center" cz-shortcut-listen="true">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">
                        <h1>Login</h1>
                        {{ Form::open(array('url' => 'login')) }}
                        <p>
                            {{ $errors->first('email') }}
                            {{ $errors->first('password') }}
                        </p>
                        <p class="text-muted">Sign In to your account</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
</svg></span></div>
                            {{ Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com', 'class' => 'form-control')) }}
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
</svg></span></div>
                            {{ Form::password('password', array('class' => 'form-control')) }}
                        </div>
                        <div class="row">
                            <div class="col-6">
                                {{ Form::submit('Login!', array('class' => 'btn btn-primary px-4')) }}
                            </div>
                            <div class="col-6 text-right">
                                <button class="btn btn-link px-0" type="button">Forgot password?</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="card text-white bg-primary d-md-down-none" style="width:44%">
                    <div class="card-body text-center">
                        <div class="card-body p-4">
                            {{ Form::open(array('url' => 'register')) }}
                            <h1>Register</h1>
                            <p class="text-muted">Create your account</p>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
</svg></span></div>
                                {{ Form::text('name', Input::old('name'), array('placeholder' => 'Name', 'class' => 'form-control')) }}
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-envelope-open"></use>
</svg></span></div>
                                {{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'form-control')) }}
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
</svg></span></div>
                                {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) }}
                            </div>
                            <div class="input-group mb-4">
                                <div class="input-group-prepend"><span class="input-group-text">
<svg class="c-icon">
<use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
</svg></span></div>
                                {{ Form::password('password_confirmation', array('placeholder' => 'Repeat password', 'class' => 'form-control')) }}
                            </div>
                            {{ Form::submit('Create Account!', array('class' => 'btn btn-block btn-success')) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vendors/@coreui/coreui-pro/js/coreui.bundle.min.js"></script>
<!--[if IE]><!-->
<script src="vendors/@coreui/icons/js/svgxuse.min.js"></script>
<!--<![endif]-->
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        setTimeout(function() {
            document.body.classList.remove('c-no-layout-transition')
        }, 2000);
    });
</script>

</body>