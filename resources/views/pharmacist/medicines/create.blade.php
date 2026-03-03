@extends('layouts.dashboard')

@section('title', 'Add New Medicine')
@section('header', 'Add New Medicine')

@section('content')
    <div class="header-actions" style="margin-bottom: 20px;">
        <a href="{{ route('pharmacist.medicines.index') }}" class="btn btn-edit" style="width: auto; padding: 0 15px;">
            <i class="fas fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="{{ route('pharmacist.medicines.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Medicine Name</label>
                <input type="text" name="name" class="search-input" style="position: static;" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Stock Quantity</label>
                    <input type="number" name="quantity" class="search-input" style="position: static;" min="0" value="0" required>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600;">Price (Per Unit)</label>
                    <input type="number" name="price" class="search-input" style="position: static;" min="0" step="0.01" value="0.00" required>
                </div>
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" class="btn btn-primary" style="width: 100%; height: auto; padding: 12px; font-size: 16px;">
                    <i class="fas fa-save"></i> Save Medicine
                </button>
            </div>
        </form>
    </div>
@endsection
