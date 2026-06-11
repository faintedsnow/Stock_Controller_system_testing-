@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                កែសម្រួលអ្នកផ្គត់ផ្គង់
            </h1>
            <p class="text-muted mb-0">កែសម្រួលព័ត៌មានអ្នកផ្គត់ផ្គង់: <span class="fw-semibold">{{ $supplier->name }}</span></p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> ត្រឡប់ទៅបញ្ជីអ្នកផ្គត់ផ្គង់
        </a>
    </div>

    <div class="row g-4">
        <!-- Main Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person-badge text-primary me-2"></i>
                            កែសម្រួល: {{ $supplier->name }}
                        </h5>
                        <span class="badge bg-info bg-opacity-10 text-info">
                            <i class="bi bi-clock me-1"></i>
                            កែសម្រួលចុងក្រោយ: {{ $supplier->updated_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body pt-3">
                    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <!-- Supplier Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                ឈ្មោះអ្នកផ្គត់ផ្គង់ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-building text-muted"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $supplier->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                បញ្ចូលឈ្មោះអ្នកផ្គត់ផ្គង់ឬក្រុមហ៊ុនពេញលេញ
                            </div>
                        </div>
                        
                        <!-- Contact Person -->
                        <div class="mb-4">
                            <label for="contact_person" class="form-label fw-semibold">
                                អ្នកទំនាក់ទំនង
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" 
                                       name="contact_person" 
                                       value="{{ old('contact_person', $supplier->contact_person) }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Contact Information Row -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">
                                        អ៊ីម៉ែល
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-envelope text-muted"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $supplier->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="phone" class="form-label fw-semibold">
                                        លេខទូរស័ព្ទ
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-telephone text-muted"></i>
                                        </span>
                                        <input type="tel" 
                                               class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone', $supplier->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="form-label fw-semibold">
                                អាសយដ្ឋាន
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-items-start pt-2">
                                    <i class="bi bi-geo-alt text-muted"></i>
                                </span>
                                <textarea class="form-control border-start-0 @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3">{{ old('address', $supplier->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end mt-5 pt-3 border-top">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary px-4">
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
            <!-- Supplier Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-data text-info me-2"></i>
                        សេចក្តីសង្ខេបអ្នកផ្គត់ផ្គង់
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Associated Items -->
                    <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-box text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $supplier->inventoryItems->count() }}</h6>
                            <small class="text-muted">ទំនិញដែលបានភ្ជាប់</small>
                        </div>
                    </div>
                    
                    @if($supplier->inventoryItems->count() > 0)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            <small>អ្នកផ្គត់ផ្គង់នេះផ្គត់ផ្គង់ទំនិញដល់ស្តុករបស់អ្នក</small>
                        </div>
                    @endif
                    
                    <!-- Creation Date -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <h6 class="text-success mb-1">{{ $supplier->created_at->format('d/m/Y') }}</h6>
                                <small class="text-muted">បានបង្កើត</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-2 border rounded">
                                <h6 class="text-warning mb-1">{{ $supplier->updated_at->format('d/m/Y') }}</h6>
                                <small class="text-muted">កែសម្រួលចុងក្រោយ</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($supplier->restockOrders->count() > 0)
                        <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                            <div class="avatar-sm bg-success bg-opacity-20 rounded-2 me-3">
                                <i class="bi bi-cart-check text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 text-success">{{ $supplier->restockOrders->count() }}</h6>
                                <small class="text-muted">ការបញ្ជាទិញសរុប</small>
                                <br><small class="text-muted">ការបញ្ជាទិញដែលបានធ្វើជាមួយអ្នកផ្គត់ផ្គង់នេះ</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Supplied Items Card -->
            @if($supplier->inventoryItems->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-list-ul text-secondary me-2"></i>
                        ទំនិញដែលផ្គត់ផ្គង់
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($supplier->inventoryItems->take(5) as $item)
                            <div class="list-group-item border-0 px-0 py-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-secondary bg-opacity-10 rounded-2 me-2">
                                            <i class="bi bi-box text-secondary small"></i>
                                        </div>
                                        <span class="small">{{ $item->name }}</span>
                                    </div>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        {{ number_format($item->current_stock) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($supplier->inventoryItems->count() > 5)
                        <div class="text-center mt-3 pt-2 border-top">
                            <small class="text-muted">
                                ... និង {{ $supplier->inventoryItems->count() - 5 }} ទំនិញទៀត
                            </small>
                        </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Quick Actions Card -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-lightning text-warning me-2"></i>
                        សកម្មភាពរហ័ស
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            បន្ថែមទំនិញថ្មី
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-cart-plus me-1"></i>
                            បង្កើតការបញ្ជាទិញ
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye me-1"></i>
                            មើលបណ្តាទំនិញ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Last Updated Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light border d-flex align-items-center">
                <i class="bi bi-info-circle text-muted me-2"></i>
                <span class="small text-muted">
                    អ្នកប្រើ: <strong>{{ auth()->user()->name ?? 'whoisnut' }}</strong> • 
                   
                </span>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Form Styling */
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group-text {
    background-color: #f8f9fa !important;
    border-color: #e9ecef;
}

.form-control.border-start-0:focus {
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

/* Alert styling */
.alert {
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #0dcaf0;
}

.alert-light {
    border-left-color: #6c757d;
}

/* Validation styling */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #198754;
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
}

/* Text colors */
.text-gray-800 {
    color: #374151 !important;
}
</style>

<!-- Add form validation and auto-save JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
    
    // Auto-save draft functionality (optional)
    const formInputs = document.querySelectorAll('input, textarea');
    let autoSaveTimeout;
    
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Save draft to localStorage
                const formData = new FormData(document.querySelector('form'));
                const draftData = {};
                for (let [key, value] of formData.entries()) {
                    draftData[key] = value;
                }
                localStorage.setItem('supplier_edit_draft_{{ $supplier->id }}', JSON.stringify(draftData));
            }, 1000);
        });
    });
    
    // Load draft data if available
    const savedDraft = localStorage.getItem('supplier_edit_draft_{{ $supplier->id }}');
    if (savedDraft) {
        const draftData = JSON.parse(savedDraft);
        Object.keys(draftData).forEach(key => {
            const input = document.querySelector(`[name="${key}"]`);
            if (input && !input.value) {
                input.value = draftData[key];
            }
        });
    }
    
    // Clear draft on successful submit
    document.querySelector('form').addEventListener('submit', function() {
        localStorage.removeItem('supplier_edit_draft_{{ $supplier->id }}');
    });
    
    // Auto-format phone number
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('855')) {
                value = '+' + value;
            } else if (value.startsWith('0')) {
                value = '+855 ' + value.substring(1);
            }
            e.target.value = value;
        });
    }
});
</script>

@endsection