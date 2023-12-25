@include('layoutsbootstrap/header')

@include('layoutsbootstrap/sidebar')

<!-- Page level plugins -->
<script src="{{asset('vendor/chart.js/Chart.min.js')}}"></script>

@yield('konten')                

@include('layoutsbootstrap/footer')