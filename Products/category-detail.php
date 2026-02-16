<?php
$categoryId = $_GET['id'] ?? '';
$groupSlug = $_GET['group'] ?? '';
$jsonData = json_decode(file_get_contents(__DIR__ . '/../data.json'), true);
$category = $jsonData['categories'][$categoryId] ?? null;
$navigation = $jsonData['navigation'] ?? [];
$allProducts = $jsonData['products'] ?? [];

if (!$category) {
    header("HTTP/1.0 404 Not Found");
    echo "Category not found";
    exit;
}

// Find parent group if not passed via URL
if (empty($groupSlug)) {
    foreach ($navigation as $gSlug => $group) {
        if (in_array($categoryId, $group['subcategories'])) {
            $groupSlug = $gSlug;
            break;
        }
    }
}

$groupName = $navigation[$groupSlug]['name'] ?? 'Products';

// Helper functions
function getProductUrlCat($productId, $categorySlug, $navigation)
{
    foreach ($navigation as $groupSlug => $group) {
        if (in_array($categorySlug, $group['subcategories'])) {
            return '/products/' . $groupSlug . '/' . $categorySlug . '/' . strtolower($productId);
        }
    }
    return '/product/' . $productId;
}

function getCategoryUrlCat($categorySlug, $navigation)
{
    foreach ($navigation as $groupSlug => $group) {
        if (in_array($categorySlug, $group['subcategories'])) {
            return '/products/' . $groupSlug . '/' . $categorySlug;
        }
    }
    return '/category/' . $categorySlug;
}

$activePage = 'products';

// SEO meta
$categoryName = $category['name'] ?? 'Products';
$metaTitle = $categoryName . ' | LYNOPACK Industrial Packaging Solutions';
if (strlen($metaTitle) > 60) {
    $metaTitle = substr($categoryName, 0, 45) . ' | LYNOPACK';
}
$metaDescription = $category['description'] ?? 'Explore our range of ' . $categoryName . '. High-quality industrial packaging machines by LYNOPACK.';
if (strlen($metaDescription) > 155) {
    $metaDescription = substr($metaDescription, 0, 152) . '...';
}

// Breadcrumb data
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/index.php'],
    ['name' => 'Products', 'url' => '#'],
    ['name' => $groupName, 'url' => '#'],
    ['name' => $categoryName, 'url' => getCategoryUrlCat($categoryId, $navigation)]
];

