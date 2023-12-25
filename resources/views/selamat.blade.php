<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang</title>
</head>
<body>
    Hello World <br>
    Selamat Datang {{ $nama }} <br>
    <hr>
    Mencoba memanggil fungsi PHP di blade <br>
    Tanggal sekarang {{ date("Y-d-m H:i:s") }} <br>
    <b>Fungsi date di atas adalah fungsi PHP yang langsung bisa dipanggil tanpa perlu menambahkan tag PHP</b>
    <hr>
    Menampilkan nama dalam huruf besar {{ strtoupper($nama) }}
</body>
</html>