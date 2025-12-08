<!-- resources/views/footer.blade.php -->
<div class="page-container">
    <!-- Konten utama -->
    <div class="content">
        <!-- Isi konten halaman -->
    </div>

    <!-- Footer -->
    <footer style="background-color: #a09172; ">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">

            <!-- Bagian Logo Surakarta dan BKPSDM -->
            <div class="footer-logo" style="display: flex; align-items: center; margin-left:650px;">
                <!-- Logo Surakarta di sebelah kiri -->
                <img src="{{ asset('images/surakarta.png') }}" alt="Logo Surakarta" style="max-width: 40px; margin-right: 20px;">
                <!-- Logo Surakarta -->
            
                <!-- Logo BKPSDM di sebelah kanan logo Surakarta -->
                <div style="text-align: center;" >
                    <img src="{{ asset('images/bkpsdm.png') }}" alt="Logo BKPSDM" style="max-width: 80px;"> <!-- Logo BKPSDM -->
                    <p class="footer-title" style="color: #000000">KOTA SURAKARTA</p>
                </div>
            </div>

            <!-- Bagian Informasi Kontak -->
            <div class="footer-info" style="color: white;">
                <p><i class="fa fa-map-marker"></i> Balaikota Surakarta</p>
                <p><i class="fa fa-phone"></i> (0271) 0000000</p>
                <p><i class="fa fa-envelope"></i> bkpsdm@surakarta.go.id</p>
            </div>
        </div>

        <div class="copyright" style="margin-top: 5px; border-top: 1px solid #fff; padding-top: 10px; text-align: center;">
            <p>&copy;
                {{ date('Y') }} BKPSDM Kota Surakarta. All rights reserved.
            </p>
        </div>
    </footer>
</div>



<style>
/* Atur tinggi seluruh halaman */
html, body {
    height: 100%;
    margin: 0px;
    padding: 0px;
    display: flex;
    flex-direction: column;
    overflow-x: hidden; /* Mencegah scroll horizontal */
    overflow-y: auto; /* Mencegah scroll horizontal */
}

/* Wrapper untuk seluruh halaman */
.page-container {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.content {
    flex: 1;
    align-items: center;
}

footer {
    background-color: #a09172;
    padding: 1px;
    color: whiteblac;
}

.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Menggeser logo dan teks agak ke kanan */
.footer-logo {
    display: flex;
    align-items: center;
    margin-left: 80px; /* Tambahkan margin untuk menggeser ke kanan */
}

.footer-logo img {
    max-width: 100px;
}

.footer-title {
    text-transform: uppercase;
    font-family: "Futura", sans-serif;
    font-size: 10px; /* Membesarkan ukuran teks */
    color: black; /* Mengubah warna teks menjadi hitam */
    margin-top: -10px; /* Menambah jarak atas untuk posisi yang lebih baik */
}

.footer-info p {
    margin: 5px 0;
    text-align: right;
    font-family: Arial, sans-serif;
    font-size: 14px;
    margin-right: -300px;
}

/* Icon styling */
.footer-info i {
    margin-right: 0px;
}

.copyright {
    margin-top: 20px;
    border-top: 1px solid #fff;
    padding-top: 10px;
    text-align: center;
    font-family: Arial, sans-serif;
    font-size: 12px;
}



</style>
