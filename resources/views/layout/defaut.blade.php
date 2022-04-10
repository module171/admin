<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Custom fonts for this template-->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('./public/bower_components/font-awesome-5/css/all.min.css') }}">

    <!-- Custom styles for this template-->
    <link href="{{ asset('./public/asset/css/sb-admin-2.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('./public/bower_components/bootstrap-sweetalert/dist/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('./public/asset/plugins/DataTables/datatables.min.css') }}">
</head>

@yield('body')

@section('script')

@show

</html>
