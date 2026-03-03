@extends('layouts.dashboard')

@section('title', 'Doctor Dashboard')
@section('header', 'Doctor Dashboard')

@section('content')
    <div class="header-actions">
        <form action="{{ route('doctor.dashboard') }}" method="GET" class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" class="search-input" placeholder="Search patient by name..."
                value="{{ $search ?? '' }}">
        </form>
    </div>

    <div class="cards">
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Total Appointments</h3>
            <p style="font-size: 24px; font-weight: bold; color: #111827;">{{ $totalAppointments }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Today's Appointments</h3>
            <p style="font-size: 24px; font-weight: bold; color: #2563eb;">{{ $todayCount }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Upcoming Appointments</h3>
            <p style="font-size: 24px; font-weight: bold; color: #059669;">{{ $upcomingCount }}</p>
        </div>
        <div class="card">
            <h3 style="color: #6b7280; font-size: 14px; margin-bottom: 5px;">Completed Appointments</h3>
            <p style="font-size: 24px; font-weight: bold; color: #6366f1;">{{ $completedCount }}</p>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="card full-width" style="margin-top: 30px; padding: 0; overflow: hidden;">
        <div style="display: flex; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <button class="tab-btn active" onclick="openTab(event, 'today')" id="defaultTab"
                style="padding: 15px 25px; border: none; background: none; cursor: pointer; font-weight: 600; color: #2563eb; border-bottom: 2px solid #2563eb; transition: 0.3s;">Today</button>
            <button class="tab-btn" onclick="openTab(event, 'upcoming')"
                style="padding: 15px 25px; border: none; background: none; cursor: pointer; font-weight: 500; color: #6b7280; transition: 0.3s;">Upcoming</button>
            <button class="tab-btn" onclick="openTab(event, 'past')"
                style="padding: 15px 25px; border: none; background: none; cursor: pointer; font-weight: 500; color: #6b7280; transition: 0.3s;">Past</button>
        </div>

        <div id="today" class="tab-content" style="display: block; padding: 20px;">
            @include('doctor._appointments_table', ['appointments' => $todayAppointments])
        </div>

        <div id="upcoming" class="tab-content" style="display: none; padding: 20px;">
            @include('doctor._appointments_table', ['appointments' => $upcomingAppointments])
        </div>

        <div id="past" class="tab-content" style="display: none; padding: 20px;">
            @include('doctor._appointments_table', ['appointments' => $pastAppointments])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
                tablinks[i].style.color = "#6b7280";
                tablinks[i].style.borderBottom = "none";
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.classList.add("active");
            evt.currentTarget.style.color = "#2563eb";
            evt.currentTarget.style.borderBottom = "2px solid #2563eb";
        }
    </script>
@endsection