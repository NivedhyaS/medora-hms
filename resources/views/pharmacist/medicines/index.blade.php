@extends('layouts.dashboard')

@section('title', 'Medicine Inventory')
@section('header', 'Medicine Inventory')

@section('content')
    <div class="header-actions" style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
        <a href="{{ route('pharmacist.medicines.create') }}" class="btn btn-primary" style="height: auto; padding: 10px 20px;">
            <i class="fas fa-plus"></i> Add New Medicine
        </a>
    </div>

    <div class="card full-width">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb;">
                    <th style="padding: 12px;">Medicine Name</th>
                    <th style="padding: 12px;">Stock Quantity</th>
                    <th style="padding: 12px;">Price</th>
                    <th style="padding: 12px;">Last Updated</th>
                    <th style="padding: 12px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $medicine)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px; font-weight: 500;">{{ $medicine->name }}</td>
                        <td style="padding: 12px;">
                            <span style="color: {{ $medicine->quantity < 10 ? '#dc2626' : '#111827' }}; font-weight: {{ $medicine->quantity < 10 ? 'bold' : 'normal' }};">
                                {{ $medicine->quantity }}
                            </span>
                            @if($medicine->quantity < 10)
                                <small style="display: block; color: #dc2626;">Low Stock</small>
                            @endif
                        </td>
                        <td style="padding: 12px;">{{ number_format($medicine->price, 2) }}</td>
                        <td style="padding: 12px; color: #6b7280; font-size: 13px;">{{ $medicine->updated_at->format('M d, Y H:i') }}</td>
                        <td style="padding: 12px; text-align: center; vertical-align: middle;">
                            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                <a href="{{ route('pharmacist.medicines.edit', $medicine->id) }}" title="Edit" 
                                   style="height: 36px; width: 36px; background: #eff6ff; color: #2563eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; border: none;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('pharmacist.medicines.destroy', $medicine->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this medicine?')" 
                                      style="margin: 0; padding: 0; display: flex; align-items: center; transform: translateY(4px);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" 
                                            style="height: 36px; width: 36px; background: #fef2f2; color: #ef4444; border: none; cursor: pointer; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #9ca3af;">
                            No medicines found in the inventory.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
