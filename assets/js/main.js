(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToggle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToggle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToggle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns - Enhanced for 3-level navigation
   */
  if (window.innerWidth < 1200) {
    // Handle main dropdown toggle (Products)
    const megaMenu = document.querySelector('.navmenu .mega-menu > a');
    if (megaMenu) {
      megaMenu.addEventListener('click', function(e) {
        if (window.innerWidth < 1200) {
          e.preventDefault();
          const level1 = this.closest('.mega-menu').querySelector('.dropdown-level-1');
          if (level1) {
            level1.classList.toggle('show');
            this.classList.toggle('expanded');
          }
        }
      });
    }

    // Handle level-2 dropdown toggles (Categories within Products)
    document.querySelectorAll('.navmenu .dropdown-level-2 > li.dropdown > a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (window.innerWidth < 1200) {
          e.preventDefault();
          const level3 = this.closest('li').querySelector('.dropdown-level-3');
          if (level3) {
            level3.classList.toggle('show');
            this.classList.toggle('expanded');
          }
        }
      });
    });

    // Handle level-1 dropdown toggles (Category groups)
    document.querySelectorAll('.navmenu .dropdown-level-1 > li.dropdown > a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (window.innerWidth < 1200) {
          e.preventDefault();
          const level2 = this.closest('li').querySelector('.dropdown-level-2');
          if (level2) {
            level2.classList.toggle('show');
            this.classList.toggle('expanded');
          }
        }
      });
    });
  }

  /**
   * Old toggle-dropdown handler - kept for compatibility
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      const parentLi = this.closest('li');
      const submenu = parentLi.querySelector('ul');
      const link = parentLi.querySelector('a');

      if (submenu) {
        submenu.classList.toggle('dropdown-active');
        link.classList.toggle('active');
        
        // Update aria-expanded
        const isExpanded = submenu.classList.contains('dropdown-active');
        link.setAttribute('aria-expanded', isExpanded);
      }
      
      e.stopImmediatePropagation();
    });
  });

  /**
   * Desktop Dropdown Accessibility & Delay
   */
  if (window.innerWidth >= 1200) {
    document.querySelectorAll('.navmenu .dropdown').forEach(dropdown => {
      const toggle = dropdown.querySelector('.dropdown-toggle');
      
      dropdown.addEventListener('mouseenter', () => {
        if (toggle) toggle.setAttribute('aria-expanded', 'true');
      });
      
      dropdown.addEventListener('mouseleave', () => {
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Initiate glightbox
   */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /**
   * Init isotope layout and filters
   */
  document.querySelectorAll('.isotope-layout').forEach(function(isotopeItem) {
    let layout = isotopeItem.getAttribute('data-layout') ?? 'masonry';
    let filter = isotopeItem.getAttribute('data-default-filter') ?? '*';
    let sort = isotopeItem.getAttribute('data-sort') ?? 'original-order';

    let initIsotope;
    imagesLoaded(isotopeItem.querySelector('.isotope-container'), function() {
      initIsotope = new Isotope(isotopeItem.querySelector('.isotope-container'), {
        itemSelector: '.isotope-item',
        layoutMode: layout,
        filter: filter,
        sortBy: sort
      });
    });

    isotopeItem.querySelectorAll('.isotope-filters li').forEach(function(filters) {
      filters.addEventListener('click', function() {
        isotopeItem.querySelector('.isotope-filters .filter-active').classList.remove('filter-active');
        this.classList.add('filter-active');
        initIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        if (typeof aosInit === 'function') {
          aosInit();
        }
      }, false);
    });

  });

  /**
   * Frequently Asked Questions Toggle
   */
  document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle, .faq-item .faq-header').forEach((faqItem) => {
    faqItem.addEventListener('click', () => {
      faqItem.parentNode.classList.toggle('faq-active');
    });
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Correct scrolling position upon page load for URLs containing hash links.
   */
  window.addEventListener('load', function(e) {
    if (window.location.hash) {
      if (document.querySelector(window.location.hash)) {
        setTimeout(() => {
          let section = document.querySelector(window.location.hash);
          let scrollMarginTop = getComputedStyle(section).scrollMarginTop;
          window.scrollTo({
            top: section.offsetTop - parseInt(scrollMarginTop),
            behavior: 'smooth'
          });
        }, 100);
      }
    }
  });

  /**
   * Navmenu Scrollspy
   */
  let navmenulinks = document.querySelectorAll('.navmenu a');

  function navmenuScrollspy() {
    navmenulinks.forEach(navmenulink => {
      if (!navmenulink.hash) return;
      let section = document.querySelector(navmenulink.hash);
      if (!section) return;
      let position = window.scrollY + 200;
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
        navmenulink.classList.add('active');
      } else {
        navmenulink.classList.remove('active');
      }
    })
  }
  window.addEventListener('load', navmenuScrollspy);
  document.addEventListener('scroll', navmenuScrollspy);

})();