// Category intro content based on category
$categoryIntros = [
    'pallet-stretch-wrapping-machines' => 'LYNOPACK Pallet Stretch Wrapping Machines are engineered to deliver superior load containment and protection for palletized goods. Our comprehensive range includes manual, semi-automatic, and fully automatic models suited for diverse industrial applications — from small warehouses to high-speed automated production lines. Each machine is designed with precision tension control, durable construction, and user-friendly operation to ensure consistent wrapping performance cycle after cycle. Whether you need a cost-effective turntable wrapper or an advanced rotary arm system for heavy and unstable loads, LYNOPACK offers reliable solutions that reduce film consumption, minimize labor requirements, and enhance your packaging efficiency. Our pallet wrappers are trusted by businesses across FMCG, pharmaceuticals, chemicals, logistics, and export packaging industries worldwide.',
    'box-stretch-wrapping-machines' => 'LYNOPACK Box Stretch Wrapping Machines provide compact, efficient wrapping solutions specifically designed for individual boxes, cartons, and smaller packages. Our range includes tabletop semi-automatic models for e-commerce and retail operations, as well as fully automatic inline systems for high-speed fulfillment centers. These machines ensure tight, secure wrapping that protects products during handling and shipping while optimizing film usage. Built for reliability and easy operation, they integrate seamlessly into existing packaging workflows.',
    'orbit-stretch-wrapping-machines' => 'LYNOPACK Orbit Stretch Wrapping Machines are engineered for wrapping long, ring-shaped, coil, and irregularly shaped products. Using advanced orbital ring technology, these machines rotate the film carriage around the product to deliver uniform, secure wrapping without manual intervention. Our range covers horizontal orbital wrappers for pipes and profiles, vertical and horizontal coil wrappers for wire and steel, and high-speed ring wrappers for continuous production lines. Each model is built for industrial durability with adjustable tension, conveyor integration, and safety features.',
    'standard-taping-machines' => 'LYNOPACK Standard Taping Machines deliver reliable carton sealing performance for production lines of all sizes. From manually adjustable models to semi-automatic and fixed-size automatic sealers, our machines ensure uniform, secure tape application on every carton. Designed for ease of use, durability, and consistent performance, they are ideal for general packaging, food, consumer goods, and batch production environments.',
    'autonomous-taping-machines' => 'LYNOPACK Autonomous Taping Machines represent the pinnacle of fully automated carton sealing technology. These machines handle random-size carton detection, automatic flap closing, case erecting, and sealing without any manual intervention. Engineered for high-speed, unmanned packaging operations, they are perfect for e-commerce, 3PL logistics, and complex multi-SKU production lines. With pneumatic control, intelligent size detection, and robust construction, they maximize throughput while minimizing labor costs.',
    'conveyor' => 'LYNOPACK Conveyor Systems provide robust, reliable material handling solutions to streamline your packaging workflow from end to end. Our range includes belt conveyors, cross transfer systems, flexible roller conveyors, pallet turntables, and power roller conveyors — each designed for heavy-duty industrial use. Built with variable speed control, custom length options, and durable construction, our conveyors integrate seamlessly with wrapping, taping, and palletizing equipment.',
    'lift-auto-door-mechanism-s-series' => 'The LYNOPACK JLT S-Series Lift Auto Door Mechanisms deliver smooth, reliable elevator door operation for residential lift applications. Available in centre opening and telescopic configurations for both car and landing installations, the S-Series features VVF drive technology, low noise operation, and easy installation. Designed for durability and safety, these mechanisms ensure trouble-free performance throughout the life of your elevator system.',
    'lift-auto-door-mechanism-f-series' => 'The LYNOPACK JLT F-Series Lift Auto Door Mechanisms are engineered for high-traffic commercial elevator applications. Built with heavy-duty motors, advanced controllers, and robust construction, the F-Series handles continuous operation in demanding environments. Available in centre opening and telescopic configurations, these mechanisms deliver fast, reliable performance with certified safety features and long service life.',
    'lift-auto-door-mechanism-parts' => 'LYNOPACK offers a comprehensive range of genuine spare parts for both JLT S-Series and F-Series lift door mechanisms. Our parts inventory includes motor drives, door shoes, contacts, rollers, belts, and controllers — all manufactured to original specifications for perfect fit and reliable performance. Keeping genuine spare parts ensures your elevator door systems maintain optimal operation and safety compliance.'
];

