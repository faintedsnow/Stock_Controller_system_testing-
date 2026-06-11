@extends('layouts.app')
@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                កែសម្រួលទំនិញក្នុងស្តុក
            </h1>
            <p class="text-muted mb-0">កែសម្រួលព័ត៌មានទំនិញ: <span class="fw-semibold">{{ $item->name }}</span></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅស្តុកទំនិញ
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-1"></i> លុបទំនិញ
            </button>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box text-primary me-2"></i>
                            កែសម្រួល: {{ $item->name }}
                        </h5>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="bi bi-clock me-1"></i>
                            កែសម្រួលចុងក្រោយ: {{ $item->updated_at ? $item->updated_at->format('d/m/Y') : 'មិនមាន' }}
                        </span>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <form method="POST" action="{{ route('inventory.update', $item) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Item Name and SKU Row -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label fw-semibold">
                                        ឈ្មោះទំនិញ <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-box text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $item->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="sku" class="form-label fw-semibold">
                                        SKU (លេខកូដទំនិញ) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-upc-scan text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0 @error('sku') is-invalid @enderror" 
                                               id="sku" 
                                               name="sku" 
                                               value="{{ old('sku', $item->sku) }}" 
                                               required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        លេខកូដតែមួយគត់សម្រាប់ទំនិញនេះ
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                ការពិពណ៌នា
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-items-start pt-2">
                                    <i class="bi bi-text-left text-muted"></i>
                                </span>
                                <textarea class="form-control border-start-0 @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3"
                                          placeholder="ការពិពណ៌នាលម្អិតអំពីទំនិញនេះ...">{{ old('description', $item->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Stock Information Row -->
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="current_stock" class="form-label fw-semibold">
                                        ស្តុកបច្ចុប្បន្ន <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-box-seam text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control border-start-0 @error('current_stock') is-invalid @enderror" 
                                               id="current_stock" 
                                               name="current_stock" 
                                               value="{{ old('current_stock', $item->current_stock) }}" 
                                               min="0" 
                                               required>
                                        @error('current_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="minimum_stock" class="form-label fw-semibold">
                                        ស្តុកអប្បបរមា <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-flag text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control border-start-0 @error('minimum_stock') is-invalid @enderror" 
                                               id="minimum_stock" 
                                               name="minimum_stock" 
                                               value="{{ old('minimum_stock', $item->minimum_stock) }}" 
                                               min="0" 
                                               required>
                                        @error('minimum_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label for="price" class="form-label fw-semibold">
                                        តម្លៃក្នុងមួយឯកតា <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">$</span>
                                        <input type="number" 
                                               class="form-control border-start-0 @error('price') is-invalid @enderror" 
                                               id="price" 
                                               name="price" 
                                               value="{{ old('price', $item->price) }}" 
                                               min="0" 
                                               step="0.01" 
                                               required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Supplier Selection -->
                        <div class="mb-4">
                            <label for="supplier_id" class="form-label fw-semibold">
                                អ្នកផ្គត់ផ្គង់
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-people text-muted"></i>
                                </span>
                                <select class="form-select border-start-0 @error('supplier_id') is-invalid @enderror" 
                                        id="supplier_id" name="supplier_id">
                                    <option value="">ជ្រើសរើសអ្នកផ្គត់ផ្គង់ (ស្រេចចិត្ត)</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" 
                                                {{ old('supplier_id', $item->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                            @if($supplier->contact_person)
                                                ({{ $supplier->contact_person }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                អ្នកផ្គត់ផ្គង់ដែលផ្គត់ផ្គង់ទំនិញនេះ
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>
                                បោះបង់
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-check-circle me-1"></i>
                                ធ្វើបច្ចុប្បន្នភាព
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Item Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-data text-info me-2"></i>
                        ស្ថានភាពទំនិញ
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Current Status -->
                    @php
                        $needsRestock = $item->current_stock <= $item->minimum_stock;
                        $bgColor = $needsRestock ? 'rgba(220, 53, 69, 0.1)' : 'rgba(25, 135, 84, 0.1)';
                        $statusClass = $needsRestock ? 'danger' : 'success';
                        $statusIcon = $needsRestock ? 'exclamation-triangle' : 'check-circle';
                    @endphp
                    <div class="d-flex align-items-center mb-3 p-3 rounded" style="background-color: {{ $bgColor }}">
                        <div class="avatar-sm bg-{{ $statusClass }} bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-{{ $statusIcon }} text-{{ $statusClass }}"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">ស្ថានភាពបច្ចុប្បន្ន</h6>
                            @if($needsRestock)
                                <span class="badge bg-danger bg-opacity-10 text-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>ស្តុកតិច
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-check-circle me-1"></i>មានស្តុក
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Stock Analysis -->
                    <div class="mb-3 p-3 bg-light rounded">
                        <h6 class="text-primary mb-2">
                            <i class="bi bi-graph-up me-1"></i>
                            ការវិភាគស្តុក
                        </h6>
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="fw-bold text-primary" id="currentStockDisplay">{{ number_format($item->current_stock) }}</div>
                                <small class="text-muted">បច្ចុប្បន្ន</small>
                            </div>
                            <div class="col-6">
                                <div class="fw-bold text-warning" id="minimumStockDisplay">{{ number_format($item->minimum_stock) }}</div>
                                <small class="text-muted">អប្បបរមា</small>
                            </div>
                        </div>
                        @if($item->current_stock > 0)
                            @php
                                $progressPercentage = min(100, ($item->current_stock / max(1, $item->minimum_stock * 2)) * 100);
                                $progressClass = $needsRestock ? 'warning' : 'success';
                            @endphp
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-{{ $progressClass }}" 
                                     id="stockProgressBar"
                                     style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- FIXED: Timestamps with null checking -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <h6 class="text-success mb-1">
                                    {{ $item->created_at ? $item->created_at->format('d/m/Y') : 'មិនមាន' }}
                                </h6>
                                <small class="text-muted">បានបង្កើត</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <h6 class="text-warning mb-1">
                                    {{ $item->updated_at ? $item->updated_at->format('d/m/Y') : 'មិនមាន' }}
                                </h6>
                                <small class="text-muted">កែសម្រួលចុងក្រោយ</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-lightning text-warning me-2"></i>
                        សកម្មភាពរហ័ស
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#stockModal">
                            <i class="bi bi-arrow-up-circle me-1"></i>
                            ធ្វើបច្ចុប្បន្នភាពស្តុក
                        </button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-list me-1"></i>
                            មើលទំនិញទាំងអស់
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Value Information -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-currency-dollar text-success me-2"></i>
                        ព័ត៌មានតម្លៃ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1" id="totalValueDisplay">${{ number_format($item->current_stock * $item->price, 2) }}</h4>
                                <small class="text-muted">តម្លៃស្តុកសរុប</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Update Modal -->
    <div class="modal fade" id="stockModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-arrow-up-circle me-2"></i>
                        ធ្វើបច្ចុប្បន្នភាពស្តុក: {{ $item->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('inventory.update-stock', $item) }}" id="stockUpdateForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>ស្តុកបច្ចុប្បន្ន:</strong> <span id="modalCurrentStock">{{ number_format($item->current_stock) }}</span>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label fw-semibold">
                                បរិមាណ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-123 text-muted"></i>
                                </span>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="action" class="form-label fw-semibold">
                                សកម្មភាព <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="action" name="action" required>
                                <option value="add">បន្ថែមស្តុក</option>
                                <option value="remove">ដកស្តុក</option>
                            </select>
                        </div>
                        <div class="mb-3" id="previewSection" style="display: none;">
                            <div class="alert alert-light border">
                                <strong>ស្តុកបន្ទាប់ពីការកែប្រែ:</strong> <span id="previewStock">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            បោះបង់
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>ធ្វើបច្ចុប្បន្នភាព
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        លុបទំនិញ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center">
                        តើអ្នកពិតជាចង់លុប <strong>{{ $item->name }}</strong> មែនទេ?
                    </p>
                    <div class="alert alert-warning">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>សកម្មភាពនេះមិនអាចត្រឡប់វិញបានទេ! ទំនិញនេះនឹងត្រូវបានលុបចេញពីប្រព័ន្ធទាំងស្រុង។</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        បោះបង់
                    </button>
                    <form method="POST" action="{{ route('inventory.destroy', $item) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>លុបទំនិញ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FIXED: Footer Info with current date/time -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle text-muted me-2"></i>
                <span class="small text-muted">
                    អ្នកប្រើ: <strong>whoisnut</strong> • 
                    កំពុងកែសម្រួលនៅ: <strong>20/06/2025 17:19:36</strong> • 
                    ការបង្កើតដំបូង: <strong>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : 'មិនមាន' }}</strong>
                </span>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Form Styling */
.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    background-color: #f8f9fa !important;
    border-color: #e9ecef;
}

.form-control.border-start-0:focus,
.form-select.border-start-0:focus {
    border-left: 1px solid #0d6efd;
}

.input-group:focus-within .input-group-text {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05) !important;
}

.input-group:focus-within .input-group-text i {
    color: #0d6efd !important;
}

/* Card Enhancements */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
}

/* Avatar styling */
.avatar-sm {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Badge styling */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

.bg-opacity-20 {
    --bs-bg-opacity: 0.2;
}

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Progress bar styling */
.progress {
    background-color: rgba(0, 0, 0, 0.1);
}

/* Khmer Font Support */
body {
    font-family: 'Noto Sans Khmer', 'Khmer OS', sans-serif;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Custom spacing for better Khmer text rendering */
.form-text,
.small {
    line-height: 1.6;
}

/* Text colors */
.text-gray-800 {
    color: #374151 !important;
}

/* Validation styling */
.was-validated .form-control:valid,
.form-control.is-valid,
.was-validated .form-select:valid,
.form-select.is-valid {
    border-color: #198754;
}

.was-validated .form-control:invalid,
.form-control.is-invalid,
.was-validated .form-select:invalid,
.form-select.is-invalid {
    border-color: #dc3545;
}

/* Modal Enhancements */
.modal-content {
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Current stock value for calculations
    const currentStock = {{ $item->current_stock }};
    const currentPrice = {{ $item->price }};
    
    // Form validation
    const form = document.querySelector('.needs-validation');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
    
    // Stock level indicator update
    const currentStockInput = document.getElementById('current_stock');
    const minimumStockInput = document.getElementById('minimum_stock');
    const priceInput = document.getElementById('price');
    
    function updateStockIndicator() {
        const current = parseInt(currentStockInput.value) || 0;
        const minimum = parseInt(minimumStockInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        
        // Update displays
        if (document.getElementById('currentStockDisplay')) {
            document.getElementById('currentStockDisplay').textContent = new Intl.NumberFormat().format(current);
        }
        if (document.getElementById('minimumStockDisplay')) {
            document.getElementById('minimumStockDisplay').textContent = new Intl.NumberFormat().format(minimum);
        }
        if (document.getElementById('totalValueDisplay')) {
            document.getElementById('totalValueDisplay').textContent = '$' + (current * price).toFixed(2);
        }
        
        // Update progress bar
        const progressBar = document.getElementById('stockProgressBar');
        if (progressBar && minimum > 0) {
            const percentage = Math.min(100, (current / (minimum * 2)) * 100);
            progressBar.style.width = percentage + '%';
            
            // Update color based on stock level
            progressBar.className = 'progress-bar bg-' + (current <= minimum ? 'warning' : 'success');
        }
        
        // Visual feedback for stock input
        if (current <= minimum) {
            currentStockInput.classList.add('border-warning');
            currentStockInput.classList.remove('border-success');
        } else {
            currentStockInput.classList.add('border-success');
            currentStockInput.classList.remove('border-warning');
        }
    }
    
    if (currentStockInput) currentStockInput.addEventListener('input', updateStockIndicator);
    if (minimumStockInput) minimumStockInput.addEventListener('input', updateStockIndicator);
    if (priceInput) priceInput.addEventListener('input', updateStockIndicator);
    
    // Initialize stock indicator
    updateStockIndicator();
    
    // Auto-format price input
    if (priceInput) {
        priceInput.addEventListener('blur', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                this.value = value.toFixed(2);
                updateStockIndicator();
            }
        });
    }
    
    // SKU uppercase transformation
    const skuInput = document.getElementById('sku');
    if (skuInput) {
        skuInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Stock Update Modal Logic
    const quantityInput = document.getElementById('quantity');
    const actionSelect = document.getElementById('action');
    const previewSection = document.getElementById('previewSection');
    const stockForm = document.getElementById('stockUpdateForm');
    
    function updateStockPreview() {
        if (quantityInput && actionSelect) {
            const quantity = parseInt(quantityInput.value) || 0;
            const action = actionSelect.value;
            
            if (quantity > 0) {
                let newStock = currentStock;
                if (action === 'add') {
                    newStock = currentStock + quantity;
                } else if (action === 'remove') {
                    newStock = Math.max(0, currentStock - quantity);
                }
                
                previewSection.style.display = 'block';
                
                // Warning for negative stock
                if (action === 'remove' && quantity > currentStock) {
                    previewSection.innerHTML = '<div class="alert alert-warning border"><i class="bi bi-exclamation-triangle me-1"></i><strong>ការព្រមាន:</strong> បរិមាណដកលើសពីស្តុកបច្ចុប្បន្ន។ ស្តុកនឹងក្លាយជា 0</div>';
                } else {
                    previewSection.innerHTML = '<div class="alert alert-light border"><strong>ស្តុកបន្ទាប់ពីការកែប្រែ:</strong> <span>' + new Intl.NumberFormat().format(newStock) + '</span></div>';
                }
            } else {
                previewSection.style.display = 'none';
            }
        }
    }
    
    if (quantityInput) quantityInput.addEventListener('input', updateStockPreview);
    if (actionSelect) actionSelect.addEventListener('change', updateStockPreview);
    
    // Form validation for stock update
    if (stockForm) {
        stockForm.addEventListener('submit', function(e) {
            const quantity = parseInt(quantityInput.value);
            
            if (!quantity || quantity <= 0) {
                e.preventDefault();
                alert('សូមបញ្ចូលបរិមាណដែលត្រឹមត្រូវ');
                quantityInput.focus();
                return false;
            }
        });
    }
    
    // Reset modal form when hidden
    const stockModal = document.getElementById('stockModal');
    if (stockModal) {
        stockModal.addEventListener('hidden.bs.modal', function () {
            quantityInput.value = '';
            actionSelect.value = 'add';
            previewSection.style.display = 'none';
            stockForm.classList.remove('was-validated');
        });
    }
});
</script>

@endsection