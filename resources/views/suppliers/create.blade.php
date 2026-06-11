@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-plus-circle me-2 text-primary"></i>
                បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី
            </h1>
            <p class="text-muted mb-0">បំពេញព័ត៌មានអ្នកផ្គត់ផ្គង់ថ្មីសម្រាប់ស្តុកទំនិញ</p>
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
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-badge text-primary me-2"></i>
                        ព័ត៌មានអ្នកផ្គត់ផ្គង់
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <form method="POST" action="{{ route('suppliers.store') }}" class="needs-validation" novalidate>
                        @csrf
                        
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
                                       value="{{ old('name') }}" 
                                       required 
                                       placeholder="ឧ. ក្រុមហ៊ុនផ្គត់ផ្គង់ ABC">
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
                                       value="{{ old('contact_person') }}" 
                                       placeholder="ឧ. លោក ស្មីត ចន">
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
                                               value="{{ old('email') }}" 
                                               placeholder="supplier@example.com">
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
                                               value="{{ old('phone') }}" 
                                               placeholder="+855 12 345 678">
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
                                          rows="3" 
                                          placeholder="អាសយដ្ឋានពេញលេញ រួមទាំងផ្លូវ ខណ្ឌ រាជធានី">{{ old('address') }}</textarea>
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
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>
                                បង្កើតអ្នកផ្គត់ផ្គង់
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Tips Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        គន្លឹះសម្រាប់បំពេញ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            ព័ត៌មានអ្នកផ្គត់ផ្គង់៖
                        </h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                មានតែឈ្មោះអ្នកផ្គត់ផ្គង់ប៉ុណ្ណោះដែលត្រូវការ
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                បន្ថែមព័ត៌មានទំនាក់ទំនងសម្រាប់ការងាយស្រួល
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                អ្នកអាចកែសម្រួលព័ត៌មាននេះពេលក្រោយ
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h6 class="text-info mb-2">
                            <i class="bi bi-arrow-right-circle me-1"></i>
                            ជំហានបន្ទាប់៖
                        </h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2">
                                <i class="bi bi-dot"></i>
                                បន្ទាប់ពីបង្កើត ភ្ជាប់ទំនិញជាមួយអ្នកផ្គត់ផ្គង់នេះ
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-dot"></i>
                                ប្រើព័ត៌មានអ្នកផ្គត់ផ្គង់សម្រាប់ការបញ្ជាទិញ
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-dot"></i>
                                តាមដានដំណើរការអ្នកផ្គត់ផ្គង់តាមពេលវេលា
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Card -->
            <div class="card border-0 shadow-sm">
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
                                <h4 class="text-primary mb-1">{{ $totalSuppliers ?? 0 }}</h4>
                                <small class="text-muted">អ្នកផ្គត់ផ្គង់សរុប</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-1">{{ $activeSuppliers ?? 0 }}</h4>
                                <small class="text-muted">កំពុងសកម្ម</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="bi bi-clock me-2"></i>
                            <span>បានបង្កើតនៅ {{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
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

/* Validation styling */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.94-.94 1.94 1.94L8.5 3.41l-.94-.94L4.26 5.79z'/%3e%3c/svg%3e");
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>

<!-- Add form validation JavaScript -->
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
    
    // Auto-format phone number (optional)
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