@extends('layouts.dashboard')

@section('title', 'Lab Staff')
@section('header', 'Laboratory Personnel')

@section('content')
    <div class="card">
        <div class="header-actions">
            <a href="{{ route('admin.labstaff.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Lab Staff
            </a>

            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="labSearch" class="search-input" placeholder="Search by name, ID or department..."
                    onkeyup="filterTable('labSearch', 'labTable')">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Lab ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody id="labTable">
                @forelse($labstaff as $staff)
                    <tr>
                        <td><strong>{{ $staff->lab_id }}</strong></td>
                        <td>{{ $staff->name }}</td>
                        <td><span
                                style="background: #ecfdf5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 13px;">{{ $staff->department }}</span>
                        </td>
                        <td>{{ $staff->phone }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.labstaff.show', $staff->id) }}" class="btn btn-view"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.labstaff.edit', $staff->id) }}" class="btn btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.labstaff.destroy', $staff->id) }}" method="POST"
                                    onsubmit="return confirm('Delete this record?')">
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
                        <td colspan="5" style="text-align:center; padding: 40px; color: #64748b;">No lab staff found</td>
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