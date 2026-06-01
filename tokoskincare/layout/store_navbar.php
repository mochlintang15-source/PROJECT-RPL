<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../config/order_helpers.php';

function renderStoreNavbar(string $active = 'home'): void
{
    static $assetsPrinted = false;
    $cartCount = currentCartCount();
    $userName = isLoggedIn() ? ($_SESSION['user']['nama'] ?? 'UserDemo') : 'UserDemo';
    ?>
    <?php if (!$assetsPrinted): $assetsPrinted = true; ?>
        <style>
            /* ============================================================
               STYLE HOMEPAGE TOKO - mengikuti preview Beauty Always
               ============================================================ */
            body {
                font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                color: #1f1f1f;
                background: #f7f5f5;
            }

            body a {
                color: inherit;
                text-decoration: none;
            }

            body button {
                font: inherit;
                cursor: pointer;
            }

            .homepage-wrapper {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 24px;
            }

            .site-header {
                padding-top: 8px;
                position: relative;
                z-index: 99;
            }

            .top-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                background: #111;
                color: #fff;
                padding: 12px 24px;
                border-radius: 16px;
                margin-bottom: 18px;
                margin-top: 15px;
            }

            .top-left {
                display: flex;
                gap: 16px;
                align-items: center;
                flex-wrap: wrap;
            }

            .promo-text {
                font-size: 0.85rem;
                letter-spacing: 0.18em;
                text-transform: uppercase;
                font-weight: 700;
            }

            .promo-subtext {
                font-size: 0.85rem;
                color: rgba(255, 255, 255, 0.8);
            }

            .top-right {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-wrap: wrap;
            }

            .top-search {
                display: flex;
                align-items: center;
                background: #fff;
                border-radius: 999px;
                overflow: hidden;
                border: 1px solid #ddd;
            }

            .top-search input {
                border: none;
                padding: 10px 16px;
                min-width: 180px;
                outline: none;
                color: #1f1f1f;
                background: #fff;
            }

            .top-search button {
                border: none;
                padding: 0 16px;
                color: #111;
                background: transparent;
                font-weight: bold;
            }

            .nav-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 20px;
                margin-bottom: 28px;
                width: 100%;
            }

            .brand-logo {
                font-size: 2rem;
                font-weight: 800;
                letter-spacing: 0.25em;
                flex-shrink: 0;
            }

            .nav-links {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 32px;
                flex-grow: 1;
            }

            .nav-links a,
            .store-nav-item > a {
                font-size: 0.95rem;
                color: #333;
                font-weight: 600;
                display: inline-block;
                padding: 8px 0;
                line-height: 1.2;
                white-space: nowrap;
            }

            .nav-links a.store-active,
            .store-nav-item > a.store-active {
                color: #b9407f;
            }

            .store-nav-item {
                position: relative;
                display: inline-flex;
                align-items: center;
            }

            .nav-extra {
                display: flex;
                align-items: center;
                gap: 18px;
                justify-content: flex-end;
                flex-shrink: 0;
            }

            .nav-extra span {
                font-weight: 700;
                white-space: nowrap;
            }

            .nav-extra a {
                color: #333;
                font-weight: 600;
                transition: color 0.2s ease;
                white-space: nowrap;
            }

            .nav-extra a:hover,
            .nav-links a:hover,
            .store-nav-item > a:hover {
                color: #b9407f;
            }

            .dropdown-panel {
                position: absolute;
                top: 100%;
                left: 50%;
                width: min(920px, calc(100vw - 56px));
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 24px;
                padding: 24px 28px;
                background: #fff;
                border-radius: 28px;
                box-shadow: 0 28px 60px rgba(34, 22, 56, 0.12);
                opacity: 0;
                visibility: hidden;
                transform: translate(-50%, 18px);
                transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
                z-index: 1000;
                min-width: 650px;
            }

            .store-nav-item:hover .dropdown-panel,
            .store-nav-item.active .dropdown-panel {
                opacity: 1;
                visibility: visible;
                transform: translate(-50%, 0);
            }

            .dropdown-column h4 {
                margin: 0 0 16px;
                font-size: 0.95rem;
                text-transform: uppercase;
                letter-spacing: 0.12em;
                color: #7a5f79;
                font-weight: 700;
            }

            .dropdown-column a {
                display: block;
                color: #3b3b3b;
                margin-bottom: 12px;
                font-size: 0.92rem;
                transition: color 0.2s ease;
            }

            .dropdown-column a:hover {
                color: #b9407f;
            }

            .user-profile-dropdown {
                position: relative;
            }

            .user-profile-btn {
                background: #fff;
                border: none;
                padding: 10px 18px;
                border-radius: 20px;
                font-weight: 600;
                color: #111;
                cursor: pointer;
                font-size: 0.95rem;
                transition: background 0.2s ease, box-shadow 0.2s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .user-profile-btn:hover {
                background: #f0f0f0;
            }

            .user-dropdown-menu {
                position: absolute;
                top: calc(100% + 8px);
                right: 0;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 12px;
                min-width: 165px;
                box-shadow: 0 12px 28px rgba(34, 22, 56, 0.12);
                opacity: 0;
                visibility: hidden;
                transform: translateY(8px);
                transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
                z-index: 1001;
            }

            .user-profile-dropdown:hover .user-dropdown-menu {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .user-menu-item {
                display: block;
                padding: 12px 16px;
                color: #333;
                font-weight: 600;
                transition: color 0.2s ease, background 0.2s ease;
            }

            .user-menu-item:hover {
                background: rgba(185, 64, 127, 0.08);
                color: #b9407f;
                border-radius: 12px;
            }

            .cart-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .cart-badge {
                min-width: 20px;
                height: 20px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 999px;
                background: #b9407f;
                color: #fff;
                font-size: 0.75rem;
                padding: 0 6px;
            }

            .store-mobile-toggle {
                display: none;
                background: #111;
                color: #fff;
                border: none;
                border-radius: 12px;
                padding: 10px 14px;
                font-weight: 700;
            }

            .store-page-title {
                color: #111;
            }

            body .btn-primary {
                background: #111;
                border-color: #111;
            }

            body .btn-primary:hover,
            body .btn-primary:focus {
                background: #b9407f;
                border-color: #b9407f;
            }

            body .btn-outline-primary {
                color: #111;
                border-color: #111;
            }

            body .btn-outline-primary:hover,
            body .btn-outline-primary:focus {
                background: #111;
                border-color: #111;
                color: #fff;
            }

            body .text-primary {
                color: #b9407f !important;
            }

            body .text-bg-primary {
                background: #111 !important;
            }

            .slider-container {
                width: 100%;
                margin: 40px auto;
                padding: 0 24px;
            }

            .slider {
                width: 100%;
                max-width: 1200px;
                height: 350px;
                margin: auto;
                position: relative;
                overflow: hidden;
                border-radius: 20px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                background: #fff;
            }

            .slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0;
                transition: 1s;
            }

            .slide.active {
                opacity: 1;
            }

            .home-section-title {
                font-weight: 800;
                color: #111;
            }

            .beauty-card {
                background: #fff;
                border: 1px solid rgba(34, 22, 56, 0.08);
                border-radius: 24px;
                box-shadow: 0 12px 28px rgba(34, 22, 56, 0.06);
            }

            @media (max-width: 992px) {
                .nav-bar {
                    align-items: flex-start;
                    flex-wrap: wrap;
                }

                .store-mobile-toggle {
                    display: inline-flex;
                }

                .nav-links {
                    display: none;
                    width: 100%;
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 10px;
                    background: #fff;
                    padding: 18px;
                    border-radius: 20px;
                    box-shadow: 0 12px 28px rgba(34, 22, 56, 0.08);
                    order: 4;
                }

                .nav-links.show {
                    display: flex;
                }

                .dropdown-panel {
                    position: static;
                    opacity: 1;
                    visibility: visible;
                    transform: none;
                    width: 100%;
                    min-width: unset;
                    box-shadow: none;
                    padding: 16px 0 4px;
                    display: none;
                }

                .store-nav-item.active .dropdown-panel {
                    display: grid;
                    transform: none;
                }

                .nav-extra {
                    width: 100%;
                    justify-content: flex-start;
                    order: 5;
                    flex-wrap: wrap;
                }

                .top-bar {
                    align-items: flex-start;
                    flex-direction: column;
                }
            }

            @media (max-width: 576px) {
                .homepage-wrapper,
                .slider-container {
                    padding: 0 14px;
                }

                .brand-logo {
                    font-size: 1.45rem;
                    letter-spacing: 0.18em;
                }

                .top-search input {
                    min-width: 120px;
                    width: 100%;
                }

                .slider {
                    height: 240px;
                    border-radius: 16px;
                }
            }
        </style>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const mobileToggle = document.querySelector('.store-mobile-toggle');
                const navLinks = document.querySelector('.nav-links');
                if (mobileToggle && navLinks) {
                    mobileToggle.addEventListener('click', function () {
                        navLinks.classList.toggle('show');
                    });
                }

                const dropdownItems = document.querySelectorAll('.store-nav-item.dropdown');
                dropdownItems.forEach(function (item) {
                    const link = item.querySelector('a');
                    if (!link) return;
                    link.addEventListener('click', function (event) {
                        event.preventDefault();
                        item.classList.toggle('active');
                    });
                });

                document.addEventListener('click', function (event) {
                    if (!event.target.closest('.store-nav-item.dropdown')) {
                        dropdownItems.forEach(function (item) {
                            item.classList.remove('active');
                        });
                    }
                });

                const slides = document.querySelectorAll('.slide');
                if (slides.length > 1) {
                    let currentSlide = 0;
                    setInterval(function () {
                        slides[currentSlide].classList.remove('active');
                        currentSlide++;
                        if (currentSlide >= slides.length) {
                            currentSlide = 0;
                        }
                        slides[currentSlide].classList.add('active');
                    }, 3000);
                }
            });
        </script>
    <?php endif; ?>

    <div class="homepage-wrapper">
        <header class="site-header">
            <div class="top-bar">
                <div class="top-left">
                    <span class="promo-text">ALL DAY SHOPING</span>
                    <span class="promo-subtext">order by delivery</span>
                </div>
                <div class="top-right">
                    <form class="top-search" method="GET" action="index.php">
                        <input type="hidden" name="page" value="katalog">
                        <input type="text" name="q" placeholder="cari kebutuhan" value="<?= e((string) getParam('q')) ?>" />
                        <button type="submit">Search</button>
                    </form>
                    <div class="user-profile-dropdown">
                        <button type="button" class="user-profile-btn"><?= e($userName) ?> ▼</button>
                        <div class="user-dropdown-menu">
                            <?php if (isLoggedIn()): ?>
                                <?php if (isAdmin()): ?>
                                    <a href="index.php?page=admin-dashboard" class="user-menu-item">Dashboard</a>
                                <?php endif; ?>
                                <a href="index.php?page=status-pesanan" class="user-menu-item">Status Pesanan</a>
                                <a href="auth/logout.php" class="user-menu-item">Logout</a>
                            <?php else: ?>
                                <a href="index.php?page=login" class="user-menu-item">Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nav-bar">
                <a class="brand-logo" href="index.php">ALLIE</a>
                <button type="button" class="store-mobile-toggle">Menu</button>
                <nav class="nav-links">
                    <div class="store-nav-item dropdown">
                        <a href="index.php?page=katalog" class="<?= $active === 'katalog' ? 'store-active' : '' ?>">Shop</a>
                        <div class="dropdown-panel">
                            <div class="dropdown-column">
                                <h4>By Category</h4>
                                <a href="index.php?page=katalog&q=Sunscreen">Sunscreens</a>
                                <a href="index.php?page=katalog&q=Moisturizer">Moisturizers</a>
                                <a href="index.php?page=katalog&q=Cleanser">Cleansers</a>
                                <a href="index.php?page=katalog&q=Serum">Serums</a>
                                <a href="index.php?page=katalog&q=Mask">Masks</a>
                                <a href="index.php?page=katalog&q=Lip">Lip Care</a>
                                <a href="index.php?page=katalog&q=Eye">Eye Care</a>
                                <a href="index.php?page=katalog&q=Exfoliator">Exfoliators</a>
                                <a href="index.php?page=katalog&q=Collagen">Collagen Drink</a>
                                <a href="index.php?page=katalog&q=Body">Body Care</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>By Ingredient</h4>
                                <a href="index.php?page=katalog&q=Vitamin C">Vitamin C</a>
                                <a href="index.php?page=katalog&q=Hyaluronic Acid">Hyaluronic Acid</a>
                                <a href="index.php?page=katalog&q=Peptides">Peptides</a>
                                <a href="index.php?page=katalog&q=Vitamin E">Vitamin E</a>
                                <a href="index.php?page=katalog&q=Salicylic Acid">Salicylic Acid</a>
                                <a href="index.php?page=katalog&q=Retinol">Retinol</a>
                                <a href="index.php?page=katalog&q=Bakuchiol">Bakuchiol</a>
                                <a href="index.php?page=katalog&q=Niacinamide">Niacinamide</a>
                                <a href="index.php?page=katalog&q=Glycolic Acid">Glycolic Acid</a>
                                <a href="index.php?page=katalog&q=Ferulic Acid">Ferulic Acid</a>
                                <a href="index.php?page=katalog&q=Lactic Acid">Lactic Acid</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>By Collection</h4>
                                <a href="index.php?page=katalog&q=VITAL C">VITAL C</a>
                                <a href="index.php?page=katalog&q=VOL.U.LIFT">VOL.U.LIFT</a>
                                <a href="index.php?page=katalog&q=AGELESS">AGELESS</a>
                                <a href="index.php?page=katalog&q=DAILY PREVENTION">DAILY PREVENTION</a>
                                <a href="index.php?page=katalog&q=ORMEDIC">ORMEDIC</a>
                                <a href="index.php?page=katalog&q=the MAX">the MAX</a>
                                <a href="index.php?page=katalog&q=ILUMA">ILUMA</a>
                                <a href="index.php?page=katalog&q=CLEAR CELL">CLEAR CELL</a>
                                <a href="index.php?page=katalog&q=BIOME">BIOME+</a>
                            </div>
                        </div>
                    </div>

                    <a href="index.php?page=katalog&q=Brand">Brand</a>
                    <a href="index.php#about-us">About us</a>

                    <div class="store-nav-item dropdown">
                        <a href="index.php?page=katalog&q=Produk">Produk</a>
                        <div class="dropdown-panel">
                            <div class="dropdown-column">
                                <h4>Hair Accessories</h4>
                                <a href="index.php?page=katalog&q=Brushes">Brushes & Combs</a>
                                <a href="index.php?page=katalog&q=Hair Pins">Hair Pins & Bands</a>
                                <a href="index.php?page=katalog&q=Electric Tools">Electric Tools</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Face Accessories</h4>
                                <a href="index.php?page=katalog&q=Face Gadgets">Face Gadgets</a>
                                <a href="index.php?page=katalog&q=Skincare Tools">Skincare Tools</a>
                                <a href="index.php?page=katalog&q=Hair Removal">Hair Removal Tools</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Body Accessories</h4>
                                <a href="index.php?page=katalog&q=Body Tools">Non-electrical Tools</a>
                                <a href="index.php?page=katalog&q=Body Shavers">Body Shavers</a>
                                <a href="index.php?page=katalog&q=Epilators">Epilators & IPL</a>
                                <a href="index.php?page=katalog&q=Menstrual Care">Menstrual Care Products</a>
                            </div>
                        </div>
                    </div>

                    <div class="store-nav-item dropdown">
                        <a href="index.php?page=katalog&q=Makeup">Makeup</a>
                        <div class="dropdown-panel">
                            <div class="dropdown-column">
                                <h4>Face</h4>
                                <a href="index.php?page=katalog&q=Foundation">Foundation</a>
                                <a href="index.php?page=katalog&q=Concealer">Concealer</a>
                                <a href="index.php?page=katalog&q=Powder">Powder</a>
                                <a href="index.php?page=katalog&q=Primer">Primer</a>
                                <a href="index.php?page=katalog&q=Blush">Blush</a>
                                <a href="index.php?page=katalog&q=Bronzer">Bronzer</a>
                                <a href="index.php?page=katalog&q=Highlighter">Highlighter</a>
                                <a href="index.php?page=katalog&q=Setting Spray">Setting Spray</a>
                                <a href="index.php?page=katalog&q=BB CC Cream">BB & CC Cream</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Eyes</h4>
                                <a href="index.php?page=katalog&q=Eyeshadow">Eyeshadow</a>
                                <a href="index.php?page=katalog&q=Eyeliner">Eyeliner</a>
                                <a href="index.php?page=katalog&q=Mascara">Mascara</a>
                                <a href="index.php?page=katalog&q=Eyebrow">Eyebrow Makeup</a>
                                <a href="index.php?page=katalog&q=False Eyelashes">False Eyelashes & Glue</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Lips</h4>
                                <a href="index.php?page=katalog&q=Lipstick">Lipstick</a>
                                <a href="index.php?page=katalog&q=Liquid Lipstick">Liquid Lipstick</a>
                                <a href="index.php?page=katalog&q=Lip Oil">Lip Oil</a>
                                <a href="index.php?page=katalog&q=Lip Tint">Lip Tint</a>
                                <a href="index.php?page=katalog&q=Lip Gloss">Lip Gloss</a>
                                <a href="index.php?page=katalog&q=Lip Liner">Lip Liner</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Makeup Accessories</h4>
                                <a href="index.php?page=katalog&q=Makeup Tools">Makeup Tools</a>
                                <a href="index.php?page=katalog&q=Makeup Brushes">Makeup Brushes</a>
                                <a href="index.php?page=katalog&q=Makeup Sponges">Makeup Sponges</a>
                            </div>
                        </div>
                    </div>

                    <a href="index.php?page=keranjang" class="cart-pill <?= $active === 'keranjang' ? 'store-active' : '' ?>">
                        Keranjang
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?= e($cartCount) ?></span>
                        <?php endif; ?>
                    </a>
                </nav>

                <div class="nav-extra">
                    <span>087648255942</span>
                    <a href="index.php?page=status-pesanan">Help Center</a>
                </div>
            </div>
        </header>
    </div>
    <?php
}
