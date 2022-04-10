@extends('layout.defaut')

@section('body')

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            @include('layout.sidebar')
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    @include('layout.topbar')
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                @include('layout.footer')
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->


        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('./public/asset/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('./public/asset/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('./public/asset/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('./public/asset/js/sb-admin-2.min.js') }}"></script>

        <!-- Page level plugins -->
        <script src="{{ asset('./public/asset/vendor/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('./public/bower_components/bootstrap-sweetalert/dist/sweetalert.js') }}"></script>
        <!-- Page level custom scripts -->
        <script src="{{ asset('./public/asset/vendor/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('./public/asset/js/demo/chart-pie-demo.js') }}"></script>
        <script src="{{ asset('./public/asset/plugins/DataTables/datatables.min.js') }}"></script>

    </body>

@endsection

