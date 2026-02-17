<?php
$activePage = $activePage ?? '';

// Load navigation data
$navJsonData = json_decode(file_get_contents(__DIR__ . '/data.json'), true);
$navigation = $navJsonData['navigation'] ?? [];
$categories = $navJsonData['categories'] ?? [];
$allProducts = $navJsonData['products'] ?? [];

/**
 * Helper: Generate SEO-friendly product URL
 */
function getProductUrl($productId, $categorySlug, $navigation)
{
  foreach ($navigation as $groupSlug => $group) {
    if (in_array($categorySlug, $group['subcategories'])) {
      return '/products/' . $groupSlug . '/' . $categorySlug . '/' . strtolower($productId);
    }
  }
  return '/product/' . $productId;
}

/**
 * Helper: Generate SEO-friendly category URL
 */
function getCategoryUrl($categorySlug, $navigation)
{
  foreach ($navigation as $groupSlug => $group) {
    if (in_array($categorySlug, $group['subcategories'])) {
      return '/products/' . $groupSlug . '/' . $categorySlug;
    }
  }
  return '/category/' . $categorySlug;
}
// Contact information
$phoneNumber = '+91 82000 12841, +91 9409930209';
$emailAddress = 'madadserviceprovider@gmail.com';
?>
<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container position-relative d-flex align-items-center justify-content-between">

    <a href="/index.php" class="logo d-flex align-items-center me-auto me-xl-0">
      <img src="/assets/img/logo.png" alt="LYNOPACK - Packaging Machine Manufacturer" loading="lazy">
    </a>

    <!-- Desktop Navigation (Hidden on Mobile) -->
    <nav id="navmenu" class="navmenu desktop-nav" role="navigation" aria-label="Main Navigation">
      <ul role="menubar">
        <li role="none"><a href="/index.php" role="menuitem" <?php echo $activePage === 'home' ? ' class="active"' : ''; ?>>Home</a></li>
        <li role="none"><a href="/about.php" role="menuitem" <?php echo $activePage === 'about' ? ' class="active"' : ''; ?>>About</a></li>
        <!-- Products Mega Dropdown (3-Level) -->
        <li class="dropdown mega-menu" role="none">
          <a href="#" role="menuitem" aria-haspopup="true" aria-expanded="false"
            class="dropdown-toggle"><span>Products</span> <i class="bi bi-chevron-down"></i></a>
          <ul role="menu" class="dropdown-level-1">
            <?php foreach ($navigation as $groupSlug => $group): ?>
              <?php
              $subcats = $group['subcategories'] ?? [];
              $hasSubcats = count($subcats) > 1 || (count($subcats) === 1 && $subcats[0] !== $groupSlug);
              ?>
              <?php if ($hasSubcats): ?>
                <li class="dropdown" role="none">
                  <a href="#" role="menuitem" aria-haspopup="true" aria-expanded="false"
                    class="dropdown-toggle"><span><?php echo htmlspecialchars($group['name']); ?></span> <i
                      class="bi bi-chevron-right"></i></a>
                  <ul role="menu" class="dropdown-level-2">
                    <?php foreach ($subcats as $subcatSlug): ?>
                      <?php $subcat = $categories[$subcatSlug] ?? null; ?>
                      <?php if ($subcat): ?>
                        <?php $subcatProducts = $subcat['products'] ?? []; ?>
                        <?php if (count($subcatProducts) > 0): ?>
                          <li class="dropdown" role="none">
                            <a href="<?php echo getCategoryUrl($subcatSlug, $navigation); ?>" role="menuitem" aria-haspopup="true"
                              aria-expanded="false" class="dropdown-toggle">
                              <span><?php echo htmlspecialchars($subcat['name']); ?></span> <i
                                class="bi bi-chevron-right toggle-dropdown"></i>
                            </a>
                            <ul role="menu" class="dropdown-level-3">
                              <?php foreach ($subcatProducts as $prodId): ?>
                                <?php $prod = $allProducts[$prodId] ?? null; ?>
                                <?php if ($prod): ?>
                                  <li role="none">
                                    <a href="<?php echo getProductUrl($prodId, $subcatSlug, $navigation); ?>" role="menuitem">
                                      <?php echo htmlspecialchars($prodId); ?>
                                    </a>
                                  </li>
                                  <?php
                                endif; ?>
                                <?php
                              endforeach; ?>
                            </ul>
                          </li>
                          <?php
                        else: ?>
                          <li role="none">
                            <a href="<?php echo getCategoryUrl($subcatSlug, $navigation); ?>"
                              role="menuitem"><?php echo htmlspecialchars($subcat['name']); ?></a>
                          </li>
                          <?php
                        endif; ?>
                        <?php
                      endif; ?>
                      <?php
                    endforeach; ?>
                  </ul>
                </li>
                <?php
              else: ?>
                <!-- Single subcategory group (e.g., Conveyor) -->
                <?php
                $singleSubcatSlug = $subcats[0] ?? $groupSlug;
                $singleSubcat = $categories[$singleSubcatSlug] ?? null;
                ?>
                <?php if ($singleSubcat && !empty($singleSubcat['products'])): ?>
                  <li class="dropdown" role="none">
                    <a href="<?php echo getCategoryUrl($singleSubcatSlug, $navigation); ?>" role="menuitem"
                      aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                      <span><?php echo htmlspecialchars($group['name']); ?></span> <i class="bi bi-chevron-right"></i>
                    </a>
                    <ul role="menu" class="dropdown-level-3">
                      <?php foreach ($singleSubcat['products'] as $prodId): ?>
                        <?php $prod = $allProducts[$prodId] ?? null; ?>
                        <?php if ($prod): ?>
                          <li role="none">
                            <a href="<?php echo getProductUrl($prodId, $singleSubcatSlug, $navigation); ?>" role="menuitem">
                              <?php echo htmlspecialchars($prodId); ?>
                            </a>
                          </li>
                          <?php
                        endif; ?>
                        <?php
                      endforeach; ?>
                    </ul>
                  </li>
                  <?php
                else: ?>
                  <li role="none">
                    <a href="<?php echo getCategoryUrl($singleSubcatSlug, $navigation); ?>"
                      role="menuitem"><?php echo htmlspecialchars($group['name']); ?></a>
                  </li>
                  <?php
                endif; ?>
                <?php
              endif; ?>
              <?php
            endforeach; ?>
          </ul>
        </li>
        <li role="none"><a href="/gallery.php" role="menuitem" <?php echo $activePage === 'gallery' ? ' class="active"' : ''; ?>>Gallery</a></li>
        <li role="none"><a href="/blog.php" role="menuitem" <?php echo $activePage === 'blog' ? ' class="active"' : ''; ?>>Blogs</a></li>
        <li role="none"><a href="/contact.php" role="menuitem" <?php echo $activePage === 'contact' ? ' class="active"' : ''; ?>>Contact</a></li>
      </ul>
    </nav>

    <!-- Mobile Navigation (Separate Structure) -->
    <nav id="mobile-navmenu" class="mobile-navmenu" role="navigation" aria-label="Mobile Navigation">
      <div class="mobile-nav-header">
        <span class="mobile-nav-title">Menu</span>
        <button type="button" class="mobile-nav-close" aria-label="Close menu">
          <span class="close-icon">×</span>
        </button>
      </div>

      <ul class="mobile-nav-list">
        <li><a href="/index.php" <?php echo $activePage === 'home' ? ' class="active"' : ''; ?>>Home</a></li>
        <li><a href="/about.php" <?php echo $activePage === 'about' ? ' class="active"' : ''; ?>>About</a></li>
        <li><a href="/gallery.php" <?php echo $activePage === 'gallery' ? ' class="active"' : ''; ?>>Gallery</a></li>
        <li><a href="/blog.php" <?php echo $activePage === 'blog' ? ' class="active"' : ''; ?>>Blogs</a></li>

        <!-- Mobile Products Dropdown -->
        <li class="mobile-dropdown">
          <button class="mobile-dropdown-btn">
            <span>Products</span>
            <i class="bi bi-chevron-down"></i>
          </button>
          <ul class="mobile-dropdown-content">
            <?php foreach ($navigation as $groupSlug => $group): ?>
              <?php
              $subcats = $group['subcategories'] ?? [];
              $hasSubcats = count($subcats) > 1 || (count($subcats) === 1 && $subcats[0] !== $groupSlug);
              ?>
              <?php if ($hasSubcats): ?>
                <li class="mobile-dropdown">
                  <button class="mobile-dropdown-btn level-2">
                    <span><?php echo htmlspecialchars($group['name']); ?></span>
                    <i class="bi bi-chevron-down"></i>
                  </button>
                  <ul class="mobile-dropdown-content">
                    <?php foreach ($subcats as $subcatSlug): ?>
                      <?php $subcat = $categories[$subcatSlug] ?? null; ?>
                      <?php if ($subcat): ?>
                        <?php $subcatProducts = $subcat['products'] ?? []; ?>
                        <?php if (count($subcatProducts) > 0): ?>
                          <li class="mobile-dropdown">
                            <button class="mobile-dropdown-btn level-3">
                              <span><?php echo htmlspecialchars($subcat['name']); ?></span>
                              <i class="bi bi-chevron-down"></i>
                            </button>
                            <ul class="mobile-dropdown-content">
                              <?php foreach ($subcatProducts as $prodId): ?>
                                <?php $prod = $allProducts[$prodId] ?? null; ?>
                                <?php if ($prod): ?>
                                  <li>
                                    <a href="<?php echo getProductUrl($prodId, $subcatSlug, $navigation); ?>">
                                      <?php echo htmlspecialchars($prodId); ?>
                                    </a>
                                  </li>
                                <?php endif; ?>
                              <?php endforeach; ?>
                            </ul>
                          </li>
                        <?php else: ?>
                          <li>
                            <a
                              href="<?php echo getCategoryUrl($subcatSlug, $navigation); ?>"><?php echo htmlspecialchars($subcat['name']); ?></a>
                          </li>
                        <?php endif; ?>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </ul>
                </li>
              <?php else: ?>
                <?php
                $singleSubcatSlug = $subcats[0] ?? $groupSlug;
                $singleSubcat = $categories[$singleSubcatSlug] ?? null;
                ?>
                <?php if ($singleSubcat && !empty($singleSubcat['products'])): ?>
                  <li class="mobile-dropdown">
                    <button class="mobile-dropdown-btn level-2">
                      <span><?php echo htmlspecialchars($group['name']); ?></span>
                      <i class="bi bi-chevron-down"></i>
                    </button>
                    <ul class="mobile-dropdown-content">
                      <?php foreach ($singleSubcat['products'] as $prodId): ?>
                        <?php $prod = $allProducts[$prodId] ?? null; ?>
                        <?php if ($prod): ?>
                          <li>
                            <a href="<?php echo getProductUrl($prodId, $singleSubcatSlug, $navigation); ?>">
                              <?php echo htmlspecialchars($prodId); ?>
                            </a>
                          </li>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </ul>
                  </li>
                <?php else: ?>
                  <li>
                    <a
                      href="<?php echo getCategoryUrl($singleSubcatSlug, $navigation); ?>"><?php echo htmlspecialchars($group['name']); ?></a>
                  </li>
                <?php endif; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </li>

        <li><a href="/contact.php" <?php echo $activePage === 'contact' ? ' class="active"' : ''; ?>>Contact</a></li>
      </ul>

      <!-- Mobile Contact Information and Button -->
      <div class="mobile-nav-contact">
        <div class="mobile-contact-info">
          <a href="tel:+918200012841" class="mobile-contact-item">
            <i class="bi bi-telephone"></i>
            <span><?php echo htmlspecialchars($phoneNumber); ?></span>
          </a>
          <a href="mailto:<?php echo htmlspecialchars($emailAddress); ?>" class="mobile-contact-item">
            <i class="bi bi-envelope"></i>
            <span><?php echo htmlspecialchars($emailAddress); ?></span>
          </a>
        </div>
        <a class="mobile-btn-getstarted" href="/contact.php">Get Started</a>
      </div>
    </nav>

    <!-- Mobile Navigation Overlay -->
    <div id="mobile-nav-overlay" class="mobile-nav-overlay"></div>

    <!-- Hidden Google Translate Element -->
    <div id="google_translate_element" style="display:none"></div>

    <!-- Custom Language Dropdown & Mobile Responsive Styles -->
    <style>
      /* ========== HEADER CONTACT INFORMATION - DESKTOP ONLY ========== */
      .header-right-section {
        gap: 8px;
      }

      .header-contact-info {
        display: flex;
        gap: 20px;
        margin-bottom: 4px;
      }

      .header-actions {
        display: flex;
        align-items: center;
        gap: 12px;
      }

      .header-contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--nav-color, #3a3939);
        text-decoration: none;
        font-size: 14px;
        font-family: var(--nav-font, "Raleway", sans-serif);
        font-weight: 500;
        transition: color 0.3s ease;
        white-space: nowrap;
      }

      .header-contact-item i {
        font-size: 16px;
        color: var(--accent-color, #01337e);
        transition: color 0.3s ease;
      }

      .header-contact-item:hover {
        color: var(--accent-color, #01337e);
        text-decoration: none;
      }

      .header-contact-item:hover i {
        color: var(--nav-hover-color, #01337e);
      }

      /* Hide contact info, language dropdown, and button when hamburger icon shows (below 1200px) */
      @media (max-width: 1199px) {

        .header-right-section,
        .header-contact-info,
        .header-actions,
        .header-right-section .lang-dropdown,
        .header-right-section .btn-getstarted,
        #header .header-right-section,
        #header .header-contact-info,
        #header .header-actions {
          display: none !important;
          visibility: hidden !important;
        }
      }

      /* Hide Google Translate Top Bar */
      .VIpgJd-ZVi9od-ORHb {
        margin: 0;
        background-color: #E4EFFB;
        overflow: hidden;
        display: none !important;
      }

      .goog-te-banner-frame.skiptranslate {
        display: none !important;
      }

      .goog-te-combo {
        display: none !important;
      }

      body {
        top: 0px !important;
      }

      /* Ensure header stays fixed at top */
      #header {
        top: 0px !important;
      }

      /* Hide language dropdown on mobile */
      @media (max-width: 1199px) {
        .lang-dropdown {
          display: none !important;
        }
      }

      .lang-dropdown {
        position: relative;
      }

      .lang-dropdown .dropdown-toggle {
        background: transparent;
        border: none;
        color: var(--nav-color);
        font-family: var(--nav-font);
        font-weight: 600;
        padding: 0;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .lang-dropdown .dropdown-toggle:focus {
        outline: none;
      }

      .lang-dropdown .dropdown-toggle:after {
        display: none;
      }

      .lang-dropdown .dropdown-menu {
        display: none;
        /* Hidden by default */
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 10000;
        /* Higher than header */
        background: #ffffff;
        border: none;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        padding: 10px;
        border-radius: 8px;
        min-width: 180px;
        margin-top: 10px;
        list-style: none;
      }

      .lang-dropdown .dropdown-menu.show {
        display: block;
        /* Shown when active */
        animation: fadeIn 0.2s ease-in-out;
      }

      .lang-dropdown .dropdown-item {
        font-family: var(--nav-font);
        font-size: 14px;
        padding: 8px 16px;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 4px;
        color: #333333;
        display: block;
        text-decoration: none;
      }

      .lang-dropdown .dropdown-item:hover {
        background-color: var(--nav-hover-color);
        color: #ffffff;
      }

      .lang-dropdown .dropdown-item.active-lang {
        background-color: var(--nav-color);
        color: #ffffff;
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }

        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* ========== MOBILE HAMBURGER & NAVIGATION STYLES ========== */
      /* Desktop First Approach: Default styles for desktop, then override for mobile */

      /* Mobile Responsive Navigation - Hide navmenu on mobile by default */
      @media (max-width: 1199px) {

        /* Hide main navigation on mobile/tablet by default */
        .navmenu {
          position: fixed;
          top: 70px;
          left: 0;
          right: 0;
          width: 100%;
          height: calc(100vh - 70px);
          background: #ffffff;
          z-index: 9998;
          overflow-y: auto;
          overflow-x: hidden;
          display: none;
          flex-direction: column;
          padding: 0;
          margin: 0;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
          -webkit-overflow-scrolling: touch;
        }

        /* Show navmenu when .active class is added */
        .navmenu.active {
          display: flex;
          animation: slideInMenu 0.3s ease-out;
        }

        @keyframes slideInMenu {
          from {
            opacity: 0;
            transform: translateY(-10px);
          }

          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        /* Main menu items (Home, About, Gallery, Blogs, Contact) */
        .navmenu>ul {
          width: 100%;
          flex-direction: column;
          padding: 0;
          margin: 0;
          list-style: none;
          display: flex;
        }

        .navmenu>ul>li {
          width: 100%;
          margin: 0;
          padding: 0;
          border-bottom: 1px solid #e8e8e8;
        }

        .navmenu>ul>li>a {
          display: block;
          padding: 16px 20px;
          color: var(--nav-dropdown-color);
          text-decoration: none;
          font-size: 16px;
          font-weight: 500;
          transition: background-color 0.2s ease, color 0.2s ease;
        }

        .navmenu>ul>li>a:active {
          background-color: #f8f8f8;
          color: var(--nav-dropdown-hover-color);
        }

        /* Show mobile hamburger menu toggle */
        .mobile-nav-toggle {
          display: block !important;
          z-index: 9999;
          cursor: pointer;
          font-size: 28px;
          color: var(--nav-color);
          background: transparent;
          border: none;
          padding: 8px;
          margin: 0;
          transition: transform 0.3s ease, color 0.2s ease;
        }

        .mobile-nav-toggle:active {
          color: var(--nav-hover-color);
          transform: scale(0.95);
        }
      }

      /* Product mega dropdown on mobile - Level 1 */
      @media (max-width: 1199px) {
        .navmenu ul.dropdown-level-1 {
          position: static;
          width: 100%;
          background: #f7f7f7;
          border-left: 3px solid var(--accent-color);
          list-style: none;
          padding: 0;
          margin: 0;
          box-sizing: border-box;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          display: block;
        }

        .navmenu ul.dropdown-level-1.show {
          max-height: 3000px;
          padding-bottom: 8px;
        }

        /* Level 2 Dropdowns - Categories under Product Groups */
        .navmenu .dropdown-level-2 {
          position: static;
          width: 100%;
          background: #efefef;
          padding-left: 16px;
          list-style: none;
          margin: 0 !important;
          padding-top: 0 !important;
          padding-bottom: 0 !important;
          padding-right: 0 !important;
          border-left: 3px solid var(--nav-color);
          box-sizing: border-box;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          display: block;
        }

        .navmenu .dropdown-level-2.show {
          max-height: 2000px;
          padding-bottom: 6px;
        }

        /* Level 3 Dropdowns - Individual Products */
        .navmenu .dropdown-level-3 {
          position: static;
          width: 100%;
          background: #f9f9f9;
          padding-left: 16px;
          list-style: none;
          margin: 0 !important;
          padding-top: 0 !important;
          padding-bottom: 0 !important;
          padding-right: 0 !important;
          border-left: 3px solid #aaa;
          box-sizing: border-box;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
          display: block;
        }

        .navmenu .dropdown-level-3.show {
          max-height: 2500px;
          padding-bottom: 6px;
        }

        /* List items and remove default margins/padding */
        .navmenu .dropdown-level-2>li,
        .navmenu .dropdown-level-3>li {
          margin: 0 !important;
          padding: 0 !important;
          list-style: none;
          line-height: 1;
        }

        .navmenu .dropdown-level-2>li.dropdown,
        .navmenu .dropdown-level-3>li.dropdown {
          margin: 0 !important;
          padding: 0 !important;
        }

        /* Links in dropdowns - Touch-friendly sizing */
        .navmenu .dropdown-level-2>li>a,
        .navmenu .dropdown-level-3>li>a {
          display: block;
          padding: 13px 16px !important;
          margin: 0 !important;
          text-decoration: none;
          color: var(--nav-dropdown-color);
          transition: background-color 0.2s ease, color 0.2s ease;
          line-height: 1.5;
          font-size: 15px;
          word-wrap: break-word;
        }

        /* Remove hover effects on touch devices - Touch devices use :active instead */
        .navmenu .dropdown-level-2>li>a:active,
        .navmenu .dropdown-level-3>li>a:active {
          background-color: rgba(0, 0, 0, 0.05);
          color: var(--nav-dropdown-hover-color);
        }

        /* Toggle indicator for dropdowns (+ and X) */
        .navmenu .dropdown>a.dropdown-toggle {
          position: relative;
          padding-right: 45px !important;
          display: block;
          padding-top: 16px;
          padding-bottom: 16px;
          padding-left: 20px;
        }

        .navmenu .dropdown>a.dropdown-toggle::after {
          content: '+';
          position: absolute;
          right: 20px;
          top: 50%;
          transform: translateY(-50%);
          font-size: 22px;
          font-weight: 600;
          color: var(--accent-color);
          transition: transform 0.3s ease;
          line-height: 1;
        }

        .navmenu .dropdown>a.dropdown-toggle.expanded::after {
          transform: translateY(-50%) rotate(45deg);
        }

        /* Hide old toggle-dropdown icon on mobile */
        .toggle-dropdown {
          display: none !important;
        }

        /* First-level dropdown items (Categories) */
        .navmenu .dropdown-level-1>li.dropdown>a.dropdown-toggle {
          color: var(--nav-dropdown-color);
          font-size: 15px;
          font-weight: 500;
        }

        /* Style for category names in Products dropdown */
        .navmenu .dropdown-level-1>li>a {
          display: block;
          padding: 15px 20px;
          color: var(--nav-dropdown-color);
          text-decoration: none;
          font-weight: 500;
          font-size: 15px;
          transition: background-color 0.2s ease;
        }

        .navmenu .dropdown-level-1>li>a:active {
          background-color: rgba(0, 0, 0, 0.05);
          color: var(--nav-dropdown-hover-color);
        }
      }

      /* Tablet specific adjustments (768px - 1199px) */
      @media (max-width: 1199px) and (min-width: 768px) {
        .navmenu {
          top: 70px;
          height: calc(100vh - 70px);
        }

        .navmenu>ul>li>a {
          padding: 16px 24px;
          font-size: 16px;
        }

        .navmenu .dropdown-level-2 {
          padding-left: 20px;
        }

        .navmenu .dropdown-level-3 {
          padding-left: 20px;
        }

        .navmenu .dropdown-level-2>li>a,
        .navmenu .dropdown-level-3>li>a {
          padding: 13px 18px !important;
          font-size: 15px;
        }

        .navmenu .dropdown>a.dropdown-toggle {
          padding-right: 50px !important;
          padding-left: 24px;
        }

        .navmenu .dropdown>a.dropdown-toggle::after {
          right: 24px;
          font-size: 22px;
        }

        .navmenu .dropdown-level-1>li>a {
          padding: 15px 24px;
        }
      }

      /* Mobile specific adjustments (< 768px) */
      @media (max-width: 767px) {
        .navmenu {
          top: 70px;
          height: calc(100vh - 70px);
        }

        .navmenu>ul>li>a {
          padding: 14px 16px;
          font-size: 15px;
        }

        .navmenu .dropdown-level-2 {
          padding-left: 14px;
        }

        .navmenu .dropdown-level-3 {
          padding-left: 14px;
        }

        .navmenu .dropdown-level-2>li>a,
        .navmenu .dropdown-level-3>li>a {
          padding: 12px 14px !important;
          font-size: 14px;
        }

        .navmenu .dropdown>a.dropdown-toggle {
          padding-right: 42px !important;
          padding-top: 14px;
          padding-bottom: 14px;
          padding-left: 16px;
        }

        .navmenu .dropdown>a.dropdown-toggle::after {
          right: 16px;
          font-size: 20px;
        }

        .navmenu .dropdown-level-1>li>a {
          padding: 14px 16px;
        }
      }

      /* Small mobile devices (< 480px) */
      @media (max-width: 479px) {
        .navmenu {
          top: 70px;
          height: calc(100vh - 70px);
          left: 0;
          right: 0;
          width: 100%;
        }

        .navmenu>ul>li>a {
          padding: 13px 14px;
          font-size: 14px;
        }

        .navmenu .dropdown-level-2 {
          padding-left: 12px;
        }

        .navmenu .dropdown-level-3 {
          padding-left: 12px;
        }

        .navmenu .dropdown-level-2>li>a,
        .navmenu .dropdown-level-3>li>a {
          padding: 11px 12px !important;
          font-size: 13px;
        }

        .navmenu .dropdown>a.dropdown-toggle {
          padding-right: 40px !important;
          padding-top: 13px;
          padding-bottom: 13px;
          padding-left: 14px;
        }

        .navmenu .dropdown>a.dropdown-toggle::after {
          right: 14px;
          font-size: 18px;
        }

        .navmenu .dropdown-level-1>li>a {
          padding: 13px 14px;
        }

        .mobile-nav-toggle {
          padding: 6px;
          font-size: 26px;
        }
      }

      /* Desktop view (1200px and above) - Ensure full navigation visible */
      @media (min-width: 1200px) {

        /* Show navigation normally on desktop */
        .navmenu {
          position: static;
          top: auto;
          left: auto;
          right: auto;
          width: auto;
          height: auto;
          background: transparent;
          z-index: auto;
          display: flex !important;
          flex-direction: row;
          overflow: visible;
          box-shadow: none;
          animation: none;
        }

        /* Main menu items on desktop */
        .navmenu>ul {
          width: auto;
          flex-direction: row;
          display: flex;
        }

        .navmenu>ul>li {
          border-bottom: none;
          margin: 0 10px;
          padding: 0;
        }

        .navmenu>ul>li>a {
          padding: 10px 0;
          font-size: 15px;
          color: var(--nav-color);
          transition: color 0.3s, background-color 0.3s;
        }

        .navmenu>ul>li>a:hover,
        .navmenu>ul>li>a:focus,
        .navmenu>ul>li>a:active {
          color: var(--nav-hover-color);
          background-color: transparent;
          padding: 10px 0;
        }

        /* Hide dropdown toggles on desktop */
        .navmenu .dropdown>a.dropdown-toggle::after {
          display: none;
        }

        /* Style arrow icons for dropdown items on desktop */
        .navmenu .dropdown-toggle i.bi-chevron-down,
        .navmenu .dropdown-toggle i.bi-chevron-right {
          font-size: 12px;
          margin-left: 6px;
          transition: transform 0.2s ease;
          display: inline-block;
        }

        .navmenu .mega-menu .dropdown-toggle i.bi-chevron-down {
          font-size: 11px;
          margin-left: 4px;
        }

        .navmenu .dropdown-level-1>li.dropdown .dropdown-toggle i.bi-chevron-right {
          font-size: 11px;
          margin-left: 6px;
        }

        .navmenu .dropdown-level-2>li.dropdown .dropdown-toggle i.bi-chevron-right {
          font-size: 11px;
          margin-left: 6px;
        }

        /* Products mega menu - show as dropdown on desktop */
        .navmenu ul.dropdown-level-1 {
          position: absolute;
          top: 100%;
          left: 0;
          width: auto;
          background: white;
          border-left: none;
          max-height: none;
          overflow: visible;
          display: none;
          box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
          /* padding: 15px !important; */
          margin-top: 5px;
          border-radius: 4px;
          flex-direction: row;
          flex-wrap: wrap;
        }

        .navmenu .mega-menu:hover>.dropdown-level-1,
        .navmenu ul.dropdown-level-1.show {
          display: flex;
        }

        /* Level 2 dropdowns on desktop */
        .navmenu .dropdown-level-2 {
          position: absolute;
          top: 100%;
          left: 0;
          width: 200px;
          background: white;
          border-left: none;
          max-height: none;
          overflow: visible;
          display: none;
          box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
          padding: 0 !important;
          margin-top: 5px;
          border-radius: 4px;
          flex-direction: column;
        }

        .navmenu .dropdown-level-1>li.dropdown:hover>.dropdown-level-2,
        .navmenu .dropdown-level-2.show {
          display: flex;
        }

        /* Level 3 dropdowns on desktop */
        .navmenu .dropdown-level-3 {
          position: absolute;
          top: 0;
          left: 100%;
          width: 200px;
          background: white;
          border-left: none;
          max-height: none;
          overflow: visible;
          display: none;
          box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
          padding: 0 !important;
          margin-left: 5px;
          border-radius: 4px;
          flex-direction: column;
        }

        .navmenu .dropdown-level-2>li.dropdown:hover>.dropdown-level-3,
        .navmenu .dropdown-level-3.show {
          display: flex;
        }

        /* Desktop dropdown items */
        .navmenu .dropdown-level-1>li>a,
        .navmenu .dropdown-level-2>li>a,
        .navmenu .dropdown-level-3>li>a {
          padding: 10px 15px !important;
          font-size: 14px;
          color: #333;
          background-color: transparent;
          transition: color 0.3s, background-color 0.3s;
        }

        .navmenu .dropdown-level-1>li>a:hover,
        .navmenu .dropdown-level-1>li>a:focus,
        .navmenu .dropdown-level-1>li>a:active,
        .navmenu .dropdown-level-2>li>a:hover,
        .navmenu .dropdown-level-2>li>a:focus,
        .navmenu .dropdown-level-2>li>a:active,
        .navmenu .dropdown-level-3>li>a:hover,
        .navmenu .dropdown-level-3>li>a:focus,
        .navmenu .dropdown-level-3>li>a:active {
          background-color: #f5f5f5;
          color: var(--nav-hover-color);
          padding: 10px 15px !important;
        }

        /* Hide mobile hamburger on desktop */
        .mobile-nav-toggle {
          display: none !important;
        }
      }

      /* ========== SEPARATE MOBILE NAVIGATION SYSTEM ========== */

      /* Hide desktop nav on mobile, hide mobile nav on desktop */
      @media (max-width: 1199px) {
        .navmenu {
          display: none !important;
        }

        /* Show hamburger toggle by default (when menu is closed) */
        .mobile-nav-toggle {
          display: block !important;
          visibility: visible !important;
          opacity: 1 !important;
          z-index: 10001 !important;
          transition: opacity 0.3s ease, visibility 0.3s ease;
          color: var(--nav-color, #2c3e50) !important;
        }

        /* Hide hamburger icon when menu is open */
        .menu-open .mobile-nav-toggle,
        .mobile-navmenu.active~.mobile-nav-toggle {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
        }

        /* Ensure hamburger icon is only in main header, never in mobile menu */
        #header .container .mobile-nav-toggle {
          position: relative !important;
        }

        /* Hide hamburger icon if it somehow appears inside mobile menu */
        .mobile-navmenu .mobile-nav-toggle,
        .mobile-navmenu * .mobile-nav-toggle {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
        }
      }

      @media (min-width: 1200px) {

        .mobile-navmenu,
        .mobile-nav-overlay {
          display: none !important;
        }

        /* Hide hamburger icon on desktop */
        .mobile-nav-toggle {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
        }
      }

      /* Prevent Bootstrap Icons from creating duplicate icons on hamburger toggle */
      .mobile-nav-toggle {
        position: relative;
        transition: opacity 0.3s ease, visibility 0.3s ease, color 0.2s ease;
        color: var(--nav-color, #2c3e50) !important;
      }

      /* Ensure hamburger icon never inherits white color from any parent */
      #header .mobile-nav-toggle,
      #header .container .mobile-nav-toggle,
      #header * .mobile-nav-toggle {
        color: var(--nav-color, #2c3e50) !important;
      }

      /* Prevent mobile nav header from affecting hamburger icon color */
      .mobile-nav-header~* .mobile-nav-toggle,
      .mobile-navmenu .mobile-nav-toggle {
        color: var(--nav-color, #2c3e50) !important;
      }

      .mobile-nav-toggle.bi-list::before {
        content: "\f479" !important;
        display: inline-block !important;
        font-family: bootstrap-icons !important;
        font-style: normal !important;
        font-weight: normal !important;
      }

      .mobile-nav-toggle.bi-x,
      .mobile-nav-toggle.bi-x::before {
        display: none !important;
        content: none !important;
      }

      /* Show hamburger icon by default (when menu is closed) - only on mobile */
      @media (max-width: 1199px) {
        .mobile-nav-toggle.bi-list {
          display: block !important;
          visibility: visible !important;
          opacity: 1 !important;
          pointer-events: auto !important;
          color: var(--nav-color, #2c3e50) !important;
        }

        /* Hide hamburger icon when menu is open - use class-based approach */
        .menu-open .mobile-nav-toggle,
        .mobile-navmenu.active~.mobile-nav-toggle {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
          pointer-events: none !important;
        }
      }

      /* Hide hamburger icon inside mobile menu sidebar only */
      .mobile-navmenu .mobile-nav-toggle,
      #mobile-navmenu .mobile-nav-toggle,
      .mobile-navmenu * .mobile-nav-toggle {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
      }

      /* Ensure hamburger icon is only visible in main header container, not in mobile menu - only on mobile */
      @media (max-width: 1199px) {
        #header .container .mobile-nav-toggle {
          position: relative !important;
          display: block !important;
          visibility: visible !important;
          opacity: 1 !important;
        }

        /* Override any hiding rules for hamburger in header when menu is closed */
        #header .container .mobile-nav-toggle:not(.bi-x) {
          display: block !important;
          visibility: visible !important;
          opacity: 1 !important;
        }

        /* Force hamburger icon to be visible by default - highest priority */
        #header .container .mobile-nav-toggle.bi-list {
          display: block !important;
          visibility: visible !important;
          opacity: 1 !important;
          color: var(--nav-color, #2c3e50) !important;
        }

        /* Only hide when menu is actually open */
        body.menu-open #header .container .mobile-nav-toggle,
        .mobile-navmenu.active~#header .container .mobile-nav-toggle {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
        }
      }

      /* Mobile Navigation Overlay */
      .mobile-nav-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
      }

      .mobile-nav-overlay.active {
        opacity: 1;
        visibility: visible;
      }

      /* Mobile Navigation Container - Slide from Right */
      .mobile-navmenu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 85%;
        max-width: 380px;
        height: 100vh;
        background: #ffffff;
        z-index: 9999;
        overflow-y: auto;
        overflow-x: hidden;
        transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -4px 0 24px rgba(0, 0, 0, 0.2);
        -webkit-overflow-scrolling: touch;
        display: flex;
        flex-direction: column;
      }

      .mobile-navmenu.active {
        right: 0;
      }

      /* Mobile Nav Header */
      .mobile-nav-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 20px;
        background: linear-gradient(135deg, var(--nav-color, #2c3e50) 0%, var(--nav-hover-color, #1a252f) 100%);
        color: #ffffff;
        border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
      }

      .mobile-nav-header * {
        box-sizing: border-box;
      }

      .mobile-nav-title {
        font-size: 20px;
        font-weight: 700;
        font-family: var(--nav-font);
        letter-spacing: 0.5px;
      }

      .mobile-nav-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #ffffff;
        font-size: 36px;
        cursor: pointer;
        padding: 0;
        margin: 0;
        width: 42px;
        height: 42px;
        min-width: 42px;
        min-height: 42px;
        max-width: 42px;
        max-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
        line-height: 1;
        position: relative;
        overflow: hidden;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
      }

      .mobile-nav-close::before,
      .mobile-nav-close::after {
        content: none !important;
        display: none !important;
      }

      .mobile-nav-close .close-icon {
        display: flex !important;
        align-items: center;
        justify-content: center;
        line-height: 1;
        font-style: normal;
        font-weight: 300;
        color: #ffffff;
        font-size: 20px;
        width: 100%;
        height: 100%;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
      }

      .mobile-nav-close .close-icon::before,
      .mobile-nav-close .close-icon::after {
        display: none !important;
        content: none !important;
      }

      /* Prevent any Bootstrap icon classes from affecting close button */
      .mobile-nav-close.bi,
      .mobile-nav-close[class*="bi-"],
      .mobile-nav-close .close-icon.bi,
      .mobile-nav-close .close-icon[class*="bi-"] {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
      }

      .mobile-nav-close.bi::before,
      .mobile-nav-close[class*="bi-"]::before,
      .mobile-nav-close .close-icon.bi::before,
      .mobile-nav-close .close-icon[class*="bi-"]::before {
        display: none !important;
        content: none !important;
      }

      .mobile-nav-close:active {
        transform: scale(0.9);
        background: rgba(255, 255, 255, 0.2);
      }

      /* Mobile Nav List */
      .mobile-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
        overflow-y: auto;
      }

      .mobile-nav-list>li {
        border-bottom: 1px solid #e8e8e8;
      }

      .mobile-nav-list>li:last-child {
        border-bottom: none;
      }

      .mobile-nav-list>li>a {
        display: block;
        padding: 18px 20px;
        color: #2c3e50;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        font-family: var(--nav-font);
        transition: all 0.2s ease;
        position: relative;
      }

      .mobile-nav-list>li>a::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: var(--accent-color, #e74c3c);
        transform: scaleY(0);
        transition: transform 0.2s ease;
      }

      .mobile-nav-list>li>a:active::before,
      .mobile-nav-list>li>a.active::before {
        transform: scaleY(1);
      }

      .mobile-nav-list>li>a:active {
        background-color: #f8f9fa;
        color: var(--nav-hover-color);
      }

      .mobile-nav-list>li>a.active {
        background-color: #f0f4f8;
        color: var(--nav-color);
        font-weight: 600;
      }

      /* Mobile Dropdown Styles */
      .mobile-dropdown {
        position: relative;
      }

      .mobile-dropdown-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        background: transparent;
        border: none;
        color: #2c3e50;
        font-size: 16px;
        font-weight: 500;
        font-family: var(--nav-font);
        text-align: left;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
      }

      .mobile-dropdown-btn:active {
        background-color: #f8f9fa;
      }

      .mobile-dropdown-btn i {
        font-size: 16px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--accent-color, #e74c3c);
      }

      .mobile-dropdown-btn.expanded i {
        transform: rotate(180deg);
      }

      /* Dropdown Content */
      .mobile-dropdown-content {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 0;
        overflow: hidden;
        background: #f8f9fa;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .mobile-dropdown-content.show {
        max-height: 2500px;
      }

      .mobile-dropdown-content li {
        border-bottom: 1px solid #e0e3e7;
      }

      .mobile-dropdown-content li:last-child {
        border-bottom: none;
      }

      .mobile-dropdown-content>li>a {
        display: block;
        padding: 15px 20px 15px 36px;
        color: #495057;
        text-decoration: none;
        font-size: 15px;
        font-family: var(--nav-font);
        transition: all 0.2s ease;
        position: relative;
      }

      .mobile-dropdown-content>li>a::before {
        content: '→';
        position: absolute;
        left: 20px;
        opacity: 0;
        transition: all 0.2s ease;
      }

      .mobile-dropdown-content>li>a:active {
        background-color: #e9ecef;
        color: var(--nav-hover-color);
      }

      .mobile-dropdown-content>li>a:active::before {
        opacity: 1;
        left: 22px;
      }

      /* Level 2 Dropdown Button */
      .mobile-dropdown-btn.level-2 {
        padding-left: 36px;
        font-size: 15px;
        background: #f8f9fa;
        font-weight: 500;
      }

      .mobile-dropdown-btn.level-2:active {
        background-color: #f8f9fa;
      }

      /* Level 2 Dropdown Content */
      .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content {
        background: #eff2f5;
      }

      .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content>li>a {
        padding-left: 52px;
        font-size: 14px;
        color: #5a6c7d;
      }

      .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content>li>a:active {
        background-color: #e9ecef;
        color: var(--nav-hover-color);
      }

      /* Level 3 Dropdown Button */
      .mobile-dropdown-btn.level-3 {
        padding-left: 52px;
        font-size: 14px;
        background: #eff2f5;
        font-weight: 500;
      }

      .mobile-dropdown-btn.level-3:active {
        background-color: #f8f9fa;
      }

      /* Level 3 Dropdown Content */
      .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content .mobile-dropdown-content {
        background: #ffffff;
      }

      .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content .mobile-dropdown-content>li>a {
        padding-left: 68px;
        font-size: 13px;
        color: #6c757d;
      }

      .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content .mobile-dropdown-content>li>a:active {
        background-color: #e9ecef;
        color: var(--nav-hover-color);
      }

      /* Responsive adjustments for smaller screens */
      @media (max-width: 480px) {
        .mobile-navmenu {
          width: 92%;
          max-width: 100%;
        }

        .mobile-nav-header {
          padding: 18px 16px;
        }

        .mobile-nav-title {
          font-size: 18px;
        }

        .mobile-nav-close {
          font-size: 32px;
          width: 38px;
          height: 38px;
          min-width: 38px;
          min-height: 38px;
          max-width: 38px;
          max-height: 38px;
        }

        .mobile-nav-list>li>a,
        .mobile-dropdown-btn {
          padding: 16px 16px;
          font-size: 15px;
        }

        .mobile-dropdown-btn.level-2 {
          padding-left: 32px;
          font-size: 14px;
        }

        .mobile-dropdown-content>li>a {
          padding: 14px 16px 14px 32px;
          font-size: 14px;
        }

        .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content>li>a {
          padding-left: 48px;
          font-size: 13px;
        }

        .mobile-dropdown-btn.level-3 {
          padding-left: 48px;
          font-size: 13px;
        }

        .mobile-dropdown .mobile-dropdown-content .mobile-dropdown-content .mobile-dropdown-content>li>a {
          padding-left: 64px;
          font-size: 12px;
        }
      }

      /* ========== MOBILE NAVIGATION CONTACT INFO & BUTTON ========== */
      .mobile-nav-contact {
        padding: 20px;
        border-top: 2px solid #e8e8e8;
        background: #f8f9fa;
        margin-top: auto;
      }

      .mobile-contact-info {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
      }

      .mobile-contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #2c3e50;
        text-decoration: none;
        font-size: 14px;
        font-family: var(--nav-font);
        font-weight: 500;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
        background: #ffffff;
      }

      .mobile-contact-item i {
        font-size: 18px;
        color: var(--accent-color, #01337e);
        min-width: 20px;
      }

      .mobile-contact-item:active {
        background-color: #e9ecef;
        color: var(--nav-hover-color);
      }

      .mobile-btn-getstarted {
        display: block;
        width: 100%;
        text-align: center;
        padding: 12px 24px;
        background: var(--accent-color, #01337e);
        color: #ffffff;
        text-decoration: none;
        font-size: 16px;
        font-weight: 600;
        font-family: var(--nav-font);
        border-radius: 6px;
        transition: all 0.3s ease;
      }

      .mobile-btn-getstarted:active {
        background: var(--nav-hover-color, #01337e);
        transform: scale(0.98);
      }
    </style>


    <script type="text/javascript">
      function googleTranslateElementInit() {
        new google.translate.TranslateElement({
          pageLanguage: '<?php echo $pageLanguage ?? 'en'; ?>',
          includedLanguages: 'en,th,ko,pt,pl,es',
          layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
          autoDisplay: false
        }, 'google_translate_element');
      }

      function changeLanguage(lang) {
        var pageLang = '<?php echo $pageLanguage ?? 'en'; ?>';
        // Clear existing cookies
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname;

        // Set new cookie
        if (lang !== pageLang) {
          document.cookie = 'googtrans=/' + pageLang + '/' + lang + '; path=/;';
        }

        location.reload();
      }

      document.addEventListener('DOMContentLoaded', function () {
        // ===== LANGUAGE DROPDOWN =====
        var dropdownToggle = document.getElementById('languageDropdown');
        var dropdownMenu = document.getElementById('languageMenu');

        if (dropdownToggle && dropdownMenu) {
          dropdownToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            var isExpanded = dropdownMenu.classList.contains('show');
            dropdownToggle.setAttribute('aria-expanded', isExpanded);
          });

          document.addEventListener('click', function (e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
              dropdownMenu.classList.remove('show');
              dropdownToggle.setAttribute('aria-expanded', 'false');
            }
          });
        }

        // ===== MOBILE HAMBURGER MENU SYSTEM =====
        var navmenu = document.getElementById('navmenu');
        var mobileNavmenu = document.getElementById('mobile-navmenu');
        var mobileNavOverlay = document.getElementById('mobile-nav-overlay');
        var mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        var mobileNavClose = document.querySelector('.mobile-nav-close');
        var isDesktop = window.innerWidth >= 1200;

        /**
         * Close all dropdowns at a specific level
         */
        function closeAllDropdowns() {
          // Close desktop dropdowns
          document.querySelectorAll('.dropdown-level-1.show, .dropdown-level-2.show, .dropdown-level-3.show').forEach(function (menu) {
            menu.classList.remove('show');
          });

          document.querySelectorAll('.dropdown-toggle.expanded').forEach(function (toggle) {
            toggle.classList.remove('expanded');
          });

          // Close mobile dropdowns
          document.querySelectorAll('.mobile-dropdown-content.show').forEach(function (menu) {
            menu.classList.remove('show');
          });

          document.querySelectorAll('.mobile-dropdown-btn.expanded').forEach(function (toggle) {
            toggle.classList.remove('expanded');
          });
        }

        /**
         * Close mobile menu completely
         */
        function closeMobileMenu() {
          if (navmenu) {
            navmenu.classList.remove('active');
          }
          if (mobileNavmenu) {
            mobileNavmenu.classList.remove('active');
          }
          if (mobileNavOverlay) {
            mobileNavOverlay.classList.remove('active');
          }
          // Remove menu-open class to show hamburger icon
          document.body.classList.remove('menu-open');
          closeAllDropdowns();

          // Re-enable body scroll - ensure it's always reset (use setTimeout to ensure it happens after animations)
          setTimeout(function () {
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.documentElement.style.overflow = '';
            document.documentElement.style.position = '';
          }, 100);

          // Ensure hamburger icon has correct classes and color
          if (mobileNavToggle && window.innerWidth < 1200) {
            mobileNavToggle.classList.remove('bi-x');
            mobileNavToggle.classList.add('bi-list');
            mobileNavToggle.style.display = 'block';
            mobileNavToggle.style.visibility = 'visible';
            mobileNavToggle.style.opacity = '1';

            // Force correct color - use setTimeout to ensure it happens after any CSS transitions
            setTimeout(function () {
              // Remove any inline color to let CSS take over
              mobileNavToggle.style.color = '';
              // Force reflow to ensure CSS is applied
              mobileNavToggle.offsetHeight;
            }, 100);
          }
        }

        /**
         * Toggle dropdown menu
         */
        function toggleDropdownMenu(e) {
          e.preventDefault();
          e.stopPropagation();

          var toggle = this;
          var isCurrentlyExpanded = toggle.classList.contains('expanded');

          // Find the associated dropdown menu
          var dropdownMenu = null;
          if (toggle.closest('.mega-menu')) {
            dropdownMenu = toggle.closest('.mega-menu').querySelector('.dropdown-level-1');
          } else if (toggle.closest('.dropdown-level-1 > li.dropdown')) {
            dropdownMenu = toggle.closest('.dropdown-level-1 > li.dropdown').querySelector('.dropdown-level-2');
          } else if (toggle.closest('.dropdown-level-2 > li.dropdown')) {
            dropdownMenu = toggle.closest('.dropdown-level-2 > li.dropdown').querySelector('.dropdown-level-3');
          }

          if (!dropdownMenu) return;

          // Close other dropdowns at the same level
          var parentElement = toggle.closest('.mega-menu') || toggle.closest('.dropdown-level-1') || toggle.closest('.dropdown-level-2');
          if (parentElement) {
            var siblings = parentElement.querySelectorAll('.dropdown-toggle.expanded');
            siblings.forEach(function (sibling) {
              if (sibling !== toggle) {
                sibling.classList.remove('expanded');
                var siblingMenu = null;
                if (sibling.closest('.mega-menu')) {
                  siblingMenu = sibling.closest('.mega-menu').querySelector('.dropdown-level-1');
                } else if (sibling.closest('.dropdown-level-1 > li.dropdown')) {
                  siblingMenu = sibling.closest('.dropdown-level-1 > li.dropdown').querySelector('.dropdown-level-2');
                } else if (sibling.closest('.dropdown-level-2 > li.dropdown')) {
                  siblingMenu = sibling.closest('.dropdown-level-2 > li.dropdown').querySelector('.dropdown-level-3');
                }
                if (siblingMenu) {
                  siblingMenu.classList.remove('show');
                }
              }
            });
          }

          // Toggle current dropdown
          toggle.classList.toggle('expanded');
          dropdownMenu.classList.toggle('show');
        }

        /**
         * Initialize hamburger menu on mobile
         */
        function initializeHamburgerMenu() {
          if (!mobileNavToggle) return;

          // Open mobile menu
          mobileNavToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            if (mobileNavmenu && mobileNavOverlay) {
              mobileNavmenu.classList.add('active');
              mobileNavOverlay.classList.add('active');
              // Add class to body to hide hamburger icon
              document.body.classList.add('menu-open');
              // Prevent body scroll when menu is open
              document.body.style.overflow = 'hidden';
            }
          });

          // Close button
          if (mobileNavClose) {
            mobileNavClose.addEventListener('click', function (e) {
              e.preventDefault();
              closeMobileMenu();
            });
          }

          // Close when clicking overlay
          if (mobileNavOverlay) {
            mobileNavOverlay.addEventListener('click', function (e) {
              closeMobileMenu();
            });
          }
        }

        /**
         * Initialize dropdown toggles for Products menu
         */
        function initializeDropdownToggles() {
          // Desktop navigation dropdowns
          var megaMenuToggle = document.querySelector('.navmenu .mega-menu > .dropdown-toggle');
          if (megaMenuToggle) {
            megaMenuToggle.addEventListener('click', toggleDropdownMenu);
          }

          document.querySelectorAll('.navmenu .dropdown-level-1 > li.dropdown > .dropdown-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', toggleDropdownMenu);
          });

          document.querySelectorAll('.navmenu .dropdown-level-2 > li.dropdown > .dropdown-toggle').forEach(function (toggle) {
            toggle.addEventListener('click', toggleDropdownMenu);
          });

          // Mobile navigation dropdowns
          document.querySelectorAll('.mobile-navmenu .mobile-dropdown-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
              e.preventDefault();
              e.stopPropagation();

              var isExpanded = this.classList.contains('expanded');
              var dropdownContent = this.nextElementSibling;

              // Close siblings at the same level
              var parent = this.closest('li').parentElement;
              if (parent) {
                parent.querySelectorAll(':scope > li > .mobile-dropdown-btn.expanded').forEach(function (sibling) {
                  if (sibling !== btn) {
                    sibling.classList.remove('expanded');
                    var siblingContent = sibling.nextElementSibling;
                    if (siblingContent) {
                      siblingContent.classList.remove('show');
                    }
                  }
                });
              }

              // Toggle current dropdown
              this.classList.toggle('expanded');
              if (dropdownContent) {
                dropdownContent.classList.toggle('show');
              }
            });
          });
        }

        /**
         * Close menu when clicking on actual navigation links
         */
        function attachLinkClickHandlers() {
          // Desktop navigation links
          document.querySelectorAll('.navmenu a:not(.dropdown-toggle)').forEach(function (link) {
            link.addEventListener('click', function (e) {
              if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                closeMobileMenu();
              }
            });
          });

          // Mobile navigation links
          document.querySelectorAll('.mobile-navmenu a').forEach(function (link) {
            link.addEventListener('click', function (e) {
              if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                closeMobileMenu();
              }
            });
          });
        }

        /**
         * Close menu when clicking outside
         */
        document.addEventListener('click', function (e) {
          if (navmenu && !navmenu.contains(e.target) && !mobileNavToggle?.contains(e.target)) {
            if (navmenu.classList.contains('active')) {
              closeMobileMenu();
            }
          }
        });

        /**
         * Handle window resize
         */
        var resizeTimer;
        window.addEventListener('resize', function () {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(function () {
            var isMobileNow = window.innerWidth < 1200;

            if (isDesktop && !isMobileNow) {
              // Still desktop
              return;
            } else if (!isDesktop && isMobileNow) {
              // Still mobile
              return;
            } else if (isDesktop && isMobileNow) {
              // Changed from desktop to mobile
              isDesktop = false;
              closeMobileMenu();
            } else {
              // Changed from mobile to desktop
              isDesktop = true;
              closeMobileMenu();
            }
          }, 250);
        });

        /**
         * Update language status from cookie
         */
        function updateLanguageStatus() {
          var cookies = document.cookie.split(';');
          var targetLang = '';

          for (var i = 0; i < cookies.length; i++) {
            var c = cookies[i].trim();
            if (c.indexOf('googtrans=') === 0) {
              var val = c.substring('googtrans='.length);
              var parts = val.split('/');
              if (parts.length === 3) {
                targetLang = parts[2];
              }
              break;
            }
          }

          var langMap = {
            'en': 'English',
            'ko': 'Korean',
            'pl': 'Polish',
            'pt': 'Portuguese',
            'es': 'Spanish',
            'th': 'Thai'
          };

          if (targetLang && langMap[targetLang]) {
            document.getElementById('currentLangText').textContent = langMap[targetLang];
            document.querySelectorAll('.lang-dropdown .dropdown-item').forEach(function (item) {
              item.classList.remove('active-lang');
              if (item.textContent.includes(langMap[targetLang])) {
                item.classList.add('active-lang');
              }
            });
          }
        }

        // ===== INITIALIZE ALL SYSTEMS =====
        // Ensure hamburger icon is visible on page load (only on mobile)
        if (mobileNavToggle) {
          if (window.innerWidth < 1200) {
            // Mobile: show hamburger icon with correct color
            mobileNavToggle.classList.remove('bi-x');
            mobileNavToggle.classList.add('bi-list');
            mobileNavToggle.style.display = 'block';
            mobileNavToggle.style.visibility = 'visible';
            mobileNavToggle.style.opacity = '1';
            // Ensure correct color
            var navColor = getComputedStyle(document.documentElement).getPropertyValue('--nav-color') || '#2c3e50';
            mobileNavToggle.style.color = navColor;
          } else {
            // Desktop: hide hamburger icon
            mobileNavToggle.style.display = 'none';
            mobileNavToggle.style.visibility = 'hidden';
            mobileNavToggle.style.opacity = '0';
          }
        }

        initializeHamburgerMenu();
        initializeDropdownToggles();
        attachLinkClickHandlers();
        updateLanguageStatus();
      });
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Contact Information, Language Dropdown and Get Started Button - Desktop Only (hidden when hamburger shows) -->
    <div class="header-right-section d-none d-xl-flex flex-column align-items-end">
      <!-- Contact Information - Above Button -->
      <div class="header-contact-info">
        <a href="tel:+918200012841" class="header-contact-item">
          <i class="bi bi-telephone"></i>
          <span><?php echo htmlspecialchars($phoneNumber); ?></span>
        </a>
        <a href="mailto:<?php echo htmlspecialchars($emailAddress); ?>" class="header-contact-item">
          <i class="bi bi-envelope"></i>
          <span><?php echo htmlspecialchars($emailAddress); ?></span>
        </a>
      </div>
      <!-- Language Dropdown and Get Started Button Row -->
      <div class="header-actions d-flex align-items-center gap-3">
        <div class="dropdown lang-dropdown">
          <button class="btn dropdown-toggle" type="button" id="languageDropdown" aria-expanded="false">
            <i class="bi bi-translate"></i> <span id="currentLangText">Select Language</span> <i
              class="bi bi-chevron-down" style="font-size: 12px;"></i>
          </button>
          <ul class="dropdown-menu" id="languageMenu">
            <li><a class="dropdown-item" onclick="changeLanguage('en')">English</a></li>
            <li><a class="dropdown-item" onclick="changeLanguage('ko')">Korean</a></li>
            <li><a class="dropdown-item" onclick="changeLanguage('pl')">Polish</a></li>
            <li><a class="dropdown-item" onclick="changeLanguage('pt')">Portuguese (Brazil)</a></li>
            <li><a class="dropdown-item" onclick="changeLanguage('es')">Spanish</a></li>
            <li><a class="dropdown-item" onclick="changeLanguage('th')">Thai</a></li>
          </ul>
        </div>
        <!-- Get Started Button -->
        <a class="btn-getstarted" href="/contact.php">Get Started</a>
      </div>
    </div>

    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

  </div>
</header>