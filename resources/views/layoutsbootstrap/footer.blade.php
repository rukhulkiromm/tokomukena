            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Toko Mukena 2023</span>
                    </div>
                </div>
            </footer>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ url('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- untuk update list keranjang dan list invoice -->
    <script>
        
        function refreshkeranjang(){
            $.ajax(
                {
                    type: "GET",
                    url: "{{url('penjualan/jmlbarang')}}",
                    dataType: "json",
                    success: function (response) {
                        // update informasi jumlah barang di icon keranjang
                        $('#xjmlisikeranjang').html(response.jumlah);
                    }
                }
            )
        }

        function refreshlistkeranjang(){
            $.ajax(
                {
                    type: "GET",
                    url: "{{url('penjualan/keranjangjson')}}",
                    dataType: "json",
                    success: function (response) {
                        $('#xisikeranjang').html("");
                        $.each(response.keranjang, function (key, item) {
                            var urlgambar = "{{url('barang/')}}";
                            var urlgambarfix = urlgambar.concat("/",item.foto);

                            $('#xisikeranjang').append('<a class="dropdown-item d-flex align-items-center" href="#">\
                                <div class="mr-3">\
                                    <img width="50px" height="50px" id="x-2" src="' + urlgambarfix + '" zn_id="79">\
                                </div>\
                                <div>\
                                <div class="small text-gray-500">'+item.tgl_transaksi+'</div>\
                                    <span class="font-weight-bold">'+item.nama_barang+' ('+item.jml_barang+' biji)<br>Rp '+number_format(item.total)+'</span>\
                                </div>\
                                </a>\
                            \</tr>');
                            // $('#xisikeranjang').html(isihtml);
                        });
                    }
                }
            )
        }

        function refreshinvoice(){
            $.ajax(
                {
                    type: "GET",
                    url: "{{url('penjualan/invoice')}}",
                    dataType: "json",
                    success: function (response) {
                        $('#xisiinvoice').html("");
                        $.each(response.invoice, function (key, item) {
                            var urlgambar = "{{asset('img/undraw_profile_1.svg')}}";
                            $('#xisiinvoice').append('<a class="dropdown-item d-flex align-items-center" href="#">\
                                    <div class="dropdown-list-image mr-3">\
                                        <img class="rounded-circle" src="'+urlgambar+'"\
                                            alt="...">\
                                        <div class="status-indicator bg-success"></div>\
                                    </div>\
                                    <div class="font-weight-bold">\
                                        <div class="text-truncate">Rp '+number_format(item.total_harga)+'</div>\
                                        <div class="small text-gray-500">'+item.no_transaksi+'</div>\
                                    </div>\
                                \</a>');
                        });
                    }
                }
            )
        }

        function refreshjmlinvoice(){
            $.ajax(
                {
                    type: "GET",
                    url: "{{url('penjualan/jmlinvoice')}}",
                    dataType: "json",
                    success: function (response) {
                        // update informasi jumlah barang di icon keranjang
                        $('#xjmlinvoice').html(response.jmlinvoice);
                    }
                }
            )
        }

        refreshlistkeranjang();
        refreshinvoice();
        refreshjmlinvoice();

    </script>
    <!-- akhir update list keranjang dna list invoice -->

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>

    <!-- Page level plugins -->
    <script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('js/demo/chart-area-demo.js')}}"></script>
    <!-- <script src="{{asset('js/demo/chart-pie-demo.js')}}"></script> -->

    <!-- Datatables plugin -->
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script src="{{asset('js/demo/datatables-demo.js')}}"></script>

    <!-- js untuk select2  -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#jenis_dok").select2();
            });
        </script>
    
    <!-- fancy box -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

</body>

</html>