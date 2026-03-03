@extends('layouts.dashboard')

@section('title', 'Pharmacists')
@section('header', 'Pharmacy Staff')

@section('content')
    <div class="card">
        <div class="header-actions">
            <a href="{{ route('admin.pharmacists.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Pharmacist
            </a>

            <div class="search-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="pharmSearch" class="search-input" placeholder="Search by name, ID or email..."
                    onkeyup="filterTable('pharmSearch', 'pharmTable')">
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Pharm ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody id="pharmTable">
                @forelse($pharmacists as $pharmacist)
                    <tr>
                        <td><strong>{{ $pharmacist->pharm_id }}</strong></td>
                        <td>{{ $pharmacist->name }}</td>
                        <td>{{ $pharmacist->email }}</td>
                        <td>{{ $pharmacist->phone }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.pharmacists.show', $pharmacist->id) }}" class="btn btn-view"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.pharmacists.edit', $pharmacist->id) }}" class="btn btn-edit"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.pharmacists.destroy', $pharmacist->id) }}" method="POST"
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
                        <td colspan="5" style="text-align:center; padding: 40px; color: #64748b;">No pharmacists found</td>
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