<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
    body {
        /* background-image: url('{{ asset('images/background.png') }}');
        background-size: 150%;
        background-position: center;
        background-repeat: no-repeat; */
    }
        .container {
            display: flex; /* Menggunakan flexbox untuk layout */
        }
        main {
            flex: 1; /* Konten utama mengambil sisa ruang */
            padding: 20px;
            
        }
        aside {
            width: 250px; /* Lebar sidebar */
            background-color: #e3d5c2; /* Warna background sidebar */
            padding: 20px;
            height: 100vh; /* Mengisi tinggi viewport */
            position: fixed; /* Mengunci sidebar di posisi tetap */
        }
        header {
            background-color: #c0b69d; /* Warna background header */
            padding: 20px;
            position: fixed; /* Mengunci header di posisi tetap */
            width: 100%;
            z-index: 1000; /* Pastikan header di atas konten lain */
        }
        header h1 {
            display: inline; /* Menampilkan judul di samping logo */
            margin-left: 10px;
        }
        header nav ul {
            list-style-type: none; /* Menghapus bullet point */
            padding: 0;
            display: flex; /* Menggunakan flex untuk menampilkan item secara horizontal */
        }
        header nav ul li {
            margin-right: 20px; /* Jarak antar item menu */
        }
        footer {
            background-color: #c0b69d; /* Warna background footer */
            padding: 10px;
            text-align: center;
            position: relative; /* Memastikan footer berada di bawah konten */
            clear: both; /* Menghindari konflik dengan elemen lain */
        }
    </style>
</head>
<body>
    @include('header') <!-- Menyertakan header -->

    <div class="container">

        <main>
            @yield('content') <!-- Konten utama halaman -->
        </main>
    </div>

    @include('footer') <!-- Menyertakan footer -->
</body>
</html>
