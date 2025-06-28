<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mkristoh Rental - Premium Car Rental Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-car me-2"></i>Mkristoh Rental
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#cars">Cars</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary ms-2" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary ms-2" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay">
            <div class="container">
                <div class="row min-vh-100 align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold text-white mb-4">
                            Premium Car Rental Services
                        </h1>
                        <p class="lead text-white mb-4">
                            Experience luxury and comfort with our premium fleet of vehicles. 
                            From economy to luxury, we have the perfect car for your journey.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="register.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Get Started
                            </a>
                            <a href="#cars" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-car me-2"></i>View Cars
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold">Why Choose Mkristoh Rental?</h2>
                    <p class="lead text-muted">We provide the best car rental experience with premium service</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-car text-primary fa-3x"></i>
                            </div>
                            <h5 class="card-title">Premium Fleet</h5>
                            <p class="card-text">Choose from our extensive collection of well-maintained vehicles ranging from economy to luxury.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-clock text-primary fa-3x"></i>
                            </div>
                            <h5 class="card-title">24/7 Support</h5>
                            <p class="card-text">Round-the-clock customer support to assist you with any queries or emergencies.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-shield-alt text-primary fa-3x"></i>
                            </div>
                            <h5 class="card-title">Safe & Secure</h5>
                            <p class="card-text">All our vehicles are fully insured and regularly maintained for your safety.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cars Section -->
    <section id="cars" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold">Our Premium Fleet</h2>
                    <p class="lead text-muted">Explore our collection of vehicles</p>
                </div>
            </div>
            <div class="row g-4" id="cars-container">
                <!-- Cars will be loaded dynamically -->
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">About Mkristoh Rental</h2>
                    <p class="lead mb-4">
                        We are a leading car rental service provider committed to delivering exceptional customer experience 
                        with our premium fleet of vehicles and professional service.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Premium Vehicles</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Flexible Booking</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Competitive Rates</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1449824913935-59a10b8d2000?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                         alt="Luxury Car" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="display-5 fw-bold">Contact Us</h2>
                    <p class="lead text-muted">Get in touch with us for any inquiries</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-map-marker-alt text-primary fa-2x mb-3"></i>
                            <h5>Address</h5>
                            <p class="text-muted">123 Rental Street, City, Country</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-phone text-primary fa-2x mb-3"></i>
                            <h5>Phone</h5>
                            <p class="text-muted">+254 114 289 197</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-envelope text-primary fa-2x mb-3"></i>
                            <h5>Email</h5>
                            <p class="text-muted">info@mkristohrental.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="main.js"></script>
</body>
</html> 