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
?>
<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container position-relative d-flex align-items-center justify-content-between">

    <a href="/index.php" class="logo d-flex align-items-center me-auto me-xl-0">
      <img src="/assets/img/logo.png" alt="LYNOPACK - Packaging Machine Manufacturer" loading="lazy">
    </a>

    <nav id="navmenu" class="navmenu" role="navigation" aria-label="Main Navigation">
      <ul role="menubar">
        <li role="none"><a href="/index.php" role="menuitem"<?php echo $activePage === 'home' ? ' class="active"' : ''; ?>>Home</a></li>
        <li role="none"><a href="/about.php" role="menuitem"<?php echo $activePage === 'about' ? ' class="active"' : ''; ?>>About</a></li>
        <li role="none"><a href="/gallery.php" role="menuitem"<?php echo $activePage === 'gallery' ? ' class="active"' : ''; ?>>Gallery</a></li>
        <li role="none"><a href="/blog.php" role="menuitem"<?php echo $activePage === 'blog' ? ' class="active"' : ''; ?>>Blogs</a></li>
        
        <!-- Products Mega Dropdown (3-Level) -->
        <li class="dropdown mega-menu" role="none">
          <a href="#" role="menuitem" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><span>Products</span></a>
          <ul role="menu" class="dropdown-level-1">
            <?php foreach ($navigation as $groupSlug => $group): ?>
              <?php
  $subcats = $group['subcategories'] ?? [];
  $hasSubcats = count($subcats) > 1 || (count($subcats) === 1 && $subcats[0] !== $groupSlug);
?>
              <?php if ($hasSubcats): ?>
                <li class="dropdown" role="none">
                  <a href="#" role="menuitem" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><span><?php echo htmlspecialchars($group['name']); ?></span></a>
                  <ul role="menu" class="dropdown-level-2">
                    <?php foreach ($subcats as $subcatSlug): ?>
                      <?php $subcat = $categories[$subcatSlug] ?? null; ?>
                      <?php if ($subcat): ?>
                        <?php $subcatProducts = $subcat['products'] ?? []; ?>
                        <?php if (count($subcatProducts) > 0): ?>
                          <li class="dropdown" role="none">
                            <a href="<?php echo getCategoryUrl($subcatSlug, $navigation); ?>" role="menuitem" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                              <span><?php echo htmlspecialchars($subcat['name']); ?></span> <i class="bi bi-chevron-right toggle-dropdown"></i>
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
                            <a href="<?php echo getCategoryUrl($subcatSlug, $navigation); ?>" role="menuitem"><?php echo htmlspecialchars($subcat['name']); ?></a>
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
                    <a href="<?php echo getCategoryUrl($singleSubcatSlug, $navigation); ?>" role="menuitem" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">
                      <span><?php echo htmlspecialchars($group['name']); ?></span>
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
                    <a href="<?php echo getCategoryUrl($singleSubcatSlug, $navigation); ?>" role="menuitem"><?php echo htmlspecialchars($group['name']); ?></a>
                  </li>
                <?php
    endif; ?>
              <?php
  endif; ?>
            <?php
