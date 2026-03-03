@extends('layouts.dashboard')

@section('title', 'Doctors')
@section('header', 'Our Doctors')

@section('content')
    <div class="card">
        <div class="header-actions">
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Doctor
            </a>

            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="doctorSearch" class="search-input"
                    placeholder="Search doctors by name, email or specialization..."
                    onkeyup="filterTable('doctorSearch', 'doctorTable')">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Available Time</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody id="doctorTable">
                @foreach($doctors as $doctor)
                    <tr>
                        <td><strong>{{ $doctor->name }}</strong></td>
                        <td><span
                                style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 13px;">{{ $doctor->specialization }}</span>
                        </td>
                        <td>{{ $doctor->phone }}</td>
                        <td>{{ $doctor->email }}</td>
                        <td>
                            @if($doctor->available_days)
                                <div style="font-weight: 600; font-size: 12px; color: #2563eb; margin-bottom: 4px;">
                                    {{ implode(', ', array_map(fn($day) => substr($day, 0, 3), $doctor->available_days)) }}
                                </div>
                            @endif
                            <div style="font-size: 13px; color: #4b5563; display: flex; align-items: center; gap: 4px;">
                                <i class="far fa-clock" style="color: #94a3b8;"></i>
                                {{ date('h:i A', strtotime($doctor->availability_start)) }} -
                                {{ date('h:i A', strtotime($doctor->availability_end)) }}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                    onsubmit="return confirm('Delete this doctor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete-icon" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function filterTable(inputId, tableId) {
            let input = document.getElementById(inputId);
            let filter = input.value.toLowerCase();
            let tbody = document.getElementById(tableId);
            let tr = tbody.getElementsByTagName("tr");

            for (let i = 0; i < tr.length; i++) {
                let text = tr[i].innerText.toLowerCase();
                tr[i].style.display = text.includes(filter) ? "" : "none";
            }
        }
    </script>
@endsection