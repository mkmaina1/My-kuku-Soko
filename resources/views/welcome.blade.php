<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>My-Kuku-Soko - Digital Poultry Marketplace</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-green: #2e7d32;
            --light-green: #66bb6a;
            --dark-green: #1b5e20;
            --orange: #ff7c00;
            --dark-orange: #cc6500;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1598514982733-e09c8a4c46ee') no-repeat center center fixed;
            background-size: cover;
            transition: background-color 0.3s ease;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #121212;
            color: white;
            background-blend-mode: multiply;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.55);
            height: 100vh;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 2;
        }

        .hero-box {
            padding: 60px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            max-width: 650px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .hero-title {
            font-size: 50px;
            font-weight: 800;
            color: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .hero-title i {
            font-size: 60px;
            color: var(--orange);
        }

        .btn-main {
            background: var(--orange);
            color: white;
            font-size: 18px;
            font-weight: 500;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-main:hover {
            background: var(--dark-orange);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 124, 0, 0.3);
        }

        .btn-outline-success {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            font-weight: 500;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-outline-success:hover {
            background: var(--primary-green);
            color: white;
            transform: translateY(-2px);
        }

        footer {
            background: #1a1a1a;
            color: white;
            padding: 25px 0;
        }

        .testimonial-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
            height: 100%;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
        }

        body.dark-mode .testimonial-card {
            background: #2b2b2b;
            color: white;
        }

        .product-card img {
            height: 180px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-card .card-body {
            padding: 20px;
        }

        .product-card .card-title {
            color: var(--primary-green);
            font-weight: 600;
        }

        .nav-link {
            color: #333 !important;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-green) !important;
        }

        .navbar-brand {
            color: var(--primary-green) !important;
            font-weight: 700;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            color: var(--orange);
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--orange);
            border-radius: 2px;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-green), var(--light-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .feature-icon i {
            font-size: 24px;
            color: white;
        }

        .feature-card {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 40px;
            font-weight: 700;
            color: var(--primary-green);
            line-height: 1;
        }

        .stats-label {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            .hero-box {
                padding: 30px;
                margin: 20px;
            }

            .hero-title {
                font-size: 36px;
            }

            .hero-title i {
                font-size: 40px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-light shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-egg"></i> My-Kuku-Soko
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="#get-started">Get Started</a></li>
                    <li class="nav-item">
                        <button id="darkToggle" class="btn btn-outline-dark ms-3">
                            <i class="fas fa-moon"></i> Dark Mode
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <div class="overlay"></div>

    <div class="container h-100 d-flex justify-content-center align-items-center content-wrapper" style="margin-top:100px;">
        <div class="hero-box text-center">
            <div class="hero-title">
                <i class="fas fa-egg"></i> My-Kuku-Soko
            </div>

            <p class="mt-3 text-secondary fs-5">
                Kenya's Digital Marketplace for Poultry Farmers, Buyers, and Agro-Suppliers
            </p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <a href="{{ route('login') }}" class="btn btn-main w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="{{ route('register') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-user-plus me-2"></i> Create Account
                    </a>
                </div>
            </div>

            <div class="row mt-4 text-center">
                <div class="col-md-4 mb-3">
                    <div class="stats-number">500+</div>
                    <div class="stats-label">Farmers</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stats-number">1,200+</div>
                    <div class="stats-label">Transactions</div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stats-number">100+</div>
                    <div class="stats-label">Veterinaries</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURES SECTION -->
    <section class="py-5 bg-white" id="features">
        <div class="container">
            <h2 class="text-center fw-bold section-title">Platform Features</h2>

            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <h5 class="fw-bold">Farm Management</h5>
                        <p class="text-muted">Manage your farm profile, listings, and sales all in one place</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5 class="fw-bold">Easy Buying</h5>
                        <p class="text-muted">Browse and purchase quality poultry products with ease</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h5 class="fw-bold">Veterinary Services</h5>
                        <p class="text-muted">Connect with poultry health experts for your farm needs</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="fw-bold">Sales Analytics</h5>
                        <p class="text-muted">Track your sales and performance with detailed analytics</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCT SHOWCASE SECTION -->
    <section class="py-5 bg-light" id="products">
        <div class="container">
            <h2 class="text-center fw-bold section-title">Popular Poultry Products</h2>
            <p class="text-center text-muted mb-5">
                Explore high-quality poultry products available on My-Kuku-Soko
            </p>

            <div class="row g-4">
                <!-- Product 1: Live Chickens -->
                <div class="col-md-4">
                    <div class="card product-card shadow-sm h-100">
                        <img src="https://media.istockphoto.com/id/2240911649/photo/large-indoor-poultry-farms.webp?a=1&b=1&s=612x612&w=0&k=20&c=NG8ZNXSbqgR93QPfRGrUJGgyW4pznmPmUe12qfof4y8="
                             class="card-img-top" alt="Live Chickens">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Live Chickens</h5>
                            <p class="card-text text-muted">
                                Healthy broilers and kienyeji breeds from verified farmers. Available in various ages and quantities.
                            </p>
                            <div class="text-end">
                                <span class="badge bg-success">From KES 500</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 2: Farm Chicks -->
                <div class="col-md-4">
                    <div class="card product-card shadow-sm h-100">
                        <img src="https://media.istockphoto.com/id/1006665202/photo/little-chicken-feeding-at-the-farm.webp?a=1&b=1&s=612x612&w=0&k=20&c=uonds5-5LbO5GcslHMlIl86zuh9HcF1UnpZISA7niyU="
                             class="card-img-top" alt="Farm Chicks">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Farm Chicks</h5>
                            <p class="card-text text-muted">
                                Day-old chicks ready for rearing, sourced from trusted hatcheries and farms.
                            </p>
                            <div class="text-end">
                                <span class="badge bg-success">From KES 150</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product 3: Chicken Feed -->
                <div class="col-md-4">
                    <div class="card product-card shadow-sm h-100">
                        <img src="https://images.unsplash.com/photo-1569466593977-94ee7ed02ec9?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Y2hpY2tlbiUyMGZlZWR8ZW58MHx8MHx8fDA%3D"
                             class="card-img-top" alt="Chicken Feed">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Chicken Feed</h5>
                            <p class="card-text text-muted">
                                Nutritious feeds for all poultry growth stages. Starter, grower, and layer feeds available.
                            </p>
                            <div class="text-end">
                                <span class="badge bg-success">From KES 2,500</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FARMER TESTIMONIALS -->
    <section id="testimonials" class="container py-5 content-wrapper">
        <h2 class="text-center fw-bold section-title">What Our Users Say</h2>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="text-warning mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"My-Kuku-Soko helped me sell my broilers faster than ever. The platform connects me directly with serious buyers!"</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-success rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <strong>Mary Wanjiku</strong><br>
                            <small class="text-muted">Poultry Farmer, Nakuru</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="text-warning mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p>"As a restaurant owner, I buy broilers in bulk. My-Kuku-Soko gives me access to quality suppliers at competitive prices."</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-primary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-tie text-white"></i>
                        </div>
                        <div>
                            <strong>James Kamau</strong><br>
                            <small class="text-muted">Restaurant Owner, Kiambu</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="text-warning mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p>"Thanks to My-Kuku-Soko, my poultry farm income has doubled. The veterinary services feature is a lifesaver!"</p>
                    <div class="d-flex align-items-center mt-3">
                        <div class="bg-danger rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <strong>Amina Latif</strong><br>
                            <small class="text-muted">Farm Manager, Eldoret</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GET STARTED SECTION -->
    <section id="get-started" class="py-5 text-center bg-white">
        <div class="container">
            <h2 class="fw-bold text-primary mb-4">Ready to Join the Poultry Revolution?</h2>
            <p class="text-secondary fs-5 mb-5">Join thousands of farmers, buyers, and service providers growing their businesses with My-Kuku-Soko</p>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="row g-3">
                       <div class="col-md-4">
    <a href="{{ route('register') }}?role=supplier" class="btn btn-success w-100 py-3">
        <i class="fas fa-warehouse me-2"></i> Join as Supplier <!-- Changed -->
    </a>
</div>
<div class="col-md-4">
    <a href="{{ route('register') }}?role=farmer" class="btn btn-primary w-100 py-3">
        <i class="fas fa-tractor me-2"></i> Join as Farmer <!-- Changed -->
    </a>
</div>
                        <div class="col-md-4">
                            <a href="{{ route('register') }}?role=veterinary" class="btn btn-warning w-100 py-3">
                                <i class="fas fa-user-md me-2"></i> Join as Veterinary
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-egg me-2"></i> My-Kuku-Soko
                    </h5>
                    <p class="text-white-50">
                        Kenya's leading digital marketplace for poultry trade and services.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-white-50 text-decoration-none">Register</a></li>
                        <li><a href="#features" class="text-white-50 text-decoration-none">Features</a></li>
                        <li><a href="#products" class="text-white-50 text-decoration-none">Products</a></li>
                    </ul>
                </div>

                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Contact Us</h5>
                    <p class="mb-1 text-white-50">
                        <i class="fas fa-envelope me-2"></i> support@my-kuku-soko.com
                    </p>
                    <p class="mb-1 text-white-50">
                        <i class="fas fa-phone me-2"></i> +254 712 345 678
                    </p>
                    <p class="mb-2 text-white-50">
                        <i class="fas fa-map-marker-alt me-2"></i> Nairobi, Kenya
                    </p>
                </div>
            </div>

            <div class="text-center pt-4 border-top border-secondary">
                <p class="text-white-50 mb-0">
                    &copy; {{ date('Y') }} My-Kuku-Soko. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DARK MODE SCRIPT -->
    <script>
        const toggleButton = document.getElementById('darkToggle');
        const icon = toggleButton.querySelector('i');

        toggleButton.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');

            if (document.body.classList.contains('dark-mode')) {
                icon.className = 'fas fa-sun';
                toggleButton.classList.remove('btn-outline-dark');
                toggleButton.classList.add('btn-warning');
            } else {
                icon.className = 'fas fa-moon';
                toggleButton.classList.remove('btn-warning');
                toggleButton.classList.add('btn-outline-dark');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 70,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white');
                navbar.classList.remove('bg-light');
            } else {
                navbar.classList.add('bg-light');
                navbar.classList.remove('bg-white');
            }
        });
    </script>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>