endforeach; ?>
          </ul>
        </li>
        
        <li role="none"><a href="/contact.php" role="menuitem"<?php echo $activePage === 'contact' ? ' class="active"' : ''; ?>>Contact</a></li>
      </ul>
    </nav>

    <!-- Hidden Google Translate Element -->
    <div id="google_translate_element" style="display:none"></div>

    <!-- Custom Language Dropdown & Mobile Responsive Styles -->
    <style>
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
        display: none; /* Hidden by default */
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 10000; /* Higher than header */
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
        display: block; /* Shown when active */
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
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
      }

      /* ========== MOBILE HAMBURGER & NAVIGATION STYLES ========== */
      /* Desktop First Approach: Default styles for desktop, then override for mobile */
      
      /* Mobile Responsive Navigation - Hide navmenu on mobile by default */
      @media (max-width: 1199px) {
        /* Hide main navigation on mobile/tablet by default */
        .navmenu {
          position: fixed;
          top: 80px;
          left: 0;
          right: 0;
          width: 100%;
          height: calc(100vh - 80px);
          background: white;
          z-index: 9998;
          overflow-y: auto;
          overflow-x: hidden;
          display: none;
          flex-direction: column;
          padding: 0;
          margin: 0;
          box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        /* Show navmenu when .active class is added */
        .navmenu.active {
          display: flex;
          animation: slideInMenu 0.3s ease-out;
        }

        @keyframes slideInMenu {
          from {
            opacity: 0;
            transform: translateX(-20px);
          }
          to {
            opacity: 1;
            transform: translateX(0);
          }
        }

        /* Main menu items (Home, About, Gallery, Blogs, Contact) */
        .navmenu > ul {
          width: 100%;
          flex-direction: column;
          padding: 0;
          margin: 0;
          list-style: none;
          display: flex;
        }

        .navmenu > ul > li {
          width: 100%;
          margin: 0;
          padding: 0;
          border-bottom: 1px solid #f0f0f0;
        }

        .navmenu > ul > li > a {
          display: block;
          padding: 14px 15px;
          color: var(--nav-dropdown-color);
          text-decoration: none;
          font-size: 16px;
          font-weight: 500;
          transition: background-color 0.2s ease, color 0.2s ease;
        }

        .navmenu > ul > li > a:active {
          background-color: #f5f5f5;
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
          padding: 5px 15px;
          transition: transform 0.3s ease, color 0.2s ease;
        }

        .mobile-nav-toggle:active {
          color: var(--nav-hover-color);
        }
      }

      /* Product mega dropdown on mobile - Level 1 */
      @media (max-width: 1199px) {
        .navmenu ul.dropdown-level-1 {
          position: static;
          width: 100%;
          background: #f9f9f9;
          border-left: 3px solid var(--accent-color);
          list-style: none;
          padding: 0;
          margin: 0;
          box-sizing: border-box;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
          display: block;
        }

        .navmenu ul.dropdown-level-1.show {
          max-height: 3000px;
        }

        /* Level 2 Dropdowns - Categories under Product Groups */
        .navmenu .dropdown-level-2 {
          position: static;
          width: 100%;
          background: #f0f0f0;
          padding-left: 20px;
          list-style: none;
          margin: 0 !important;
          padding-top: 0 !important;
          padding-bottom: 0 !important;
          padding-right: 0 !important;
          border-left: 3px solid var(--nav-color);
          box-sizing: border-box;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
          display: block;
        }

        .navmenu .dropdown-level-2.show {
          max-height: 2000px;
        }

        /* Level 3 Dropdowns - Individual Products */
        .navmenu .dropdown-level-3 {
          position: static;
          width: 100%;
          background: #f5f5f5;
          padding-left: 20px;
          list-style: none;
          margin: 0 !important;
          padding-top: 0 !important;
          padding-bottom: 0 !important;
          padding-right: 0 !important;
          border-left: 3px solid #999;
          box-sizing: border-box;
          max-height: 0;
          overflow: hidden;
          transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
          display: block;
        }

        .navmenu .dropdown-level-3.show {
          max-height: 2500px;
        }

        /* List items and remove default margins/padding */
        .navmenu .dropdown-level-2 > li,
        .navmenu .dropdown-level-3 > li {
          margin: 0 !important;
          padding: 0 !important;
          list-style: none;
          line-height: 1;
        }

        .navmenu .dropdown-level-2 > li.dropdown,
        .navmenu .dropdown-level-3 > li.dropdown {
          margin: 0 !important;
          padding: 0 !important;
        }

        /* Links in dropdowns - Touch-friendly sizing */
        .navmenu .dropdown-level-2 > li > a,
        .navmenu .dropdown-level-3 > li > a {
          display: block;
          padding: 12px 15px !important;
          margin: 0 !important;
          text-decoration: none;
          color: var(--nav-dropdown-color);
          transition: background-color 0.2s ease, color 0.2s ease;
          line-height: 1.4;
          font-size: 15px;
        }

        /* Remove hover effects on touch devices - Touch devices use :active instead */
        .navmenu .dropdown-level-2 > li > a:active,
        .navmenu .dropdown-level-3 > li > a:active {
          background-color: #dcdcdc;
          color: var(--nav-dropdown-hover-color);
        }

        /* Toggle indicator for dropdowns (+ and X) */
        .navmenu .dropdown > a.dropdown-toggle {
          position: relative;
          padding-right: 40px;
          display: block;
          padding-top: 14px;
          padding-bottom: 14px;
        }

        .navmenu .dropdown > a.dropdown-toggle::after {
          content: '+';
          position: absolute;
          right: 15px;
          top: 50%;
          transform: translateY(-50%);
          font-size: 20px;
          font-weight: bold;
          color: var(--accent-color);
          transition: transform 0.3s ease;
        }

        .navmenu .dropdown > a.dropdown-toggle.expanded::after {
          transform: translateY(-50%) rotate(45deg);
        }

        /* Hide old toggle-dropdown icon on mobile */
        .toggle-dropdown {
          display: none !important;
        }

        /* First-level dropdown items (Categories) */
        .navmenu .dropdown-level-1 > li.dropdown > a.dropdown-toggle {
          color: var(--nav-dropdown-color);
          font-size: 15px;
        }

        /* Style for category names in Products dropdown */
        .navmenu .dropdown-level-1 > li > a {
          display: block;
          padding: 14px 15px;
          color: var(--nav-dropdown-color);
          text-decoration: none;
          font-weight: 500;
          font-size: 15px;
          transition: background-color 0.2s ease;
        }

        .navmenu .dropdown-level-1 > li > a:active {
          background-color: #e8e8e8;
          color: var(--nav-dropdown-hover-color);
        }
      }

      /* Tablet specific adjustments (768px - 1199px) */
      @media (max-width: 1199px) and (min-width: 768px) {
        .navmenu {
          top: 75px;
          height: calc(100vh - 75px);
        }

        .navmenu > ul > li > a {
          padding: 12px 15px;
          font-size: 16px;
        }

        .navmenu .dropdown-level-2 {
          padding-left: 25px;
        }

        .navmenu .dropdown-level-3 {
          padding-left: 30px;
        }

        .navmenu .dropdown-level-2 > li > a,
        .navmenu .dropdown-level-3 > li > a {
          padding: 11px 15px !important;
          font-size: 15px;
        }

        .navmenu .dropdown > a.dropdown-toggle {
          padding-right: 40px;
        }

        .navmenu .dropdown > a.dropdown-toggle::after {
          right: 15px;
          font-size: 18px;
        }
      }

      /* Mobile specific adjustments (< 768px) */
      @media (max-width: 767px) {
        .navmenu {
          top: 70px;
          height: calc(100vh - 70px);
        }

        .navmenu > ul > li > a {
          padding: 12px 12px;
          font-size: 15px;
        }

        .navmenu .dropdown-level-2 {
          padding-left: 20px;
        }

        .navmenu .dropdown-level-3 {
          padding-left: 25px;
        }

        .navmenu .dropdown-level-2 > li > a,
        .navmenu .dropdown-level-3 > li > a {
          padding: 10px 12px !important;
          font-size: 14px;
        }

        .navmenu .dropdown > a.dropdown-toggle {
          padding-right: 35px;
          padding-top: 12px;
          padding-bottom: 12px;
        }

        .navmenu .dropdown > a.dropdown-toggle::after {
          right: 12px;
          font-size: 16px;
        }
      }

      /* Small mobile devices (< 480px) */
      @media (max-width: 479px) {
        .navmenu {
          top: 65px;
          height: calc(100vh - 65px);
          left: 8px;
          right: 8px;
          width: auto;
          border-radius: 8px;
        }

        .navmenu > ul > li > a {
          padding: 11px 10px;
          font-size: 14px;
        }

        .navmenu .dropdown-level-2 {
          padding-left: 18px;
        }

        .navmenu .dropdown-level-3 {
          padding-left: 22px;
        }

        .navmenu .dropdown-level-2 > li > a,
        .navmenu .dropdown-level-3 > li > a {
          padding: 9px 10px !important;
          font-size: 13px;
        }

        .navmenu .dropdown > a.dropdown-toggle {
          padding-right: 32px;
          padding-top: 11px;
          padding-bottom: 11px;
        }

        .navmenu .dropdown > a.dropdown-toggle::after {
          right: 10px;
          font-size: 15px;
        }

        .mobile-nav-toggle {
          padding: 5px 10px;
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
        .navmenu > ul {
          width: auto;
          flex-direction: row;
          display: flex;
        }

        .navmenu > ul > li {
          border-bottom: none;
          margin: 0 10px;
          padding: 0;
        }

        .navmenu > ul > li > a {
          padding: 10px 0;
          font-size: 15px;
          color: var(--nav-color);
        }

        .navmenu > ul > li > a:hover {
          color: var(--nav-hover-color);
          background-color: transparent;
        }

        /* Hide dropdown toggles on desktop */
        .navmenu .dropdown > a.dropdown-toggle::after {
          display: none;
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
          padding: 15px !important;
          margin-top: 5px;
          border-radius: 4px;
          flex-direction: row;
          flex-wrap: wrap;
        }

        .navmenu .mega-menu:hover > .dropdown-level-1,
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

        .navmenu .dropdown-level-1 > li.dropdown:hover > .dropdown-level-2,
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

        .navmenu .dropdown-level-2 > li.dropdown:hover > .dropdown-level-3,
        .navmenu .dropdown-level-3.show {
          display: flex;
        }

        /* Desktop dropdown items */
        .navmenu .dropdown-level-1 > li > a,
        .navmenu .dropdown-level-2 > li > a,
        .navmenu .dropdown-level-3 > li > a {
          padding: 10px 15px !important;
          font-size: 14px;
          color: #333;
          background-color: transparent;
        }

        .navmenu .dropdown-level-2 > li > a:hover,
        .navmenu .dropdown-level-3 > li > a:hover {
          background-color: #f5f5f5;
          color: var(--nav-hover-color);
        }

        /* Hide mobile hamburger on desktop */
        .mobile-nav-toggle {
          display: none !important;
        }
      }
    </style>
    
    <div class="dropdown me-3 lang-dropdown">
      <button class="btn dropdown-toggle" type="button" id="languageDropdown" aria-expanded="false">
        <i class="bi bi-translate"></i> <span id="currentLangText">Select Language</span> <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
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

      document.addEventListener('DOMContentLoaded', function() {
        // ===== LANGUAGE DROPDOWN =====
        var dropdownToggle = document.getElementById('languageDropdown');
        var dropdownMenu = document.getElementById('languageMenu');
        
        if (dropdownToggle && dropdownMenu) {
          dropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
            var isExpanded = dropdownMenu.classList.contains('show');
            dropdownToggle.setAttribute('aria-expanded', isExpanded);
          });

          document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
              dropdownMenu.classList.remove('show');
              dropdownToggle.setAttribute('aria-expanded', 'false');
            }
          });
        }

        // ===== MOBILE HAMBURGER MENU SYSTEM =====
        var navmenu = document.getElementById('navmenu');
        var mobileNavToggle = document.querySelector('.mobile-nav-toggle');
        var isDesktop = window.innerWidth >= 1200;

        /**
         * Close all dropdowns at a specific level
         */
        function closeAllDropdowns() {
          document.querySelectorAll('.dropdown-level-1.show, .dropdown-level-2.show, .dropdown-level-3.show').forEach(function(menu) {
            menu.classList.remove('show');
          });
          
          document.querySelectorAll('.dropdown-toggle.expanded').forEach(function(toggle) {
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
          closeAllDropdowns();
          if (mobileNavToggle) {
            mobileNavToggle.classList.remove('bi-x');
            mobileNavToggle.classList.add('bi-list');
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
            siblings.forEach(function(sibling) {
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

          mobileNavToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (navmenu.classList.contains('active')) {
              closeMobileMenu();
            } else {
              navmenu.classList.add('active');
              this.classList.remove('bi-list');
              this.classList.add('bi-x');
            }
          });
        }

        /**
         * Initialize dropdown toggles for Products menu
         */
        function initializeDropdownToggles() {
          // Products mega-menu toggle
          var megaMenuToggle = document.querySelector('.navmenu .mega-menu > .dropdown-toggle');
          if (megaMenuToggle) {
            megaMenuToggle.addEventListener('click', toggleDropdownMenu);
          }

          // Level-1 dropdown toggles (Category Groups)
          document.querySelectorAll('.navmenu .dropdown-level-1 > li.dropdown > .dropdown-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', toggleDropdownMenu);
          });

          // Level-2 dropdown toggles (Categories)
          document.querySelectorAll('.navmenu .dropdown-level-2 > li.dropdown > .dropdown-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', toggleDropdownMenu);
          });
        }

        /**
         * Close menu when clicking on actual navigation links
         */
        function attachLinkClickHandlers() {
          document.querySelectorAll('.navmenu a:not(.dropdown-toggle)').forEach(function(link) {
            link.addEventListener('click', function(e) {
              // Don't close if link has no href or is #
              if (this.getAttribute('href') && this.getAttribute('href') !== '#') {
                closeMobileMenu();
              }
            });
          });
        }

        /**
         * Close menu when clicking outside
         */
        document.addEventListener('click', function(e) {
          if (!navmenu.contains(e.target) && !mobileNavToggle?.contains(e.target)) {
            closeMobileMenu();
          }
        });

        /**
         * Handle window resize
         */
        var resizeTimer;
        window.addEventListener('resize', function() {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(function() {
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
            document.querySelectorAll('.lang-dropdown .dropdown-item').forEach(function(item) {
              item.classList.remove('active-lang');
              if (item.textContent.includes(langMap[targetLang])) {
                item.classList.add('active-lang');
              }
            });
          }
        }

        // ===== INITIALIZE ALL SYSTEMS =====
        initializeHamburgerMenu();
        initializeDropdownToggles();
        attachLinkClickHandlers();
        updateLanguageStatus();
      });
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <a class="btn-getstarted" href="/contact.php">Get Started</a>

    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>

  </div>
</header>
