@extends('layouts.dashboard')

@section('title', 'Patients')
@section('header', 'Registered Patients')

@section('content')
    <div class="card">
        <div class="header-actions">
            <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Patient
            </a>

            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="patientSearch" class="search-input" placeholder="Search by name, email or mobile..."
                    onkeyup="filterTable('patientSearch', 'patientTable')">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Email Address</th>
                    <th>Mobile Number</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody id="patientTable">
                @forelse($patients as $patient)
                    <tr>
                        <td>
                            <a href="{{ route('admin.patients.show', $patient->id) }}"
                                style="text-decoration: none; color: #1e293b; font-weight: 600;">
                                {{ $patient->name }}
                            </a>
                        </td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->mobile ?? '-' }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.patients.show', $patient->id) }}" class="btn btn-view"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.patients.destroy', $patient->id) }}"
                                    onsubmit="return confirm('Delete this patient?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete-icon" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 40px; color: #64748b;">
                            <i class="fas fa-user-slash" style="display: block; font-size: 24px; margin-bottom: 10px;"></i>
                            No patients found
                        </td>
                    </tr>
                @endforelse
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