<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- title -->
    <title>@yield('title') | Laravel Skill Test</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/favicon.png')}}">

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
  
    <!-- stylesheets-->
    <!-- fontawesome link start  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <!-- fontawesome link end  -->
    <!-- Bootstrap CSS link start  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
    <!-- Bootstrap CSS link end  -->

    <!-- JavaScript links-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- header -->
    @include('layouts.header')
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mt-3 admin-content">

                    @yield('content')

                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    @include('layouts.footer')


    <!-- Custom scripts -->
    @yield('script-content')
</body>
</html>
