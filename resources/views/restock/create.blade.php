@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2 text-primary"></i>
                បង្កើតការបញ្ជាទិញបន្ថែម
            </h1>
            <p class="text-muted mb-0">បង្កើតការបញ្ជាទិញស្តុកទំនិញបន្ថែមពីអ្នកផ្គត់ផ្គង់</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('restock.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅបញ្ជីការបញ្ជាទិញ
            </a>
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
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cart-plus text-primary me-2"></i>
                        ព័ត៌មានការបញ្ជាទិញ
                    </h5>
                </div>
                <div class="card-body pt-3">
                    @if($hasRestockSystem)
                        <form method="POST" action="{{ route('restock.store') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <!-- Item Selection -->
                            <div class="mb-4">
                                <label for="inventory_item_id" class="form-label fw-semibold">
                                    ជ្រើសរើសទំនិញ <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-box text-muted"></i>
                                    </span>
                                    <select class="form-select border-start-0 @error('inventory_item_id') is-invalid @enderror" 
                                            id="inventory_item_id" 
                                            name="inventory_item_id" required>
                                        <option value="">ជ្រើសរើសទំនិញ...</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" 
                                                    data-current-stock="{{ $item->current_stock }}"
                                                    data-minimum-stock="{{ $item->minimum_stock }}"
                                                    data-supplier-id="{{ $item->supplier_id ?? '' }}"
                                                    data-price="{{ $item->price }}"
                                                    {{ old('inventory_item_id', $selectedItem->id ?? '') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }} ({{ $item->sku }}) - ស្តុកបច្ចុប្បន្ន: {{ number_format($item->current_stock) }}
                                                @if($item->needsRestock())
                                                    [ស្តុកតិច]
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('inventory_item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Supplier Selection -->
                            <div class="mb-4">
                                <label for="supplier_id" class="form-label fw-semibold">
                                    អ្នកផ្គត់ផ្គង់ <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-people text-muted"></i>
                                    </span>
                                    <select class="form-select border-start-0 @error('supplier_id') is-invalid @enderror" 
                                            id="supplier_id" 
                                            name="supplier_id" required>
                                        <option value="">ជ្រើសរើសអ្នកផ្គត់ផ្គង់...</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" 
                                                    {{ old('supplier_id', $selectedItem->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
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
                            </div>

                            <!-- Quantity and Total Cost Row -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="quantity_ordered" class="form-label fw-semibold">
                                            បរិមាណបញ្ជាទិញ <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-123 text-muted"></i>
                                            </span>
                                            <input type="number" 
                                                   class="form-control border-start-0 @error('quantity_ordered') is-invalid @enderror" 
                                                   id="quantity_ordered" 
                                                   name="quantity_ordered" 
                                                   value="{{ old('quantity_ordered') }}" 
                                                   min="1" 
                                                   required>
                                            @error('quantity_ordered')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="total_cost" class="form-label fw-semibold">
                                            តម្លៃសរុប <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">$</span>
                                            <input type="number" 
                                                   class="form-control border-start-0 @error('total_cost') is-invalid @enderror" 
                                                   id="total_cost" 
                                                   name="total_cost" 
                                                   value="{{ old('total_cost') }}" 
                                                   min="0" 
                                                   step="0.01" 
                                                   required>
                                            @error('total_cost')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-text">
                                            តម្លៃក្នុងមួយឯកតា: <span id="unit_cost_display">$0.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Expected Date -->
                            <div class="mb-4">
                                <label for="expected_date" class="form-label fw-semibold">
                                    កាលបរិច្ចេទរំពឹង
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar text-muted"></i>
                                    </span>
                                    <input type="date" 
                                           class="form-control border-start-0 @error('expected_date') is-invalid @enderror" 
                                           id="expected_date" 
                                           name="expected_date" 
                                           value="{{ old('expected_date', date('Y-m-d', strtotime('+7 days'))) }}" 
                                           min="{{ date('Y-m-d') }}">
                                    @error('expected_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">
                                    ស្រេចចិត្ត - កាលបរិច្ចេទដែលរំពឹងថានឹងទទួលស្តុក
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 justify-content-end mt-5 pt-3 border-top">
                                <a href="{{ route('restock.index') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i>
                                    បោះបង់
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-1"></i>
                                    បង្កើតការបញ្ជាទិញ
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                            <h4 class="text-muted mb-3 mt-3">ប្រព័ន្ធមិនអាចប្រើបានទេ</h4>
                            <p class="text-muted">ប្រព័ន្ធការបញ្ជាទិញមិនត្រូវបានដំឡើងនៅឡើយទេ។</p>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅស្តុកទំនិញ
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-calculator text-success me-2"></i>
                        សង្ខេបការបញ្ជាទិញ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h5 class="text-primary mb-1" id="displayQuantity">0</h5>
                                <small class="text-muted">បរិមាណ</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h5 class="text-success mb-1" id="displayTotal">$0.00</h5>
                                <small class="text-muted">តម្លៃសរុប</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Item Info -->
            <div class="card border-0 shadow-sm" id="itemInfoCard" style="display: none;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        ព័ត៌មានទំនិញ
                    </h6>
                </div>
                <div class="card-body">
                    <div id="itemInfo">
                        <!-- Item details will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- UPDATED: Footer with current Cambodia time -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle text-muted me-2"></i>
                <span class="small text-muted">
                    អ្នកប្រើ: <strong>whoisnut</strong> • 
                    កំពុងបង្កើតនៅ: <strong>20/06/2025 17:42:21</strong> • 
                    ទំនិញសរុប: <strong>{{ $items->count() }}</strong> • 
                    អ្នកផ្គត់ផ្គង់: <strong>{{ $suppliers->count() }}</strong>
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

.text-gray-800 {
    color: #374151 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemSelect = document.getElementById('inventory_item_id');
    const supplierSelect = document.getElementById('supplier_id');
    const quantityInput = document.getElementById('quantity_ordered');
    const totalCostInput = document.getElementById('total_cost');
    const itemInfoCard = document.getElementById('itemInfoCard');
    const itemInfo = document.getElementById('itemInfo');
    const unitCostDisplay = document.getElementById('unit_cost_display');
    
    // Update calculations
    function updateCalculations() {
        const quantity = parseInt(quantityInput.value) || 0;
        const totalCost = parseFloat(totalCostInput.value) || 0;
        const unitCost = quantity > 0 ? totalCost / quantity : 0;
        
        document.getElementById('displayQuantity').textContent = quantity.toLocaleString();
        document.getElementById('displayTotal').textContent = '$' + totalCost.toFixed(2);
        unitCostDisplay.textContent = '$' + unitCost.toFixed(2);
    }
    
    // Auto-calculate total cost when quantity changes
    function autoCalculateTotal() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        const quantity = parseInt(quantityInput.value) || 0;
        
        if (selectedOption.value && quantity > 0) {
            const itemPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            if (itemPrice > 0) {
                totalCostInput.value = (quantity * itemPrice).toFixed(2);
                updateCalculations();
            }
        }
    }
    
    // Update item info
    function updateItemInfo() {
        const selectedOption = itemSelect.options[itemSelect.selectedIndex];
        
        if (selectedOption.value) {
            const currentStock = selectedOption.getAttribute('data-current-stock');
            const minimumStock = selectedOption.getAttribute('data-minimum-stock');
            const supplierIdFromItem = selectedOption.getAttribute('data-supplier-id');
            const itemPrice = selectedOption.getAttribute('data-price');
            
            // Auto-select supplier if item has a default supplier
            if (supplierIdFromItem && supplierSelect.value === '') {
                supplierSelect.value = supplierIdFromItem;
            }
            
            // Show item info
            itemInfoCard.style.display = 'block';
            itemInfo.innerHTML = `
                <div class="mb-2">
                    <strong>ស្តុកបច្ចុប្បន្ន:</strong> ${parseInt(currentStock).toLocaleString()}
                </div>
                <div class="mb-2">
                    <strong>ស្តុកអប្បបរមា:</strong> ${parseInt(minimumStock).toLocaleString()}
                </div>
                <div class="mb-2">
                    <strong>តម្លៃក្នុងស្តុក:</strong> $${parseFloat(itemPrice).toFixed(2)}
                </div>
                <div class="mb-2">
                    <strong>ស្ថានភាព:</strong> 
                    ${parseInt(currentStock) <= parseInt(minimumStock) ? 
                        '<span class="badge bg-warning">ស្តុកតិច</span>' : 
                        '<span class="badge bg-success">ស្តុកគ្រប់គ្រាន់</span>'
                    }
                </div>
                <div>
                    <strong>បរិមាណណែនាំ:</strong> ${Math.max(10, parseInt(minimumStock) * 2).toLocaleString()}
                </div>
            `;
            
            // Suggest quantity if stock is low
            if (parseInt(currentStock) <= parseInt(minimumStock) && !quantityInput.value) {
                quantityInput.value = Math.max(10, parseInt(minimumStock) * 2);
                autoCalculateTotal();
            }
        } else {
            itemInfoCard.style.display = 'none';
        }
    }
    
    // Event listeners
    if (itemSelect) {
        itemSelect.addEventListener('change', function() {
            updateItemInfo();
            autoCalculateTotal();
        });
    }
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            autoCalculateTotal();
            updateCalculations();
        });
    }
    if (totalCostInput) {
        totalCostInput.addEventListener('input', updateCalculations);
    }
    
    // Initialize if item is pre-selected
    if (itemSelect.value) {
        updateItemInfo();
    }
    
    // Initialize calculations
    updateCalculations();
    
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
});
</script>

@endsection