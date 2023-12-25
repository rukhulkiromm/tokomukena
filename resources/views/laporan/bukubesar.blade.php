@extends('layoutbootstrap')

@section('konten')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pendapatan (Bulanan)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 40,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan (Tahunan)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 21   5,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Transaksi
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">500</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Perlu Diproses</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->

    <!-- Alert success -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif
    <!-- Akhir alert success -->

    <div class="row">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="card shadow mb-4">
                
                <!-- Card Header - Dropdown -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Buku Besar</h6>

                </div>
                <!-- Card Body -->
                <div class="card-body">
                        <div class="chart-area" hidden>
                            <canvas id="myAreaChart"></canvas>
                        </div>

                    <!-- Filter Periode Jurnal -->
                    <div class="card">
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-3">Pilih Periode</div>
                                    <div class="col-sm-9"><input type="month" class="form-control" name="periode" id="periode"></div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-3">Pilih Akun</div>
                                    <div class="col-sm-9">
                                        <select name="id_akun" id="id_akun" class="form-control" onchange="proses()" required>
                                            <option value="" disabled selected>- - - Pilih Akun - - -</option>
                                            @foreach ($akun as $ak)                   
                                                <option value="{{$ak->kode_akun}}-{{$ak->nama_akun}}">{{$ak->nama_akun}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Akhir Filter Periode Jurnal -->
                    <br>
                    <!-- Awal Tabel Jurnal -->
                    <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12" style="background-color:white;" align="center">
                                        <div id="xperusahaan"></div>
                                    </div>
                                    <div class="col-sm-12" style="background-color:white;" align="center">
                                        <div id="xbukubesar"></div>
                                    </div>
                                    <div class="col-sm-12" style="background-color:white;" align="center">
                                        <div id="xperiode"></div>
                                    </div>
                                </div>
                                <br>
                                <div class="responsive-table-plugin">
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive" data-pattern="priority-columns">
                                            <table id="report" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                <tr class="bg-dark-light">
                                                    <th rowspan="2">Tanggal</th>
                                                    <th rowspan="2">Nama Akun</th>
                                                    <th rowspan="2" class="text-center">Debet</th>
                                                    <th rowspan="2" class="text-center">Kredit</th>
                                                    <th colspan="2" class="text-center">Saldo </th>
                                                </tr>
                                                <tr class="bg-dark-light">
                                                    <th class="text-center">Debet</th>
                                                    <th class="text-center">Kredit</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <!-- Akhir Tabel Jurnal -->


                </div>
            </div>
        </div>

        
    </div>

    <!-- Proses Jurnal -->
    <script>
        // fungsi number format
        function number_format (number, decimals, decPoint, thousandsSep) { 
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
            var n = !isFinite(+number) ? 0 : +number
            var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
            var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
            var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
            var s = ''

            var toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec)
            return '' + (Math.round(n * k) / k)
                .toFixed(prec)
            }

            // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
            if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
            }
            if ((s[1] || '').length < prec) {
            s[1] = s[1] || ''
            s[1] += new Array(prec - s[1].length + 1).join('0')
            }

            return s.join(dec)
        }

        // fungsi untuk merubah format YYYY-MM menjadi Bulan Tahun
        function rubah(periode){
            // dapatkan tahun
            var tahun = periode.substring(0, 4);
            var bulan = periode.substring(5);
            switch (bulan) {
                case '01':
                    bln = "Januari";
                    break;
                case '02':
                    bln = "Februari";
                    break;
                case '03':
                    bln = "Maret";
                    break;
                case '04':
                    bln = "April";
                    break;
                case '05':
                    bln = "Mei";
                    break;
                case '06':
                    bln = "Juni";
                    break;
                case '07':
                    bln = "Juli";
                    break;
                case '08':
                    bln = "Agustus";
                    break;
                case '09':
                    bln = "September";
                    break;
                case '10':
                    bln = "Oktober";
                    break;
                case '11':
                    bln = "November";
                    break;
                case '12':
                    bln = "Desember";
                    break;
            }
            var hasil = bln.concat(" ",tahun)
            return hasil;
        }

        // fungsi untuk memproses perubahan nilai pada elemen input
        function proses(){
            
            // ambil nilai month dan year dari elemen input dalam format YYYY-MM
            var periode = document.getElementById("periode").value;
            var akun = document.getElementById("id_akun").value; //format 111-Kas
            var position = akun.search("-"); //mencari posisi indeks "-"
            var idakun = akun.substring(0,position); //mendapatkan id akun
            var namaakun = akun.substring(position+1); //mendapatkan namakun
            var periode_tampil = rubah(periode);
            var url = "{{url('jurnal/viewdatabukubesar/')}}";
            var url2 = url.concat("/",periode,"/",idakun);
            // console.log(pilihan);
            $.ajax({
                type: "GET",
                url: url2,
                success: function (response) {
                    // console.log(response);
                    if (response.status == 404) {
                        // beri alert kalau gagal
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'warning',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        console.log(response);
                        // xperusahaan
                        var tebal = "<b>";
                        var namaperusahaan = response.perusahaan.nama_perusahaan;
                        var akhirtebal = "</b>";
                        document.getElementById("xperusahaan").innerHTML = tebal.concat(namaperusahaan,akhirtebal);

                        //xperiode 
                        var awalanperiode = "Periode ";
                        document.getElementById("xperiode").innerHTML = tebal.concat(awalanperiode,periode_tampil,akhirtebal);

                        // xbukubesar
                        var awalanbukubesar = "Buku Besar ";
                        // console.log(tebal.concat(awalanbukubesar,namaakun,akhirtebal));
                        document.getElementById("xbukubesar").innerHTML = tebal.concat(awalanbukubesar,namaakun,akhirtebal);


                        // mengisi tabel
                        $('tbody').html("");
                        // untuk saldo awal
                        if(response.posisi=='d'){
                            $('tbody').append('<tr>\
                                <td>-</td>\
                                <td><b>Saldo Awal</b></td>\
                                <td>-</td>\
                                <td>-</td>\
                                <td class="text-right">Rp ' + number_format(response.saldoawal) + '</td>\
                                <td class="text-right">-</td>\
                                \</tr>');
                            var saldo_debet = response.saldoawal;
                            var saldo_kredit = 0;
                        }else{
                            $('tbody').append('<tr>\
                                <td>-</td>\
                                <td><b>Saldo Awal</b></td>\
                                <td>-</td>\
                                <td>-</td>\
                                <td class="text-right">-</td>\
                                <td class="text-right">Rp ' + number_format(response.saldoawal) + '</td>\
                                \</tr>');
                            var saldo_debet = 0;
                            var saldo_kredit =  response.saldoawal;
                        }
                        
                        // untuk isi tabel
                        var d = 0;
                        var c = 0
                        $.each(response.bukubesar, function (key, item) {
                            var tgljurnal = item.tgl_jurnal.substring(0, 10); //YYYY-MM-DD
                            
                            if((response.posisi=='d')&&(item.posisi_d_c=='d')){
                                saldo_debet = saldo_debet  + item.nominal;
                                d = d + item.nominal;
                                $('tbody').append('<tr>\
                                    <td class="text-center">' + tgljurnal + '</td>\
                                    <td>' + item.nama_akun + '</td>\
                                    <td class="text-right">Rp ' + number_format(item.nominal) + '</td>\
                                    <td></td>\
                                    <td class="text-right">Rp ' + number_format(saldo_debet) + '</td>\
                                    <td class="text-right">-</td>\
                                    \</tr>');
                            }else if((response.posisi=='d')&&(item.posisi_d_c=='c')){
                                saldo_debet = saldo_debet  - item.nominal;
                                c = c + item.nominal;
                                $('tbody').append('<tr>\
                                    <td class="text-center">' + tgljurnal + '</td>\
                                    <td>' + item.nama_akun + '</td>\
                                    <td></td>\
                                    <td class="text-right">Rp ' + number_format(item.nominal) + '</td>\
                                    <td class="text-right">Rp ' + number_format(saldo_debet) + '</td>\
                                    <td class="text-right">-</td>\
                                    \</tr>');
                            }else if((response.posisi=='c')&&(item.posisi_d_c=='d')){
                                saldo_kredit = saldo_kredit  - item.nominal;
                                c = c + item.nominal;
                                $('tbody').append('<tr>\
                                    <td class="text-center">' + tgljurnal + '</td>\
                                    <td>' + item.nama_akun + '</td>\
                                    <td class="text-right">Rp ' + number_format(item.nominal) + '</td>\
                                    <td></td>\
                                    <td class="text-right"></td>\
                                    <td class="text-right">Rp ' + number_format(saldo_kredit) + '</td>\
                                    \</tr>');
                            }else if((response.posisi=='c')&&(item.posisi_d_c=='c')){
                                saldo_kredit = saldo_kredit  + item.nominal;
                                c = c + item.nominal;
                                $('tbody').append('<tr>\
                                    <td class="text-center">' + tgljurnal + '</td>\
                                    <td>' + item.nama_akun + '</td>\
                                    <td></td>\
                                    <td class="text-right">Rp ' + number_format(item.nominal) + '</td>\
                                    <td class="text-right"></td>\
                                    <td class="text-right">Rp ' + number_format(saldo_kredit) + '</td>\
                                    \</tr>');
                            }
                        });

                        // footer saldo akhir
                        $('tbody').append('<tr>\
                            <td>-</td>\
                            <td><b>Saldo Akhir</b></td>\
                            <td class="text-right">-</td>\
                            <td class="text-right">-</td>\
                            <td class="text-right">Rp ' + number_format(saldo_debet) + '</td>\
                            <td class="text-right">Rp ' + number_format(saldo_kredit) + '</td>\
                            \</tr>');
                    }
                }
            });
        }
    </script>
    <!-- Akhir Proses Jurnal -->
    <!-- /.container-fluid -->
</div>
<!-- End of Main Content -->


@endsection