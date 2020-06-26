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
    <link rel="shortcut icon" href="{{asset('/images/favicon.png')}}">

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
  
    <!-- stylesheets-->
    <!-- Bootstrap CSS link start  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" >
    <!-- Bootstrap CSS link end  -->

    <!-- JavaScript links-->
    
</head>

<body>
    <!-- header -->
    @include('layouts.header')
    <div class="main-content">
        <div class="container-fluid pt-4">
           
                    @yield('content')
          
        </div>
    </div>

    <!-- footer -->
    @include('layouts.footer')


    <!-- Custom scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @yield('script-content')
</body>
</html>
