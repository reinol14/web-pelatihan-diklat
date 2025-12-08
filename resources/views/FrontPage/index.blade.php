<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Bidang PSDM - BKPSDM Kota Surakarta</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{asset('FrontPage/assets/img/favicon.png')}}" rel="icon">
  <link href="{{asset('FrontPage/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('FrontPage/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('FrontPage/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('FrontPage/assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('FrontPage/assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('FrontPage/assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('FrontPage/assets/css/main.css')}}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: FrontPage
  * Template URL: https://bootstrapmade.com/FrontPage-multipurpose-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="{{ asset('FrontPage/assets/img/logo.png')}}" alt=""> -->
        <h1 class="sitename">Bidang PSDM</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>

        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

          @auth('web')
            <a class="btn-getstarted" href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">Logout (Admin)</a>
            <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          @elseif(auth()->guard('pegawais')->check())
            <a class="btn-getstarted" href="{{ route('pegawai.logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form-pegawai').submit();">Logout (Pegawai)</a>
            <form id="logout-form-pegawai" action="{{ route('pegawai.logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
             @endauth
  


    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <img src="{{ asset('FrontPage/assets/img/hero-bg-abstract.jpg')}}" alt="" data-aos="fade-in" class="">

      <div class="container">
        <div class="row content-center" data-aos="zoom-out">
          <div class="row gy-3 mt-5">

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="100">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-motherboard"></i></div>
                  <h4 class="title">
                  </a>BROSUR PELATIHAN</a>
                  </h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="200">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-gem"></i></div>
                  <h4 class="title">E-KATALOG PELATIHAN</h4>
                      PELATIHAN</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-folder2"></i></div>
                  <h4 class="title"></h4>DIREKTORY PELATIHAN</h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="300">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-person-workspace"></i></div>
                  <h4 class="title"><a href="https://e-learning.surakarta.go.id/elearning/solowasis"
                      target="_blank">SOLOWASIS</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-command"></i></div>
                  <h4 class="title"><a href="">IZIN PENGEMBANGAN KOMPETENSI OPD</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-journal-arrow-up"></i></div>
                  <h4 class="title"><a href="">IP ASN</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-file-earmark-pdf"></i></div>
                  <h4 class="title"><a href="">SERTIFIKAT PELATIHAN</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-people-fill"></i></div>
                  <h4 class="title"><a href="">PBJ</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-person-fill-gear"></i></div>
                  <h4 class="title">
                    PERENCANAAN-AKPK</a>
                </h4>                
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-person-fill-add"></i></div>
                  <h4 class="title"><a onclick="window.location='{{ route('Pelatihan.index') }}'">PELATIHAN</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->


            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-person-lines-fill"></i></div>
                  <h4 class="title"><a href="">ALUMNI PELATIHAN</a></h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->

            <div class="col-md-6 col-lg-3" data-aos="zoom-out" data-aos-delay="400">
              <div class="icon-box">
                <center>
                  <div class="icon"><i class="bi bi-stickies"></i></div>
                  <h4 class="title">EVALUASI PASCA DIKLAT</h4>
                </center>
              </div>
            </div>
            <!--End Icon Box -->



          </div>
        </div>

    </section><!-- /Hero Section -->



  </main>

  <footer id="footer" class="footer light-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Bidang PSDM</span>
          </a>
          <p>BKPSDM - Pemerintah Kota Surakarta</p>
          <div class="social-links d-flex mt-4">
            <a href="https://twitter.com/BkpsdmSurakarta/"><i class="bi bi-twitter-x"></i></a>
            <a href="https://facebook.com/bkpsdmsurakarta/"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/bkpsdmsurakarta/"><i class="bi bi-instagram"></i></a>
            <a href="https://youtube.com/@bkpsdmkotasurakarta8912/"><i class="bi bi-youtube"></i></a>

          </div>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4></h4>
          <ul>

          </ul>
        </div>

        <div class="col-lg-2 col-6 footer-links">
          <h4>Bidang PSDM</h4>
          <ul>
            <li>Menyelenggaraan kebijakan daerah : </li>
            <p>terkait pengembangan Kompetensi Teknis, Manajerial dan Fungsional</p>

          </ul>
        </div>



        <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
          <h4>Contact Us</h4>
          <p>Jl. Jend. Sudirman No.2, Kp. Baru, Kec. Ps. Kliwon, Kota Surakarta</p>
          <p>Jawa Tengah</p>
          <p>57111</p>
          <p class="mt-4"><strong>Phone:</strong> <span>(0271) 642020 </span></p>

        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">BKPSDM</strong> <span>Kota Surakarta Reserved</span></p>

    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('FrontPage/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/aos/aos.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{ asset('FrontPage/assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{ asset('FrontPage/assets/js/main.js')}}"></script>

</body>

</html>