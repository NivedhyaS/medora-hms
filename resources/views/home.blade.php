<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hospital Home</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- SINGLE CSS FILE -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.8) !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 10px 0;
            transition: transform 0.3s ease-in-out;
        }

        .navbar-hidden {
            transform: translateY(-100%);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 65px;
            width: auto;
        }

        .navbar-brand span {
            color: #2563eb;
        }

        .nav-login-btn {
            background: linear-gradient(to right, #4f46e5, #0ea5e9);
            color: white !important;
            border: none;
            padding: 8px 22px !important;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-login-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .nav-register-btn {
            background: linear-gradient(to right, #4f46e5, #0ea5e9);
            color: white !important;
            border: none;
            padding: 8px 22px !important;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-register-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 20px;
                border-radius: 12px;
                margin-top: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            .navbar-nav {
                align-items: flex-start !important;
                gap: 15px !important;
            }

            .nav-login-btn,
            .nav-register-btn {
                width: auto;
                display: inline-block;
            }
        }

        /* Custom Animations */
        .hero-text h1 {
            animation: fadeInUp 1s ease-out forwards;
        }

        .hero-text p {
            animation: fadeInUp 1s ease-out 0.3s forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dept-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .dept-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .contact-card {
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .contact-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        /* Section Polish */
        .section-title {
            font-size: 38px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .section-title span {
            color: #2563eb;
        }

        .section-subtitle {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 14px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 12px;
            display: block;
        }

        .journey-text,
        .about-text {
            font-size: 16px;
            color: #475569;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .home-journey {
            padding: 100px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .about-medora {
            padding: 100px 0;
            background: #fafafa;
        }

        .dept-card {
            background: white !important;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Scroll to Top Button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .scroll-top.active {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top:hover {
            background: #1d4ed8;
            color: white;
            transform: translateY(-5px);
        }

        .navbar {
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
            box-shadow: none;
            position: relative;
            z-index: 2;
        }

        .hero {
            position: relative;
            background: #ffffff;
            overflow: hidden;
            padding: 0 !important;
            min-height: 700px;
            display: flex;
            align-items: stretch;
        }

        .hero-row {
            min-height: 700px;
            margin: 0;
            width: 100%;
            overflow: visible;
            /* Allow floating cards to overflow column boundaries */
        }

        .hero-text-col {
            display: flex;
            align-items: center;
            padding: 80px 0;
            z-index: 5;
        }

        /* Essential: Alignment with site container */
        .hero-content-inner {
            width: 100%;
            max-width: 600px;
            padding: 0 15px;
            margin-left: auto;
            /* Align to center-left of col-6 */
        }

        @media (max-width: 991px) {
            .hero-content-inner {
                margin: 0 auto;
                max-width: 100%;
                padding: 60px 20px;
            }

            .hero-image-col {
                min-height: 400px;
            }
        }

        .hero-image-col {
            padding: 0 !important;
            position: relative;
        }

        .hero-image-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .hero-image-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 150px;
            height: 100%;
            background: linear-gradient(to right, #ffffff, transparent);
            z-index: 3;
        }

        .hero-image-wrapper::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(to top, #ffffff, transparent);
            z-index: 3;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
        }

        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 3;
            animation: float 6s ease-in-out infinite;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .card-1 {
            top: 15%;
            left: -80px;
            /* Overlap into text section */
            animation-delay: 0s;
        }

        .card-2 {
            bottom: 25%;
            left: -50px;
            /* Overlap into text section */
            animation-delay: 1.5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        .floating-card i {
            color: #2563eb;
            font-size: 20px;
            margin-right: 10px;
        }

        .hero-text {
            padding-top: 50px !important;
            /* centered better with image */
        }

        /* NEW PROFESSIONAL ENHANCEMENTS */
        .trust-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 100px;
            color: #2563eb;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
            gap: 8px;
        }

        .hero-text h1 {
            font-size: 56px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 24px;
        }

        .hero-text h1 span {
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            font-size: 18px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        /* Background Decorative Elements */
        .bg-blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -2;
            filter: blur(60px);
        }

        .blob-1 {
            top: -10%;
            left: -10%;
        }

        .blob-2 {
            bottom: 10%;
            right: 10%;
            background: radial-gradient(circle, rgba(14, 165, 233, 0.1) 0%, transparent 70%);
        }

        .navbar-scrolled {
            padding: 8px 0 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
            background: rgba(255, 255, 255, 0.9) !important;
        }

        .btn-modern-primary {
            background: #2563eb;
            color: white !important;
            border: none;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-modern-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-modern-outline {
            background: transparent;
            color: #2563eb !important;
            border: 2px solid rgba(37, 99, 235, 0.2);
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-modern-outline:hover {
            border-color: #2563eb;
            background: rgba(37, 99, 235, 0.02);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top" id="mainHeader">
        <div class="container">

            <!-- Brand -->
            <a class="navbar-brand fw-bold mb-0" href="/">
                <img src="{{ asset('img/logo.svg') }}" alt="Medora Hospital Logo">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler border-0 shadow-none d-lg-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
                <ul class="navbar-nav d-flex align-items-center gap-4 mb-0 main-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#departments">Department</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn nav-register-btn" href="{{ route('auth.register') }}">
                            Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn nav-login-btn" href="{{ route('auth.login') }}">
                            Login
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>


    <section class="hero" id="home">
        <!-- Text Column -->
        <div class="row hero-row g-0">
            <div class="col-lg-6 hero-text-col">
                <div class="hero-content-inner">
                    <div class="trust-badge" data-aos="fade-down">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="me-1">
                            <path
                                d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                        </svg>
                        Professional Care Excellence
                    </div>
                    <h1 data-aos="fade-up">Reaching Beyond<br>the <span>Boundaries</span></h1>
                    <p data-aos="fade-up" data-aos-delay="200">
                        Experience world-class medical consultation from the comfort of your home.
                        Empowering healthcare through technology and compassion.
                    </p>
                    <div class="hero-btns mt-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="{{ route('auth.register') }}" class="btn btn-modern-primary me-3">Get Started</a>
                        <a href="#about" class="btn btn-modern-outline">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Image Column (Flush Right) -->
            <div class="col-lg-6 hero-image-col" data-aos="fade-left" data-aos-duration="1200">
                <div class="hero-image-wrapper">
                    <img src="{{ asset('img/hero-patient-care.png') }}" alt="Patient Care" class="hero-image">

                    <!-- Floating Rating Badge -->
                    <div class="floating-card card-1 d-none d-xl-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="me-2">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                            </polygon>
                        </svg>
                        <div>
                            <div class="fw-bold">4.9/5 Rating</div>
                            <small class="text-muted">Trusted by Patients</small>
                        </div>
                    </div>

                    <!-- Floating Badge overlaid on the split border -->
                    <div class="floating-card card-2 d-none d-xl-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="me-2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <div>
                            <div class="fw-bold">24/7 Support</div>
                            <small class="text-muted">Always Available</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-journey">
        <div class="container">
            <div class="row">
                <div class="col-md-8" data-aos="fade-up" data-aos-delay="100">
                    <span class="section-subtitle">Our Journey</span>
                    <h2 class="section-title">
                        Delivering Safe, Ethical, and Advanced Multispeciality <span>Care</span>
                    </h2>

                    <h5 class="journey-tagline fw-bold text-dark opacity-75">
                        Spreading Smiles Till the Last Mile
                    </h5>

                    <p class="journey-text">
                        At <strong>Medora Hospital</strong>, we are committed to delivering
                        high-quality healthcare through a blend of advanced medical technology,
                        experienced specialists, and patient-centric care. Our multispeciality
                        services are designed to address a wide range of healthcare needs with
                        precision, safety, and compassion.
                    </p>

                    <p class="journey-text">
                        From preventive health check-ups and routine consultations to complex
                        diagnostic and surgical procedures, Medora Hospital provides personalised
                        care tailored to every patient’s unique needs. Our clinical teams work
                        collaboratively across departments to ensure accurate diagnosis,
                        effective treatment, and better health outcomes.
                    </p>

                    <p class="journey-text">
                        Supported by a comprehensive care ecosystem that includes outpatient
                        services, inpatient facilities, emergency care, advanced surgical
                        interventions, and post-treatment rehabilitation, Medora Hospital
                        ensures seamless care at every stage of the patient journey. We
                        continuously strive to uphold the highest standards of clinical
                        excellence, patient safety, and ethical medical practice.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-medora" id="about">
        <div class="container">
            <div class="row">
                <div class="col-md-9" data-aos="fade-up" data-aos-delay="100">
                    <span class="section-subtitle">About Medora</span>
                    <h2 class="section-title">
                        Who We<span> Are?</span>
                    </h2>

                    <p class="about-text">
                        Medora Hospital is a multispeciality healthcare institution dedicated
                        to delivering high-quality medical care through a combination of
                        advanced technology, experienced specialists, and a strong commitment
                        to patient-centered values. We focus on providing safe, ethical, and
                        effective treatment tailored to the individual needs of every patient.
                    </p>

                    <p class="about-text">
                        Our comprehensive range of services spans preventive healthcare,
                        outpatient consultations, advanced diagnostics, surgical procedures,
                        emergency care, and post-treatment rehabilitation. Each department
                        works in close collaboration to ensure accurate diagnosis,
                        coordinated treatment, and improved health outcomes.
                    </p>

                    <p class="about-text">
                        At Medora Hospital, patient safety and clinical excellence are at the
                        core of everything we do. Our skilled medical teams follow
                        evidence-based practices and international healthcare standards
                        to maintain the highest levels of quality and reliability in care
                        delivery.
                    </p>

                    <p class="about-text">
                        We believe healthcare goes beyond treatment—it involves compassion,
                        trust, and continuity of care. Supported by modern infrastructure
                        and a holistic care ecosystem, Medora Hospital ensures a seamless
                        experience for patients at every stage of their healthcare journey.
                    </p>

                </div>
            </div>
        </div>
    </section>

    <section class="departments-section" id="departments">
        <div class="container">

            <div class="section-header" data-aos="fade-up">
                <h2>Our Departments</h2>
            </div>

            <div class="row g-4">

                <!-- Cardiology -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="dept-card cardiology">
                        <div class="dept-icon">❤️</div>
                        <h5>Cardiology</h5>
                    </div>
                </div>

                <!-- Orthopedics -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="dept-card orthopedics">
                        <div class="dept-icon">🦴</div>
                        <h5>Orthopedics</h5>
                    </div>
                </div>

                <!-- Oncology -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="dept-card oncology">
                        <div class="dept-icon">🧬</div>
                        <h5>Oncology</h5>
                    </div>
                </div>

                <!-- Neurology -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="dept-card neurology">
                        <div class="dept-icon">🧠</div>
                        <h5>Neurology</h5>
                    </div>
                </div>

                <!-- Gastroenterology -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="dept-card gastro">
                        <div class="dept-icon">🫀</div>
                        <h5>Gastroenterology</h5>
                    </div>
                </div>

                <!-- Nephrology & Urology -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="dept-card nephrology">
                        <div class="dept-icon">🧪</div>
                        <h5>Nephrology &amp; Urology</h5>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="contact-section" id="contact">
        <div class="container">

            <div class="section-header" data-aos="fade-up">
                <h6>Contact</h6>
                <h2>Get in Touch</h2>
                <p class="contact-subtext">
                    We are here to assist you. Reach out to Medora Hospital for appointments,
                    enquiries, or medical assistance.
                </p>
            </div>

            <div class="row g-4">

                <!-- Address -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-card">
                        <h5>Address</h5>
                        <p>
                            Medora Hospital<br>
                            MG Road,Kochi,<br>
                            Kerala – 670650
                        </p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-card">
                        <h5>Phone</h5>
                        <p>
                            +91 98765 43210<br>
                            +91 91234 56789
                        </p>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-card">
                        <h5>Email</h5>
                        <p>
                            info@medorahospital.com<br>
                            support@medorahospital.com
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <a href="#" class="scroll-top" id="scrollTop">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 15-6-6-6 6" />
        </svg>
    </a>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        // Scroll to Top & Header Scroll Effect
        const scrollTop = document.getElementById('scrollTop');
        const header = document.getElementById('mainHeader');
        let lastScrollTop = 0;

        window.addEventListener('scroll', () => {
            let st = window.pageYOffset || document.documentElement.scrollTop;
            
            // Scroll to Top Visibility
            if (st > 300) {
                scrollTop.classList.add('active');
            } else {
                scrollTop.classList.remove('active');
            }

            // Header Background & Hide on Scroll
            if (st > 50) {
                header.classList.add('navbar-scrolled');
            } else {
                header.classList.remove('navbar-scrolled');
            }

            if (st > lastScrollTop && st > 100) {
                // Scrolling down
                header.classList.add('navbar-hidden');
            } else {
                // Scrolling up
                header.classList.remove('navbar-hidden');
            }
            
            lastScrollTop = st <= 0 ? 0 : st;
        });

        scrollTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

</body>

</html>