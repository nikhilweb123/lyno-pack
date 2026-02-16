<?php $activePage = 'gallery'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Gallery – Lynopack</title>
  <meta name="description" content="Explore Lynopack's product gallery featuring our packaging and coding solutions.">
  <meta name="keywords" content="Lynopack, Gallery, Packaging Machines, Coding Machines, Industrial Solutions, Photos">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">

  <style>
    .gallery.section {
      padding-top: 80px; /* Added for localized styling */
    }
  </style>

</head>

<body class="blog-page">

  <?php include __DIR__ . '/header.php'; ?>

  <main class="main">

    <section class="page-title bg-section-light" style="margin-top: 100px;">
      <div class="container mb-5">
        <div class="section-title">
          <h2>Our Gallery</h2>
          <p>Explore our diverse range of packaging and coding solutions.</p>
        </div>
      </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">
      <div class="container" data-aos="fade-up">
        <div class="row gy-4 justify-content-center">

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery-1.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery-1.jpg" title="Gallery 1" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery-2.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery-2.jpg" title="Gallery 2" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery-3.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery-3.jpg" title="Gallery 3" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery-4.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery-4.jpg" title="Gallery 4" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery-5.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery-5.jpg" title="Gallery 5" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery-6.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery-6.jpg" title="Gallery 6" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="gallery-item h-100">
              <img src="../assets/gallery/gallery.jpg" class="img-fluid" alt="" loading="lazy">
              <div class="gallery-links d-flex align-items-center justify-content-center">
                <a href="../assets/gallery/gallery.jpg" title="Main Gallery Image" class="glightbox preview-link"><i class="bi bi-arrows-angle-expand"></i></a>
              </div>
            </div>
          </div><!-- End Gallery Item -->

        </div>
      </div>
    </section><!-- End Gallery Section -->

  </main>

  <?php include __DIR__ . '/footer.php'; ?>

</body>

</html>
