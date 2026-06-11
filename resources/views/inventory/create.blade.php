@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2 text-primary"></i>
                បន្ថែមទំនិញថ្មីក្នុងស្តុក
            </h1>
            <p class="text-muted mb-0">បង្កើតទំនិញថ្មីសម្រាប់ស្តុកទំនិញរបស់អ្នក</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅស្តុកទំនិញ
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-box text-primary me-2"></i>
                        ព័ត៌មានទំនិញ
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <form method="POST" action="{{ route('inventory.store') }}" class="needs-validation" novalidate>
                        @csrf
                        
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
                                               value="{{ old('name') }}" 
                                               required
                                               placeholder="ឧ. ប៊ិចខៀវ">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        បញ្ចូលឈ្មោះទំនិញច្បាស់លាស់
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
                                               value="{{ old('sku') }}" 
                                               required 
                                               placeholder="ឧ. ITM-001"
                                               maxlength="20">
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
                                          placeholder="ការពិពណ៌នាលម្អិតអំពីទំនិញនេះ...">{{ old('description') }}</textarea>
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
                                               value="{{ old('current_stock', 0) }}" 
                                               min="0" 
                                               required>
                                        @error('current_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        ចំនួនទំនិញដែលមានបច្ចុប្បន្ន
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
                                               value="{{ old('minimum_stock', 10) }}" 
                                               min="0" 
                                               required>
                                        @error('minimum_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        កម្រិតអប្បបរមាមុនពេលជូនដំណឹង
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
                                               value="{{ old('price', 0) }}" 
                                               min="0" 
                                               step="0.01" 
                                               required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        តម្លៃលក់ក្នុងមួយឯកតា
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
                                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
                            
                            @if($suppliers->count() == 0)
                                <div class="alert alert-warning mt-2">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    មិនមានអ្នកផ្គត់ផ្គង់នៅឡើយទេ។ 
                                    <a href="{{ route('suppliers.create') }}" class="alert-link">បន្ថែមអ្នកផ្គត់ផ្គង់ដំបូង</a>។
                                </div>
                            @else
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    អ្នកផ្គត់ផ្គង់ដែលផ្គត់ផ្គង់ទំនិញនេះ
                                </div>
                            @endif
                        </div>
                        
                        <!-- Total Value Preview -->
                        <div class="alert alert-info" id="totalValuePreview" style="display: none;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calculator me-2"></i>
                                <div>
                                    <strong>តម្លៃស្តុកសរុប:</strong> <span id="totalValue">$0.00</span>
                                    <br><small>គណនាដោយ: បរិមាណ × តម្លៃក្នុងមួយឯកតា</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>
                                បោះបង់
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>
                                បង្កើតទំនិញ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Guidelines Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        គន្លឹះសម្រាប់បង្កើតទំនិញ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="bi bi-upc-scan me-1"></i>
                            គោលការណ៍ណែនាំ SKU:
                        </h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                ប្រើលេខកូដតែមួយគត់ (ឧ. ITM-001, BOOK-045)
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                រក្សាវាឱ្យខ្លី និងងាយចាំ
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                បញ្ចូលបុព្វបទប្រភេទបើចាំបាច់
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h6 class="text-warning mb-2">
                            <i class="bi bi-graph-up me-1"></i>
                            កម្រិតស្តុក:
                        </h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-dot"></i>
                                កំណត់ស្តុកអប្បបរមាតាមទម្រង់ការប្រើប្រាស់
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-dot"></i>
                                ពិចារណាពេលវេលាដឹកជញ្ជូនអ្នកផ្គត់ផ្គង់
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-dot"></i>
                                អ្នកនឹងទទួលបានការជូនដំណឹងពេលស្តុកនៅក្រោមកម្រិតអប្បបរមា
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up text-success me-2"></i>
                        ស្ថិតិរហ័ស
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ \App\Models\InventoryItem::count() }}</h4>
                                <small class="text-muted">ទំនិញសរុប</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-warning mb-1">{{ \App\Models\InventoryItem::needingRestock()->count() }}</h4>
                                <small class="text-muted">ស្តុកតិច</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center text-muted small mb-1">
                            <i class="bi bi-person me-2"></i>
                            <span>អ្នកប្រើ: <strong>whoisnut</strong></span>
                        </div>
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-clock me-2"></i>
                            <span>17/06/2025 10:53:43</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Items Preview -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock-history text-info me-2"></i>
                        ការប្រើប្រាស់ទម្រង់
                    </h6>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="mb-2">
                            <strong>ឈ្មោះទំនិញ:</strong> ប៊ិចខៀវ
                        </div>
                        <div class="mb-2">
                            <strong>SKU:</strong> PEN-001
                        </div>
                        <div class="mb-2">
                            <strong>ស្តុកបច្ចុប្បន្ន:</strong> 50
                        </div>
                        <div class="mb-2">
                            <strong>ស្តុកអប្បបរមា:</strong> 10
                        </div>
                        <div class="mb-2">
                            <strong>តម្លៃ:</strong> $0.50
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle text-muted me-2"></i>
                <span class="small text-muted">
                    អ្នកប្រើ: <strong>whoisnut</strong> • 
                    កំពុងបង្កើតនៅ: <strong>17/06/2025 10:53:43</strong> • 
                    បន្ទាប់ពីបង្កើត អ្នកអាចកែសម្រួលបាននៅពេលក្រោយ
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Include all CSS and JavaScript from the previous version -->
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

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
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

