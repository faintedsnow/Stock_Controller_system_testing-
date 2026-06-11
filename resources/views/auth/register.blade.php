@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-5 col-md-7">
            <!-- Registration Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 text-center py-4">
                    <div class="mb-3">
                        <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="mb-0 text-gray-800">បង្កើតគណនីថ្មី</h3>
                    <p class="text-muted mb-0">ចូលរួមជាមួយប្រព័ន្ធគ្រប់គ្រងស្តុកទំនិញ</p>
                </div>
                
                <div class="card-body px-4 pb-4">
                    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                ឈ្មោះពេញ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-muted"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       placeholder="បញ្ចូលឈ្មោះពេញរបស់អ្នក"
                                       autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                ឈ្មោះនេះនឹងបង្ហាញក្នុងប្រព័ន្ធ
                            </div>
                        </div>
                        
                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                អ៊ីម៉ែល <span class="text-danger">*</span>
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
                                       required
                                       placeholder="example@email.com"
                                       autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                អ៊ីម៉ែលនេះនឹងប្រើសម្រាប់ចូលប្រព័ន្ធ
                            </div>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                ពាក្យសម្ងាត់ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="password-strength mt-2" id="passwordStrength" style="display: none;">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="strengthBar"></div>
                                </div>
                                <small class="text-muted" id="strengthText"></small>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-shield-check me-1"></i>
                                យ៉ាងហោចណាស់ 8 តួអក្សរ រួមទាំងលេខ និងអក្សរពិសេស
                            </div>
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                បញ្ជាក់ពាក្យសម្ងាត់ <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock-fill text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="បញ្ចូលពាក្យសម្ងាត់ម្តងទៀត"
                                       autocomplete="new-password">
                            </div>
                            <div class="password-match mt-2" id="passwordMatch" style="display: none;">
                                <small id="matchText"></small>
                            </div>
                        </div>
                        
                        
                        <!-- Submit Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus me-2"></i>បង្កើតគណនី
                            </button>
                        </div>
                    </form>
                    
                    <!-- Divider -->
                    <div class="text-center my-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 border-top"></div>
                            <span class="px-3 text-muted small">ឬ</span>
                            <div class="flex-grow-1 border-top"></div>
                        </div>
                    </div>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-muted mb-0">
                            មានគណនីរួចហើយ? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                ចូលប្រព័ន្ធនៅទីនេះ
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- System Info -->
            <div class="text-center mt-4">
                <div class="card border-0 bg-light">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-center align-items-center gap-4 text-muted small">
                            <div>
                                <i class="bi bi-shield-check me-1"></i>
                                ការពារដោយសុវត្ថិភាព
                            </div>

                            <div>
                                <i class="bi bi-globe me-1"></i>
                                Stock Controller System
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Side Information Panel (Hidden on mobile) -->
        <div class="col-lg-4 d-none d-lg-block">
            <div class="ps-5">
                <div class="mb-5">
                    <h2 class="text-primary mb-3">
                        <i class="bi bi-box-seam me-2"></i>
                        Stock Controller System
                    </h2>
                    <p class="text-muted lead">
                        ប្រព័ន្ធគ្រប់គ្រងស្តុកទំនិញទំនើបសម្រាប់អាជីវកម្មរបស់អ្នក
                    </p>
                </div>
                
                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="avatar-lg bg-primary bg-opacity-10 rounded-3 me-3">
                                <i class="bi bi-graph-up text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">តាមដានស្តុកទំនិញ</h5>
                                <p class="text-muted small mb-0">គ្រប់គ្រងនិងតាមដានស្តុកទំនិញរបស់អ្នកដោយងាយស្រួល</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="avatar-lg bg-success bg-opacity-10 rounded-3 me-3">
                                <i class="bi bi-people text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">គ្រប់គ្រងអ្នកផ្គត់ផ្គង់</h5>
                                <p class="text-muted small mb-0">រៀបចំនិងគ្រប់គ្រងព័ត៌មានអ្នកផ្គត់ផ្គង់ទាំងអស់</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-start">
                            <div class="avatar-lg bg-warning bg-opacity-10 rounded-3 me-3">
                                <i class="bi bi-cart-check text-warning fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">ការបញ្ជាទិញស្វ័យប្រវត្តិ</h5>
                                <p class="text-muted small mb-0">ទទួលការជូនដំណឹងនិងធ្វើការបញ្ជាទិញស្តុកដោយស្វ័យប្រវត្តិ</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <div class="bg-light rounded-3 p-4">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-star-fill me-2"></i>
                            សម្រាប់អ្នកប្រើថ្មី
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                ការបង្កើតគណនីឥតគិតថ្លៃ
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                ការគាំទ្រ 24/7
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                ការបណ្តុះបណ្តាលឥតគិតថ្លៃ
                            </li>
                        </ul>
                    </div>
                </div>
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
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.15) !important;
}