$categoryIntro = $categoryIntros[$categoryId] ?? $category['description'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo htmlspecialchars($metaTitle); ?></title>
  <meta content="<?php echo htmlspecialchars($metaDescription); ?>" name="description">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://lynopack.com<?php echo getCategoryUrlCat($categoryId, $navigation); ?>">
  
  <!-- Favicons -->
  <link href="/assets/img/favicon.png" rel="icon">
  <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="/assets/css/main.css" rel="stylesheet">

  <!-- JSON-LD BreadcrumbList Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
      <?php foreach ($breadcrumbs as $i => $crumb): ?>
      {
        "@type": "ListItem",
        "position": <?php echo $i + 1; ?>,
        "name": "<?php echo htmlspecialchars($crumb['name']); ?>"<?php if ($crumb['url'] !== '#'): ?>,
        "item": "https://lynopack.com<?php echo $crumb['url']; ?>"<?php
    endif; ?>
      }<?php echo($i < count($breadcrumbs) - 1) ? ',' : ''; ?>
      <?php
endforeach; ?>
    ]
  }
  </script>

  <style>
    .category-hero {
      background: linear-gradient(135deg, #01337e 0%, #0a5cbf 50%, #1a73e8 100%);
      padding: 100px 0 60px;
      color: #fff;
      position: relative;
      overflow: hidden;
    }
    .category-hero::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      opacity: 0.3;
    }
    .category-hero h1 {
      color: #fff;
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
    }
    .category-hero .lead {
      font-size: 1.15rem;
      opacity: 0.9;
      max-width: 600px;
    }

    .breadcrumb-nav {
      background-color: #f8f9fa;
      padding: 15px 0;
      border-bottom: 1px solid #eee;
    }
    .breadcrumb-nav ol {
      margin: 0;
      padding: 0;
      list-style: none;
      display: flex;
      flex-wrap: wrap;
      font-size: 14px;
    }
    .breadcrumb-nav ol li {
      display: flex;
      align-items: center;
    }
    .breadcrumb-nav ol li + li::before {
      content: "\F285";
      font-family: "bootstrap-icons";
      margin: 0 8px;
      color: #999;
      font-size: 10px;
    }
    .breadcrumb-nav ol li a {
      color: var(--accent-color);
      text-decoration: none;
      transition: color 0.2s;
    }
    .breadcrumb-nav ol li a:hover {
      color: var(--nav-hover-color);
      text-decoration: underline;
    }
    .breadcrumb-nav ol li.current {
      color: #666;
    }

    .category-intro {
      padding: 50px 0 30px;
    }
    .category-intro p {
      font-size: 1.05rem;
      line-height: 1.8;
      color: #555;
    }

    .product-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
      border: 1px solid #eee;
      border-radius: 12px;
      overflow: hidden;
    }
    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }
    .product-card img {
      height: 260px;
      object-fit: cover;
      width: 100%;
      transition: transform 0.4s ease;
    }
    .product-card:hover img {
      transform: scale(1.05);
    }
    .product-card .card-body {
      padding: 1.5rem;
    }
    .product-card .card-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #333;
    }
    .product-card .card-text {
      font-size: 0.9rem;
      color: #666;
      line-height: 1.5;
    }
    .product-card .badge-model {
      background: linear-gradient(135deg, #01337e, #0a5cbf);
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .btn-view-product {
      width: 100%;
      border-radius: 8px;
      padding: 10px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-view-product:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(1, 51, 126, 0.3);
    }

    .cta-section {
      background: linear-gradient(135deg, #01337e 0%, #0a5cbf 100%);
      padding: 60px 0;
      color: #fff;
    }
    .cta-section h2 {
      color: #fff;
      font-weight: 700;
    }
    .cta-section .btn-light {
      padding: 12px 35px;
      font-weight: 600;
      border-radius: 8px;
      font-size: 1.05rem;
    }

    .img-overflow-hidden {
      overflow: hidden;
      border-radius: 12px 12px 0 0;
    }
  </style>
</head>
<body class="category-page">

<?php include __DIR__ . '/../header.php'; ?>

<main class="main">
    
    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav mt-5" aria-label="Breadcrumb" style="padding-top: 45px;">
        <div class="container">
            <ol>
                <?php foreach ($breadcrumbs as $i => $crumb): ?>
                    <?php if ($i === count($breadcrumbs) - 1): ?>
                        <li class="current" aria-current="page"><?php echo htmlspecialchars($crumb['name']); ?></li>
                    <?php
    else: ?>
                        <li><a href="<?php echo $crumb['url']; ?>"><?php echo htmlspecialchars($crumb['name']); ?></a></li>
                    <?php
    endif; ?>
                <?php
endforeach; ?>
            </ol>
        </div>
    </nav>

    <!-- Category Hero -->
    <section class="category-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h1><?php echo htmlspecialchars($categoryName); ?></h1>
                    <p class="lead"><?php echo htmlspecialchars($category['description'] ?? ''); ?></p>
                    <a href="/contact.php" class="btn btn-light btn-lg mt-3">
                        <i class="bi bi-envelope-fill me-2"></i>Request a Quote
                    </a>
                </div>
                <div class="col-lg-4 text-center" data-aos="fade-left">
                    <i class="bi bi-box-seam" style="font-size: 8rem; opacity: 0.15;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Introduction -->
    <section class="category-intro">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <h2 class="mb-4">About <?php echo htmlspecialchars($categoryName); ?></h2>
                    <p><?php echo htmlspecialchars($categoryIntro); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <section class="section py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Our <?php echo htmlspecialchars($categoryName); ?> Range</h2>
            <div class="row gy-4">
                <?php if (isset($category['products']) && is_array($category['products'])): ?>
                    <?php foreach ($category['products'] as $index => $prodId): ?>
                        <?php
        $product = $allProducts[$prodId] ?? null;
        if ($product):
            $productUrl = getProductUrlCat($prodId, $categoryId, $navigation);
?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="card product-card">
                                <div class="img-overflow-hidden">
                                    <img src="<?php echo htmlspecialchars($product['image'] ?? '/assets/img/product_image_1.jpeg'); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($prodId . ' ' . $product['title'] . ' by LYNOPACK'); ?>"
                                         loading="lazy">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="badge-model"><?php echo htmlspecialchars($prodId); ?></span>
                                    </div>
                                    <h3 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h3>
                                    <p class="card-text mb-4"><?php echo htmlspecialchars(substr($product['description'], 0, 120)); ?>...</p>
                                    <?php if (!empty($product['features'])): ?>
                                        <ul class="list-unstyled mb-3" style="font-size: 0.85rem; color: #555;">
                                            <?php foreach (array_slice($product['features'], 0, 3) as $feat): ?>
                                                <li><i class="bi bi-check-circle-fill text-primary me-1"></i> <?php echo htmlspecialchars(explode(':', $feat)[0]); ?></li>
                                            <?php
                endforeach; ?>
                                        </ul>
                                    <?php
            endif; ?>
                                    <div class="mt-auto">
                                        <a href="<?php echo $productUrl; ?>" class="btn btn-outline-primary btn-view-product">
                                            View Details <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
        endif; ?>
                    <?php
    endforeach; ?>
                <?php
else: ?>
                    <div class="col-12 text-center">
                        <p>No products found in this category.</p>
                    </div>
                <?php
endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center" data-aos="zoom-in">
            <h2 class="mb-3">Need Help Choosing the Right Machine?</h2>
            <p class="mb-4" style="font-size: 1.1rem; opacity: 0.9;">Our packaging experts can recommend the ideal <?php echo htmlspecialchars($categoryName); ?> for your specific requirements.</p>
            <a href="/contact.php" class="btn btn-light btn-lg">
                <i class="bi bi-telephone-fill me-2"></i>Contact Our Experts
            </a>
        </div>
    </section>

    <!-- Internal Links -->
    <section class="section py-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="h5 mb-3">Explore More Products</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($navigation as $navGroupSlug => $navGroup): ?>
                            <?php foreach ($navGroup['subcategories'] as $navCatSlug): ?>
                                <?php if ($navCatSlug !== $categoryId): ?>
                                    <?php $navCat = $jsonData['categories'][$navCatSlug] ?? null; ?>
                                    <?php if ($navCat): ?>
                                        <a href="<?php echo getCategoryUrlCat($navCatSlug, $navigation); ?>" class="btn btn-sm btn-outline-secondary">
                                            <?php echo htmlspecialchars($navCat['name']); ?>
                                        </a>
                                    <?php
            endif; ?>
                                <?php
        endif; ?>
                            <?php
    endforeach; ?>
                        <?php
endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>