/* Alert enhancements */
.alert {
    border-radius: 0.5rem;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);
    
    // SKU auto-generation and formatting
    const nameInput = document.getElementById('name');
    const skuInput = document.getElementById('sku');
    
    nameInput.addEventListener('input', function() {
        if (!skuInput.value || skuInput.value === '') {
            // Auto-generate SKU from name
            const name = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 3);
            if (name.length > 0) {
                skuInput.value = name + '-001';
            }
        }
    });
    
    // SKU uppercase transformation
    skuInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Total value calculation
    const currentStockInput = document.getElementById('current_stock');
    const priceInput = document.getElementById('price');
    const totalValuePreview = document.getElementById('totalValuePreview');
    const totalValueSpan = document.getElementById('totalValue');
    
    function calculateTotalValue() {
        const currentStock = parseInt(currentStockInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const total = currentStock * price;
        
        if (currentStock > 0 && price > 0) {
            totalValueSpan.textContent = '$' + total.toFixed(2);
            totalValuePreview.style.display = 'block';
        } else {
            totalValuePreview.style.display = 'none';
        }
    }
    
    currentStockInput.addEventListener('input', calculateTotalValue);
    priceInput.addEventListener('input', calculateTotalValue);
    
    // Stock level validation
    const minimumStockInput = document.getElementById('minimum_stock');
    
    function validateStockLevels() {
        const current = parseInt(currentStockInput.value) || 0;
        const minimum = parseInt(minimumStockInput.value) || 0;
        
        if (current < minimum && current > 0) {
            currentStockInput.classList.add('border-warning');
            if (!document.getElementById('stock-warning')) {
                const warning = document.createElement('div');
                warning.id = 'stock-warning';
                warning.className = 'text-warning small mt-1';
                warning.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>ស្តុកបច្ចុប្បន្នតិចជាងកម្រិតអប្បបរមា';
                currentStockInput.parentNode.parentNode.appendChild(warning);
            }
        } else {
            currentStockInput.classList.remove('border-warning');
            const warning = document.getElementById('stock-warning');
            if (warning) {
                warning.remove();
            }
        }
    }
    
    currentStockInput.addEventListener('input', validateStockLevels);
    minimumStockInput.addEventListener('input', validateStockLevels);
    
    // Auto-format price input
    priceInput.addEventListener('blur', function() {
        const value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(2);
        }
    });
    
    // Initialize calculations
    calculateTotalValue();
    validateStockLevels();
    
    // Add loading state to submit button
    form.addEventListener('submit', function() {
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>កំពុងបង្កើត...';
    });
});
</script>

@endsection