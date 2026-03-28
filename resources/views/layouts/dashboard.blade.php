<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Hospital Management System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/favicon.svg') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('styles')
    <style>
        @media (max-width: 768px) {
            .admin-container {
                margin-top: 65px;
            }
        }
    </style>
</head>

<body>

    <div class="mobile-header d-md-none">
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="{{ asset('img/favicon.svg') }}" class="mobile-logo" alt="Logo">
            <h2 style="margin: 0; font-size: 18px;">Medora Hospital</h2>
        </div>
        <button id="sidebarToggle"
            style="background: none; border: 1px solid #374151; color: white; padding: 5px 10px; border-radius: 5px;">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header"
                style="background: white; padding: 10px; border-radius: 12px; margin-bottom: 30px;">
                <img src="{{ asset('img/logo.svg') }}" alt="Medora Logo" class="logo-img">
            </div>


            <ul class="menu">
                @if(Auth::user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.doctors.index') }}"><i class="fas fa-user-md"></i> Doctors</a></li>
                    <li><a href="{{ route('admin.schedules.index') }}"><i class="fas fa-calendar-alt"></i> Doctor
                            Schedules</a></li>
                    <li><a href="{{ route('admin.appointments.index') }}"><i class="fas fa-calendar-check"></i>
                            Appointments</a></li>
                    <li><a href="{{ route('admin.patients.index') }}"><i class="fas fa-user-injured"></i> Patients</a></li>
                    <li><a href="{{ route('admin.pharmacists.index') }}"><i class="fas fa-pills"></i> Pharmacists</a></li>
                    <li><a href="{{ route('admin.labstaff.index') }}"><i class="fas fa-flask"></i> Lab Staff</a></li>
                @elseif(Auth::user()->isDoctor())
                    <li><a href="{{ route('doctor.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                @elseif(Auth::user()->isLabStaff())
                    <li><a href="{{ route('labstaff.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                @elseif(Auth::user()->isPharmacist())
                    <li><a href="{{ route('pharmacist.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                    <li><a href="{{ route('pharmacist.prescriptions') }}"><i class="fas fa-file-prescription"></i>
                            Prescriptions</a></li>
                    <li><a href="{{ route('pharmacist.uploaded_prescriptions') }}"><i class="fas fa-upload"></i>
                            External Prescriptions</a></li>
                    <li><a href="{{ route('pharmacist.medicines.index') }}"><i class="fas fa-pills"></i>
                            Medicine Inventory</a></li>
                @elseif(Auth::user()->isReception())
                    <li><a href="{{ route('reception.dashboard') }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                    <li><a href="{{ route('reception.patients') }}"><i class="fas fa-user-injured"></i> Patients</a></li>
                    <li><a href="{{ route('reception.appointments') }}"><i class="fas fa-calendar-check"></i>
                            Appointments</a></li>
                    <li><a href="{{ route('reception.billings') }}"><i class="fas fa-file-invoice-dollar"></i> Billing</a>
                    </li>
                @elseif(Auth::user()->isPatient())
                    <li><a href="{{ route('patient.select_service') }}"><i class="fas fa-home"></i> Home (Services)</a></li>
                    <li><a href="{{ route('patient.dashboard') }}"><i class="fas fa-th-large"></i> Medical Dashboard</a>
                    </li>
                    <li><a href="{{ route('patient.appointments') }}"><i class="fas fa-calendar-alt"></i> My
                            Appointments</a></li>
                    <li><a href="{{ route('patient.find_doctor') }}"><i class="fas fa-search"></i> Find a Doctor</a></li>
                    <li><a href="{{ route('patient.prescriptions') }}"><i class="fas fa-file-prescription"></i> My
                            Prescriptions</a></li>
                    <li><a href="{{ route('patient.lab_reports') }}"><i class="fas fa-flask"></i> My Lab Reports</a></li>
                @endif
            </ul>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @if(session('success'))
                <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(trim($__env->yieldContent('header')))
                <h1>@yield('header')</h1>
            @endif
            @yield('content')
        </div>
    </div>

    @yield('scripts')
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Hide Navbar on Scroll Down, Show on Scroll Up
        let lastScrollTop = 0;
        const mobileHeader = document.querySelector('.mobile-header');

        window.addEventListener('scroll', function () {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > lastScrollTop && scrollTop > 50) {
                // Scrolling down
                mobileHeader?.classList.add('nav-hidden');
            } else {
                // Scrolling up
                mobileHeader?.classList.remove('nav-hidden');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        });
    </script>
    <script>
        // Global AJAX CSRF Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global 401/419 Handler
        $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
            if (jqXHR.status === 401 || jqXHR.status === 419) {
                Swal.fire({
                    title: 'Session Expired',
                    text: 'Your session has timed out. Please login again to continue.',
                    icon: 'warning',
                    confirmButtonText: 'Login Now'
                }).then(() => {
                    window.location.href = "{{ route('auth.login') }}";
                });
            }
        });
    </script>
</body>

</html>