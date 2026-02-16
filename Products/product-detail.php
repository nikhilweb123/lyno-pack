<?php
$productId = $_GET['id'] ?? '';
$categorySlug = $_GET['category'] ?? '';
$groupSlug = $_GET['group'] ?? '';
$jsonData = json_decode(file_get_contents(__DIR__ . '/../data.json'), true);
$navigation = $jsonData['navigation'] ?? [];
$allProducts = $jsonData['products'] ?? [];
$allCategories = $jsonData['categories'] ?? [];

// Case-insensitive product lookup
$product = null;
$actualProductId = $productId;
foreach ($allProducts as $pId => $pData) {
    if (strtolower($pId) === strtolower($productId)) {
        $product = $pData;
        $actualProductId = $pId;
        break;
    }
}

if (!$product) {
    header("HTTP/1.0 404 Not Found");
    echo "Product not found";
    exit;
}

// Find category slug from product data if not provided
if (empty($categorySlug)) {
    foreach ($allCategories as $catSlug => $catData) {
        if (in_array($actualProductId, $catData['products'] ?? [])) {
            $categorySlug = $catSlug;
            break;
        }
    }
}

// Find group slug if not provided
if (empty($groupSlug)) {
    foreach ($navigation as $gSlug => $group) {
        if (in_array($categorySlug, $group['subcategories'])) {
            $groupSlug = $gSlug;
            break;
        }
    }
}

$category = $allCategories[$categorySlug] ?? null;
$categoryName = $category['name'] ?? ($product['category_name'] ?? 'Products');
$groupName = $navigation[$groupSlug]['name'] ?? 'Products';

// Helper functions
function getProductUrlProd($productId, $categorySlug, $navigation)
{
    foreach ($navigation as $groupSlug => $group) {
        if (in_array($categorySlug, $group['subcategories'])) {
            return '/products/' . $groupSlug . '/' . $categorySlug . '/' . strtolower($productId);
        }
    }
    return '/product/' . $productId;
}

function getCategoryUrlProd($categorySlug, $navigation)
{
    foreach ($navigation as $groupSlug => $group) {
        if (in_array($categorySlug, $group['subcategories'])) {
            return '/products/' . $groupSlug . '/' . $categorySlug;
        }
    }
    return '/category/' . $categorySlug;
}

$activePage = 'products';

// SEO meta (max 60 chars title, max 155 chars description)
$productTitle = $product['title'] ?? $actualProductId;
$metaTitle = $actualProductId . ' ' . $productTitle . ' | LYNOPACK';
if (strlen($metaTitle) > 60) {
    $metaTitle = $actualProductId . ' ' . substr($productTitle, 0, 40) . ' | LYNOPACK';
}
if (strlen($metaTitle) > 60) {
    $metaTitle = substr($metaTitle, 0, 57) . '...';
}

$metaDescription = $product['description'] ?? 'Buy ' . $actualProductId . ' from LYNOPACK.';
if (strlen($metaDescription) > 155) {
    $metaDescription = substr($metaDescription, 0, 152) . '...';
}

// Focus keywords
$focusKeywords = $productTitle . ', ' . $categoryName . ', LYNOPACK, ' . $actualProductId;

// Breadcrumbs
$categoryUrl = getCategoryUrlProd($categorySlug, $navigation);
$productUrl = getProductUrlProd($actualProductId, $categorySlug, $navigation);

$breadcrumbs = [
    ['name' => 'Home', 'url' => '/index.php'],
    ['name' => 'Products', 'url' => '#'],
    ['name' => $groupName, 'url' => '#'],
    ['name' => $categoryName, 'url' => $categoryUrl],
    ['name' => $actualProductId, 'url' => $productUrl]
];

// Related products (same category, excluding current)
$relatedProducts = [];
if ($category && isset($category['products'])) {
    foreach ($category['products'] as $relProdId) {
        if ($relProdId !== $actualProductId && isset($allProducts[$relProdId])) {
            $relatedProducts[$relProdId] = $allProducts[$relProdId];
        }
    }
}