/* Avatar styling */
.avatar-lg {
    width: 4rem;
    height: 4rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Background Opacity */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Password Strength Indicator */
.progress {
    background-color: rgba(0, 0, 0, 0.1);
}

.strength-weak { background-color: #dc3545; }
.strength-medium { background-color: #ffc107; }
.strength-strong { background-color: #198754; }

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
.form-control.is-valid {
    border-color: #198754;
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
}

/* Minimum height for full screen */
.min-vh-100 {
    min-height: 100vh;
}

/* Loading state */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Form check styling */
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
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
        
        // Add loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>កំពុងបង្កើតគណនី...';
    }, false);
    
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        if (type === 'password') {
            toggleIcon.className = 'bi bi-eye';
        } else {
            toggleIcon.className = 'bi bi-eye-slash';
        }
    });
    
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = [];
        
        if (password.length >= 8) strength++;
        else feedback.push('យ៉ាងហោចណាស់ 8 តួអក្សរ');
        
        if (/[a-z]/.test(password)) strength++;
        else feedback.push('អក្សរតូច');
        
        if (/[A-Z]/.test(password)) strength++;
        else feedback.push('អក្សរធំ');
        
        if (/\d/.test(password)) strength++;
        else feedback.push('លេខ');
        
        if (/[^a-zA-Z\d]/.test(password)) strength++;
        else feedback.push('អក្សរពិសេស');
        
        return { strength, feedback };
    }
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password.length > 0) {
            passwordStrength.style.display = 'block';
            const { strength, feedback } = checkPasswordStrength(password);
            
            let className = '';
            let text = '';
            let width = 0;
            
            if (strength <= 2) {
                className = 'strength-weak';
                text = 'ខ្សោយ - ត្រូវការ: ' + feedback.join(', ');
                width = 33;
            } else if (strength <= 4) {
                className = 'strength-medium';
                text = 'មធ្យម - ត្រូវការ: ' + feedback.join(', ');
                width = 66;
            } else {
                className = 'strength-strong';
                text = 'ខ្លាំង - ពាក្យសម្ងាត់ល្អ';
                width = 100;
            }
            
            strengthBar.className = `progress-bar ${className}`;
            strengthBar.style.width = width + '%';
            strengthText.textContent = text;
            strengthText.className = `text-${className.split('-')[1]}`;
        } else {
            passwordStrength.style.display = 'none';
        }
    });
    
    // Password confirmation match checker
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordMatch = document.getElementById('passwordMatch');
    const matchText = document.getElementById('matchText');
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword.length > 0) {
            passwordMatch.style.display = 'block';
            
            if (password === confirmPassword) {
                matchText.textContent = 'ពាក្យសម្ងាត់ត្រូវគ្នា';
                matchText.className = 'text-success';
                confirmPasswordInput.classList.remove('is-invalid');
                confirmPasswordInput.classList.add('is-valid');
            } else {
                matchText.textContent = 'ពាក្យសម្ងាត់មិនត្រូវគ្នា';
                matchText.className = 'text-danger';
                confirmPasswordInput.classList.remove('is-valid');
                confirmPasswordInput.classList.add('is-invalid');
            }
        } else {
            passwordMatch.style.display = 'none';
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
        }
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    // Email validation
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.classList.add('is-invalid');
            if (!this.parentNode.querySelector('.invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'អ៊ីម៉ែលមិនត្រឹមត្រូវ';
                this.parentNode.appendChild(feedback);
            }
        } else if (email) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });
    
    // Name validation (no numbers)
    const nameInput = document.getElementById('name');
    nameInput.addEventListener('input', function() {
        const name = this.value;
        if (/\d/.test(name)) {
            this.value = name.replace(/\d/g, '');
        }
    });
});
</script>

@endsection