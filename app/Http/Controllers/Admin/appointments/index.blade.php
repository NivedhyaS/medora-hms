@extends('layouts.admin')

@section('content')
<h1 class="page-title">Appointments</h1>

<div class="card-grid">

    <!-- Search Card -->
    <div class="card">
        <h3>Search Appointments</h3>

        <form method="GET" action="{{ route('admin.appointments.index') }}">
            <label>Doctor</label>
            <select name="doctor_id">
                <option value="">-- All Doctors --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-primary">Search</button>
        </form>
    </div>

    <!-- Appointments Card -->
    <div class="card full-width">
        <h3>Booked Appointments</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Token</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->date }}</td>
                        <td>{{ $appointment->time }}</td>
                        <td>{{ $appointment->token }}</td>
                        <td>{{ $appointment->patient->name }}</td>
                        <td>{{ $appointment->doctor->name }}</td>
                        <td>{{ $appointment->status }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.appointments.destroy',$appointment->id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No appointments found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
