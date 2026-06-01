<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Navbar Beauty Always</title>
    <style>
        /* ============================================================
           1. RESET & ROOT BROWSER
           ============================================================ */
        :root {
            font-family: 'Inter', system-ui, sans-serif;
            color: #1f1f1f;
            background: #f7f5f5;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
            cursor: pointer;
        }

        /* Container Pembungkus agar layout rapi di tengah */
        .homepage-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .site-header {
            padding-top: 8px;
        }

        /* ============================================================
           2. TOP BAR (Promo, Search & Profile)
           ============================================================ */
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
        }

        .top-search button {
            border: none;
            padding: 0 16px;
            color: #111;
            background: transparent;
            font-weight: bold;
        }

        /* ============================================================
           3. MAIN NAVIGATION BAR (SUDAH DIPERBAIKI)
           ============================================================ */
        .nav-bar {
            display: flex; /* Menggunakan flex agar luas & sejajar */
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
            flex-grow: 1; /* Biarkan menu mengambil ruang di tengah */
        }

        .nav-links a,
        .nav-item > a {
            font-size: 0.95rem;
            color: #333;
            font-weight: 600;
            display: inline-block;
            padding: 8px 0;
            line-height: 1.2;
            white-space: nowrap; /* Mencegah teks turun ke bawah */
        }

        .nav-item {
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

        .nav-extra a:hover {
            color: #b9407f;
        }

        /* ============================================================
           4. MEGA DROPDOWN PANEL
           ============================================================ */
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
            z-index: 10;
            min-width: 650px;
        }

        /* Trigger Hover Dropdown */
        .nav-item:hover .dropdown-panel,
        .nav-item.active .dropdown-panel {
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

        /* ============================================================
           5. USER PROFILE DROPDOWN
           ============================================================ */
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
            min-width: 140px;
            box-shadow: 0 12px 28px rgba(34, 22, 56, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
            z-index: 100;
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

        /* Dummy Body Content untuk visualisasi */
        .dummy-content {
            margin-top: 60px;
            text-align: center;
            padding: 100px 20px;
            background: #fff;
            border-radius: 24px;
            border: 2px dashed #ddd;
            color: #999;
        }

        /* ==========================
        AUTO SLIDER
        ========================== */

            /* SLIDER */
        .slider-container{
            width:100%;
            margin:40px auto;
        }

        .slider{
            width:100%;
            max-width:1200px; /* sama seperti homepage-wrapper */
            height:350px;     /* lebih proporsional */
            margin:auto;

            position:relative;
            overflow:hidden;

            border-radius:20px;
            box-shadow:0 5px 15px rgba(0,0,0,0.15);
        }

        .slide{
            position:absolute;
            top:0;
            left:0;

            width:100%;
            height:100%;

            object-fit:cover;

            opacity:0;
            transition:1s;
        }

        .slide.active{
            opacity:1;
        }
    </style>
</head>
<body>

    <div class="homepage-wrapper">
        <header class="site-header">
            <div class="top-bar">
                <div class="top-left">
                    <span class="promo-text">ALL DAY SHOPING</span>
                    <span class="promo-subtext">order by delivery</span>
                </div>
                <div class="top-right">
                    <div class="top-search">
                        <input type="text" placeholder="cari kebutuhan" />
                        <button>Search</button>
                    </div>
                    <div class="user-profile-dropdown">
                        <button class="user-profile-btn">UserDemo ▼</button>
                        <div class="user-dropdown-menu">
                            <a href="#" class="user-menu-item">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nav-bar">
                <a class="brand-logo" href="#">ALLIE</a>
                <nav class="nav-links">
                    
                   <div class="nav-item dropdown">
                        <a href="#">Shop</a>
                        <div class="dropdown-panel">
                            <div class="dropdown-column">
                                <h4>By Category</h4>
                                <a href="#">Sunscreens</a>
                                <a href="#">Moisturizers</a>
                                <a href="#">Cleansers</a>
                                <a href="#">Serums</a>
                                <a href="#">Masks</a>
                                <a href="#">Lip Care</a>
                                <a href="#">Eye Care</a>
                                <a href="#">Exfoliators</a>
                                <a href="#">Collagen Drink</a>
                                <a href="#">Body Care</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>By Ingredient</h4>
                                <a href="#">Vitamin C</a>
                                <a href="#">Hyaluronic Acid</a>
                                <a href="#">Peptides</a>
                                <a href="#">Vitamin E</a>
                                <a href="#">Salicylic Acid</a>
                                <a href="#">Retinol</a>
                                <a href="#">Bakuchiol</a>
                                <a href="#">Niacinamide</a>
                                <a href="#">Glycolic Acid</a>
                                <a href="#">Ferulic Acid</a>
                                <a href="#">Tetrahexyldecyl Ascorbate</a>
                                <a href="#">Lactic Acid</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>By Collection</h4>
                                <a href="#">VITAL C</a>
                                <a href="#">VOL.U.LIFT</a>
                                <a href="#">AGELESS | AGELESS+ retinol</a>
                                <a href="#">DAILY PREVENTION</a>
                                <a href="#">ORMEDIC</a>
                                <a href="#">the MAX</a>
                                <a href="#">ILUMA</a>
                                <a href="#">IMAGE MD</a>
                                <a href="#">CLEAR CELL</a>
                                <a href="#">BIOME+</a>
                                <a href="#">I MASK</a>
                                <a href="#">YANA</a>
                                <a href="#">I BEAUTY</a>
                            </div>
                        </div>
                    </div>
                    <a href="#">Brand</a>
                    <a href="#">About us</a>
                    <div class="nav-item dropdown">
                        <a href="#">Produk</a>
                        <div class="dropdown-panel">
                            <div class="dropdown-column">
                                <h4>Hair Accessories</h4>
                                <a href="#">Brushes & Combs</a>
                                <a href="#">Hair Pins & Bands</a>
                                <a href="#">Electric Tools</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Face Accessories</h4>
                                <a href="#">Face Gadgets</a>
                                <a href="#">Skincare Tools</a>
                                <a href="#">Hair Removal Tools</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Body Accessories</h4>
                                <a href="#">Non-electrical Tools</a>
                                <a href="#">Body Shavers</a>
                                <a href="#">Epilators & IPL</a>
                                <a href="#">Menstrual Care Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#">Makeup</a>
                        <div class="dropdown-panel">
                            <div class="dropdown-column">
                                <h4>Face</h4>
                                <a href="#">Foundation</a>
                                <a href="#">Concealer</a>
                                <a href="#">Powder</a>
                                <a href="#">Primer</a>
                                <a href="#">Blush</a>
                                <a href="#">Bronzer</a>
                                <a href="#">Highlighter</a>
                                <a href="#">Face Palettes</a>
                                <a href="#">Setting Spray</a>
                                <a href="#">BB & CC Cream</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Eyes</h4>
                                <a href="#">Eyeshadow</a>
                                <a href="#">Eyeliner</a>
                                <a href="#">Mascara</a>
                                <a href="#">Eyebrow Makeup</a>
                                <a href="#">Lash & Eyebrow Growth</a>
                                <a href="#">False Eyelashes & Glue</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Lips</h4>
                                <a href="#">Lipstick</a>
                                <a href="#">Liquid Lipstick</a>
                                <a href="#">Lip Oil</a>
                                <a href="#">Lip Tint</a>
                                <a href="#">Lip Gloss</a>
                                <a href="#">Lip Liner</a>
                            </div>
                            <div class="dropdown-column">
                                <h4>Makeup Accessories</h4>
                                <a href="#">Makeup Tools</a>
                                <a href="#">Makeup Brushes</a>
                                <a href="#">Makeup Sponges</a>
                            </div>
                        </div>
                    </div>
                    
                </nav>
                
                <div class="nav-extra">
                    <span>087648255942</span>
                    <a href="#">Help Center</a>
                </div>
            </div>
        </header>
    </div>
    <!-- SLIDER -->
    <div class="slider-container">

        <div class="slider">

            <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=1600" class="slide active">

            <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?w=1600" class="slide">

            <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=1600" class="slide">

            <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?w=1600" class="slide">

        </div>

    </div>

    <script>
        const dropdownItems = document.querySelectorAll('.nav-item.dropdown');
        dropdownItems.forEach((item) => {
            const link = item.querySelector('a');

            // Jika diklik (berguna untuk mobile/tablet)
            link.addEventListener('click', (event) => {
                event.preventDefault();
                item.classList.toggle('active');
            });
        });

        // Klik di luar menu untuk menutup dropdown
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.nav-item.dropdown')) {
                dropdownItems.forEach((item) => item.classList.remove('active'));
            }
        });
    </script>
    <script>

        const slides = document.querySelectorAll('.slide');

        let currentSlide = 0;

        function changeSlide(){

            slides[currentSlide].classList.remove('active');

            currentSlide++;

            if(currentSlide >= slides.length){
                currentSlide = 0;
            }

            slides[currentSlide].classList.add('active');
        }

        setInterval(changeSlide, 3000);

    </script>
</body>
</html>