// Product image
$productImage = $product['image'] ?? '/assets/img/coming-soon.jpg';
$productImageAlt = $actualProductId . ' ' . $productTitle . ' - LYNOPACK Packaging Machine';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo htmlspecialchars($metaTitle); ?></title>
  <meta content="<?php echo htmlspecialchars($metaDescription); ?>" name="description">
  <meta name="keywords" content="<?php echo htmlspecialchars($focusKeywords); ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="https://lynopack.com<?php echo $productUrl; ?>">

  <!-- Open Graph -->
  <meta property="og:title" content="<?php echo htmlspecialchars($metaTitle); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription); ?>">
  <meta property="og:image" content="https://lynopack.com<?php echo htmlspecialchars($productImage); ?>">
  <meta property="og:url" content="https://lynopack.com<?php echo $productUrl; ?>">
  <meta property="og:type" content="product">

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

  <!-- JSON-LD Product Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?php echo htmlspecialchars($actualProductId . ' ' . $productTitle); ?>",
    "description": "<?php echo htmlspecialchars($product['description']); ?>",
    "image": "https://lynopack.com<?php echo htmlspecialchars($productImage); ?>",
    "brand": {
      "@type": "Brand",
      "name": "LYNOPACK"
    },
    "manufacturer": {
      "@type": "Organization",
      "name": "LYNOPACK TECHSOL LLP",
      "url": "https://lynopack.com"
    },
    "category": "<?php echo htmlspecialchars($categoryName); ?>",
    "url": "https://lynopack.com<?php echo $productUrl; ?>"
  }
  </script>

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
    /* Breadcrumb */
    .breadcrumb-nav {
      background-color: #f8f9fa;
      padding: 40px 0;
      border-bottom: 1px solid #eee;
    }
    .breadcrumb-nav ol {
      margin: 0; padding: 0;
      list-style: none;
      display: flex; flex-wrap: wrap;
      font-size: 14px;
    }
    .breadcrumb-nav ol li { display: flex; align-items: center; }
    .breadcrumb-nav ol li + li::before {
      content: "\F285";
      font-family: "bootstrap-icons";
      margin: 0 8px; color: #999; font-size: 10px;
    }
    .breadcrumb-nav ol li a {
      color: var(--accent-color); text-decoration: none; transition: color 0.2s;
    }
    .breadcrumb-nav ol li a:hover { text-decoration: underline; }
    .breadcrumb-nav ol li.current { color: #666; }

    /* Product Hero */
    .product-hero {
      background: linear-gradient(135deg, #f8f9fa 60%, #e8f0fe 100%);
      padding: 40px 0 50px;
      border-bottom: 1px solid #eee;
    }
    .product-hero .badge-category {
      background: linear-gradient(135deg, #01337e, #0a5cbf);
      color: #fff;
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      display: inline-block;
      margin-bottom: 12px;
    }
    .product-hero h1 {
      font-size: 2.2rem;
      font-weight: 700;
      color: #1a1a2e;
      line-height: 1.3;
    }
    .product-hero .product-subtitle {
      font-size: 1.1rem;
      color: #555;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .product-hero-image {
      width: 100%;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
      transition: transform 0.3s ease;
    }
    .product-hero-image:hover {
      transform: scale(1.02);
    }

    /* Section styles */
    .content-section { padding: 50px 0; }
    .content-section:nth-child(even) { background: #f9fafb; }
    .section-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: #1a1a2e;
      margin-bottom: 25px;
      padding-bottom: 12px;
      border-bottom: 3px solid #01337e;
      display: inline-block;
    }

    /* Feature list */
    .feature-item {
      display: flex;
      align-items: flex-start;
      gap: 15px;
      padding: 15px 0;
      border-bottom: 1px solid #f0f0f0;
    }
    .feature-item:last-child { border-bottom: none; }
    .feature-icon {
      width: 40px; height: 40px;
      background: linear-gradient(135deg, #01337e, #0a5cbf);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .feature-icon i { color: #fff; font-size: 1.1rem; }
    .feature-text {
      font-size: 0.95rem;
      color: #444;
      line-height: 1.5;
    }
    .feature-text strong { color: #1a1a2e; }

    /* Working Principle */
    .step-item {
      display: flex;
      gap: 20px;
      padding: 20px 0;
      position: relative;
    }
    .step-number {
      width: 45px; height: 45px;
      background: linear-gradient(135deg, #01337e, #0a5cbf);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-weight: 700; font-size: 1.1rem;
      flex-shrink: 0;
    }
    .step-content {
      font-size: 0.95rem;
      color: #444;
      line-height: 1.6;
      padding-top: 10px;
    }

    /* Application badges */
    .app-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #f0f4ff;
      border: 1px solid #d0daf0;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 500;
      color: #01337e;
      transition: all 0.2s;
    }
    .app-badge:hover {
      background: #01337e;
      color: #fff;
      border-color: #01337e;
    }

    /* Benefits */
    .benefit-card {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 12px;
      padding: 25px;
      text-align: center;
      transition: all 0.3s;
      height: 100%;
    }
    .benefit-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .benefit-card i {
      font-size: 2.5rem;
      color: #01337e;
      margin-bottom: 15px;
    }
    .benefit-card p {
      font-size: 0.92rem;
      color: #555;
      margin: 0;
      line-height: 1.5;
    }

    /* Related products */
    .related-product-card {
      border: 1px solid #eee;
      border-radius: 12px;
      overflow: hidden;
      transition: all 0.3s;
      height: 100%;
    }
    .related-product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .related-product-card img {
      height: 200px;
      object-fit: cover;
      width: 100%;
    }
    .related-product-card .card-body { padding: 1.2rem; }

    /* CTA Banner */
    .cta-banner {
      background: linear-gradient(135deg, #01337e 0%, #0a5cbf 100%);
      padding: 50px 0;
      color: #fff;
    }
    .cta-banner h2 { color: #fff; font-weight: 700; }
    .cta-banner .btn-light {
      padding: 12px 35px;
      font-weight: 600;
      border-radius: 8px;
    }

    /* Specs table */
    .specs-table { border-radius: 8px; overflow: hidden; }
    .specs-table th {
      background-color: #01337e;
      color: #fff;
      font-weight: 600;
      width: 40%;
    }
    .specs-table td { color: #444; }

    .img-overflow-hidden {
      overflow: hidden;
      border-radius: 12px 12px 0 0;
    }
  </style>
</head>
<body class="product-page">

<?php include __DIR__ . '/../header.php'; ?>

<main class="main">

    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav mt-5" aria-label="Breadcrumb">
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

    <!-- Product Hero Section -->
    <section class="product-hero">
        <div class="container">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <a href="<?php echo $categoryUrl; ?>" class="badge-category text-decoration-none">
                        <?php echo htmlspecialchars($categoryName); ?>
                    </a>
                    <h1><?php echo htmlspecialchars($actualProductId . ' – ' . $productTitle); ?></h1>
                    <p class="product-subtitle"><?php echo htmlspecialchars(substr($product['description'], 0, 200)); ?></p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="/contact.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-envelope-fill me-2"></i>Request a Quote
                        </a>
                        <a href="<?php echo $categoryUrl; ?>" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-grid-fill me-2"></i>View All Models
                        </a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="<?php echo htmlspecialchars($productImage); ?>" 
                         alt="<?php echo htmlspecialchars($productImageAlt); ?>" 
                         class="product-hero-image img-fluid"
                         loading="eager">
                </div>
            </div>
        </div>
    </section>

    <!-- Product Overview -->
    <section class="content-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8">
                    <h2 class="section-title">Product Overview</h2>
                    <p class="lead" style="color: #444; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    
                    <?php if (isset($product['core_functionality']) && is_array($product['core_functionality'])): ?>
                    <h3 class="mt-4 mb-3" style="font-size: 1.2rem; font-weight: 600;">Core Functionality</h3>
                    <?php foreach ($product['core_functionality'] as $func): ?>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-gear-fill"></i></div>
                            <div class="feature-text">
                                <?php
        $parts = explode(':', $func, 2);
        if (count($parts) == 2) {
            echo '<strong>' . htmlspecialchars($parts[0]) . ':</strong> ' . htmlspecialchars(trim($parts[1]));
        }
        else {
            echo htmlspecialchars($func);
        }
?>
                            </div>
                        </div>
                    <?php
    endforeach; ?>
                    <?php
endif; ?>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                        <div class="card-body p-4" style="background: linear-gradient(135deg, #f8f9fa, #e8f0fe);">
                            <h3 class="h5 fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Quick Info</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Model:</strong> <?php echo htmlspecialchars($actualProductId); ?></li>
                                <li class="mb-2"><strong>Category:</strong> <a href="<?php echo $categoryUrl; ?>"><?php echo htmlspecialchars($categoryName); ?></a></li>
                                <?php if (!empty($product['applications'])): ?>
                                <li class="mb-2"><strong>Best For:</strong> <?php echo htmlspecialchars(implode(', ', array_slice($product['applications'], 0, 3))); ?></li>
                                <?php
endif; ?>
                                <li class="mb-0"><strong>Brand:</strong> LYNOPACK</li>
                            </ul>
                        </div>
                        <div class="card-footer border-0 p-3 text-center" style="background: #01337e;">
                            <a href="/contact.php" class="btn btn-light w-100 fw-bold mb-2">Get Best Price</a>
                            <a href="/catalogue.pdf" target="_blank" class="btn btn-outline-light btn-sm w-100 fw-bold mb-2">Download Catalogue</a>
                            <a href="<?php echo htmlspecialchars($productImage); ?>" download="<?php echo htmlspecialchars($actualProductId); ?>-photo.jpg" class="btn btn-outline-light btn-sm w-100 fw-bold">Download Photos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features -->
    <?php if (isset($product['features']) && is_array($product['features']) && count($product['features']) > 0): ?>
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Key Features</h2>
            <div class="row gy-3">
                <?php foreach ($product['features'] as $index => $feature): ?>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 50; ?>">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-check-lg"></i></div>
                        <div class="feature-text">
                            <?php
        $parts = explode(':', $feature, 2);
        if (count($parts) == 2) {
            echo '<strong>' . htmlspecialchars($parts[0]) . ':</strong> ' . htmlspecialchars(trim($parts[1]));
        }
        else {
            echo htmlspecialchars($feature);
        }
?>
                        </div>
                    </div>
                </div>
                <?php
    endforeach; ?>
            </div>
        </div>
    </section>
    <?php
endif; ?>

    <!-- Working Principle -->
    <?php
$workingPrinciple = $product['working_principle'] ?? null;
$howItWorks = $product['how_it_works'] ?? null;
if ($workingPrinciple || $howItWorks):
?>
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Working Principle</h2>
            <?php if ($howItWorks): ?>
                <p style="color: #444; line-height: 1.8; font-size: 1.05rem;"><?php echo nl2br(htmlspecialchars($howItWorks)); ?></p>
            <?php
    endif; ?>
            
            <?php if ($workingPrinciple && is_array($workingPrinciple)): ?>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <?php foreach ($workingPrinciple as $stepIndex => $step): ?>
                    <div class="step-item" data-aos="fade-up" data-aos-delay="<?php echo $stepIndex * 80; ?>">
                        <div class="step-number"><?php echo $stepIndex + 1; ?></div>
                        <div class="step-content"><?php echo htmlspecialchars($step); ?></div>
                    </div>
                    <?php
        endforeach; ?>
                </div>
            </div>
            <?php
    elseif ($workingPrinciple): ?>
                <p style="color: #444; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($workingPrinciple)); ?></p>
            <?php
    endif; ?>
        </div>
    </section>
    <?php
endif; ?>

    <!-- Applications -->
    <?php if (isset($product['applications']) && is_array($product['applications']) && count($product['applications']) > 0): ?>
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Applications</h2>
            <p class="mb-4" style="color: #555;">The <?php echo htmlspecialchars($actualProductId); ?> is designed for use across multiple industries and applications:</p>
            <div class="d-flex flex-wrap gap-3">
                <?php foreach ($product['applications'] as $app): ?>
                    <span class="app-badge" data-aos="zoom-in">
                        <i class="bi bi-building"></i>
                        <?php echo htmlspecialchars($app); ?>
                    </span>
                <?php
    endforeach; ?>
            </div>
        </div>
    </section>
    <?php
endif; ?>

    <!-- Key Benefits -->
    <?php if (isset($product['benefits']) && is_array($product['benefits']) && count($product['benefits']) > 0): ?>
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Key Benefits</h2>
            <div class="row gy-4">
                <?php
    $benefitIcons = ['bi-shield-check', 'bi-lightning-charge', 'bi-graph-up-arrow', 'bi-gear-wide-connected', 'bi-award', 'bi-box-seam'];
    foreach ($product['benefits'] as $bIndex => $benefit):
        $icon = $benefitIcons[$bIndex % count($benefitIcons)];
?>
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $bIndex * 80; ?>">
                    <div class="benefit-card">
                        <i class="bi <?php echo $icon; ?>"></i>
                        <p><?php echo htmlspecialchars($benefit); ?></p>
                    </div>
                </div>
                <?php
    endforeach; ?>
            </div>
        </div>
    </section>
    <?php
endif; ?>

    <!-- Technical Specifications (if available) -->
    <?php if (isset($product['specifications']) && is_array($product['specifications'])): ?>
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Technical Specifications</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <table class="table table-striped specs-table">
                        <thead>
                            <tr>
                                <th>Parameter</th>
                                <th>Specification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product['specifications'] as $specKey => $specValue): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($specKey); ?></td>
                                <td><?php echo htmlspecialchars($specValue); ?></td>
                            </tr>
                            <?php
    endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <?php
endif; ?>

    <!-- CTA Banner -->
    <section class="cta-banner">
        <div class="container text-center" data-aos="zoom-in">
            <h2 class="mb-3">Interested in the <?php echo htmlspecialchars($actualProductId); ?>?</h2>
            <p class="mb-4" style="font-size: 1.1rem; opacity: 0.9;">Get a personalized quote, technical data sheet, or schedule a demonstration with our packaging experts.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/contact.php" class="btn btn-light btn-lg">
                    <i class="bi bi-envelope-fill me-2"></i>Request a Quote
                </a>
                <a href="tel:+918200012841" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-telephone-fill me-2"></i>Call Us Now
                </a>
                <a href="https://lynopack.com/assets/videos/<?php echo htmlspecialchars($actualProductId); ?>.mp4" class="btn btn-outline-light btn-lg glightbox">
                    <i class="bi bi-play-circle-fill me-2"></i>Watch Video
                </a>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (count($relatedProducts) > 0): ?>
    <section class="content-section">
        <div class="container">
            <h2 class="section-title">Related Products in <?php echo htmlspecialchars($categoryName); ?></h2>
            <div class="row gy-4">
                <?php
    $shown = 0;
    foreach ($relatedProducts as $relProdId => $relProd):
        if ($shown >= 4)
            break;
        $relUrl = getProductUrlProd($relProdId, $categorySlug, $navigation);
?>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo $shown * 100; ?>">
                    <div class="card related-product-card">
                        <div class="img-overflow-hidden">
                            <img src="<?php echo htmlspecialchars($relProd['image'] ?? '/assets/img/coming-soon.jpg'); ?>" 
                                 alt="<?php echo htmlspecialchars($relProdId . ' ' . $relProd['title']); ?>"
                                 loading="lazy">
                        </div>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2" style="font-size: 0.75rem;"><?php echo htmlspecialchars($relProdId); ?></span>
                            <h3 class="h6 fw-bold mb-2"><?php echo htmlspecialchars($relProd['title']); ?></h3>
                            <a href="<?php echo $relUrl; ?>" class="btn btn-sm btn-outline-primary w-100 mt-2">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
        $shown++;
    endforeach;
?>
            </div>
            <?php if (count($relatedProducts) > 4): ?>
            <div class="text-center mt-4">
                <a href="<?php echo $categoryUrl; ?>" class="btn btn-primary">View All <?php echo htmlspecialchars($categoryName); ?></a>
            </div>
            <?php
    endif; ?>
        </div>
    </section>
    <?php
endif; ?>

    <!-- Internal Links Section -->
    <section class="py-4" style="background: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="h6 mb-2">Browse Categories</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo $categoryUrl; ?>" class="btn btn-sm btn-primary"><?php echo htmlspecialchars($categoryName); ?></a>
                        <?php
$otherCats = 0;
foreach ($navigation as $navGroupSlug => $navGroup):
    foreach ($navGroup['subcategories'] as $navCatSlug):
        if ($navCatSlug !== $categorySlug && $otherCats < 5):
            $navCat = $allCategories[$navCatSlug] ?? null;
            if ($navCat):
?>
                                    <a href="<?php echo getCategoryUrlProd($navCatSlug, $navigation); ?>" class="btn btn-sm btn-outline-secondary"><?php echo htmlspecialchars($navCat['name']); ?></a>
                        <?php
                $otherCats++;
            endif;
        endif;
    endforeach;
endforeach;
?>
                    </div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="/contact.php" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-envelope me-1"></i>Contact Us</a>
                    <a href="/about.php" class="btn btn-sm btn-outline-primary"><i class="bi bi-info-circle me-1"></i>About LYNOPACK</a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>
