@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-cart-plus me-2 text-primary"></i>
                គ្រប់គ្រងការបញ្ជាទិញបន្ថែម
            </h1>
            <p class="text-muted mb-0">គ្រប់គ្រងនិងតាមដានការបញ្ជាទិញស្តុកទំនិញបន្ថែម</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅស្តុក
            </a>
            @if(class_exists('\App\Models\RestockOrder'))
                <a href="{{ route('restock.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> បញ្ជាទិញថ្មី
                </a>
            @endif
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($message))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle me-2"></i>
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-list-ul text-secondary me-2"></i>
                        បញ្ជីការបញ្ជាទិញ
                    </h5>
                </div>
                <div class="card-body pt-3">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ទំនិញ</th>
                                        <th>អ្នកផ្គត់ផ្គង់</th>
                                        <th>បរិមាណបញ្ជា</th>
                                        <th>តម្លៃសរុប</th>
                                        <th>កាលបរិច្ចេទរំពឹង</th>
                                        <th>ស្ថានភាព</th>
                                        <th>សកម្មភាព</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $order->inventoryItem->name }}</div>
                                                <small class="text-muted">SKU: {{ $order->inventoryItem->sku }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $order->supplier->name }}</td>
                                        <td>{{ number_format($order->quantity_ordered) }}</td>
                                        <td>${{ number_format($order->total_cost, 2) }}</td>
                                        <td>{{ $order->expected_date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $order->getStatusBadgeClass() }}">
                                                {{ $order->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($order->status === 'pending')
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-success receive-btn"
                                                        data-order-id="{{ $order->id }}"
                                                        data-item-name="{{ $order->inventoryItem->name }}"
                                                        data-quantity="{{ $order->quantity_ordered }}">
                                                    <i class="bi bi-check-circle"></i> ទទួល
                                                </button>
                                            @else
                                                <span class="text-muted">បានបញ្ចប់</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-cart-plus text-muted" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mb-3 mt-3">មិនទាន់មានការបញ្ជាទិញនៅឡើយទេ</h4>
                            @if(class_exists('\App\Models\RestockOrder'))
                                <a href="{{ route('restock.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>បញ្ជាទិញដំបូង
                                </a>
                            @else
                                <p class="text-muted">ប្រព័ន្ធការបញ្ជាទិញមិនត្រូវបានដំឡើងនៅឡើយទេ។</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Receive Stock Modal -->
    @if(class_exists('\App\Models\RestockOrder'))
    <div class="modal fade" id="receiveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ទទួលស្តុក</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="receiveForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">ទំនិញ</label>
                            <p class="form-control-plaintext" id="modalItemName"></p>
                        </div>
                        <div class="mb-3">
                            <label for="quantity_received" class="form-label fw-semibold">
                                បរិមាណទទួល <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" 
                                   id="quantity_received" 
                                   name="quantity_received" 
                                   min="1" required>
                            <div class="form-text">
                                បរិមាណអតិបរមា: <span id="maxQuantity"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="received_date" class="form-label fw-semibold">
                                កាលបរិច្ចេទទទួល <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" 
                                   id="received_date" 
                                   name="received_date" 
                                   value="{{ date('Y-m-d') }}" 
                                   max="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-success">ទទួលស្តុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- UPDATED: Footer Info with current Cambodia time and user info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle text-muted me-2"></i>
                <span class="small text-muted">
                    អ្នកប្រើ: <strong>whoisnut</strong> • 
                    កំពុងមើលនៅ: <strong>20/06/2025 17:34:59</strong> • 
                    ការបញ្ជាទិញសរុប: <strong>{{ $orders->count() }}</strong>
                </span>
            </div>
        </div>
    </div>
</div>

@if(class_exists('\App\Models\RestockOrder'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const receiveModal = new bootstrap.Modal(document.getElementById('receiveModal'));
    
    // Handle receive button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.receive-btn')) {
            const btn = e.target.closest('.receive-btn');
            const orderId = btn.dataset.orderId;
            const itemName = btn.dataset.itemName;
            const quantity = btn.dataset.quantity;
            
            // Update modal content
            document.getElementById('modalItemName').textContent = itemName;
            document.getElementById('maxQuantity').textContent = quantity;
            document.getElementById('quantity_received').max = quantity;
            document.getElementById('quantity_received').value = quantity;
            document.getElementById('receiveForm').action = `/restock/${orderId}/receive`;
            
            receiveModal.show();
        }
    });
});
</script>
@endif

@endsection