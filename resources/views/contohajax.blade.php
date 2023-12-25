<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title>Contoh AJAX dan DOM</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/navbar-bottom/">

    

    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
    </style>


    
  </head>
  <body>
    
<main class="container">
  <div class="bg-light p-5 rounded mt-3">
    <h1>Contoh AJAX</h1>
    <p class="lead">Contoh implementasi AJAX sederhana Untuk Load Data</p>
  </div>

  <div class="bg-light p-5 rounded mt-3">
  <button type="button" class="btn btn-primary" onclick="myFunction()">Proses</button>
  <button type="button" class="btn btn-success" onclick="hapusisitabel()">Kembalikan</button>
  <br><br>
     <!-- Awal Dari Tabel -->
     <div class="table-responsive">
                        <!-- Untuk tempat menaruh tabel -->
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                </tr>
                            </thead>
                            <tfoot class="thead-dark">
                                <tr>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                
                            </tbody>
                        </table>  
                    </div>
                    <!-- Akhir Dari Tabel -->
  </div>

</main>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

<!-- Untuk proses pengisian tabel ketika tombol proses ditekan melalui fungsi myFunction -->
<script>
function myFunction() {
    // console.log('tombol ditekan');
    $(document).ready(function(){
            datacontohform();
            }
    );
    // console.log('tombol sudah ditekan');
}
</script>

<!-- Untuk proses menghapus isi tabel ketika tombol kembalikan melalui fungsi hapusisitabel-->
<script>
function hapusisitabel() {
    // console.log('tombol hapus ditekan');
    $(document).ready(function(){
        // temukan elemen yg mengandung tbody kemudian update isinya menjadi kosong
        $('tbody').html("");
      }
    );
    // console.log('tombol hapus sudah ditekan');
}
</script>

<!-- Proses mengisi data pada tabel -->
<script>
        function datacontohform(){
            $.ajax(
                {
                    type: "GET",
                    url: "{{url('contoh1/fetchcoa')}}",
                    dataType: "json",
                    success: function (response) {
                        // console.log(response);
                        $('tbody').html("");
                        $.each(response.coas, function (key, item) {
                            $('tbody').append('<tr>\
                                <td>' + item.kode_akun + '</td>\
                                <td>' + item.nama_akun + '</td>\
                            \</tr>');
                        });
                    }
                }
            )
        }
        
    </script>


<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

      
  </body>
</html>
