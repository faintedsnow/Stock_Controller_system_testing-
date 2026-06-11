@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-people me-2 text-primary"></i>
                គ្រប់គ្រងអ្នកផ្គត់ផ្គង់
            </h1>
            <p class="text-muted mb-0">គ្រប់គ្រងនិងតាមដានអ្នកផ្គត់ផ្គង់ទាំងអស់របស់អ្នក</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterSection">
                <i class="bi bi-funnel me-1"></i> តម្រង
            </button>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី
            </a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-people text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $suppliers->count() }}</h4>
                            <small class="text-muted">អ្នកផ្គត់ផ្គង់សរុប</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-success bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-check-circle text-success"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $suppliers->where('email', '!=', null)->count() }}</h4>
                            <small class="text-muted">មានព័ត៌មានទំនាក់ទំនង</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-info bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-box text-info"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $suppliers->sum('inventory_items_count') }}</h4>
                            <small class="text-muted">ទំនិញសរុប</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-warning bg-opacity-10 rounded-2 me-3">
                            <i class="bi bi-clock text-warning"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $suppliers->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
                            <small class="text-muted">បានបន្ថែមថ្មីៗ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section (Collapsible) -->
    <div class="collapse mb-4" id="filterSection">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">ស្វែងរកតាមឈ្មោះ</label>
                        <input type="text" class="form-control" placeholder="បញ្ចូលឈ្មោះអ្នកផ្គត់ផ្គង់..." id="searchSupplier">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">តម្រងតាមទំនិញ</label>
                        <select class="form-select" id="itemCountFilter">
                            <option value="">ទាំងអស់</option>
                            <option value="0">គ្មានទំនិញ</option>
                            <option value="1-5">1-5 ទំនិញ</option>
                            <option value="6-20">6-20 ទំនិញ</option>
                            <option value="21+">21+ ទំនិញ</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">តម្រងតាមកាលបរិច្ឆេទ</label>
                        <select class="form-select" id="dateFilter">
                            <option value="">ទាំងអស់</option>
                            <option value="today">ថ្ងៃនេះ</option>
                            <option value="week">សប្តាហ៍នេះ</option>
                            <option value="month">ខែនេះ</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i> សម្អាត
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-ul text-secondary me-2"></i>
                            បញ្ជីអ្នកផ្គត់ផ្គង់
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted">ការបង្ហាញ: {{ $suppliers->count() }} អ្នកផ្គត់ផ្គង់</small>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary active" data-view="grid">
                                    <i class="bi bi-grid"></i>
                                </button>
                                <button class="btn btn-outline-secondary" data-view="list">
                                    <i class="bi bi-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-3">
                    @if($suppliers->count() > 0)
                        <!-- Grid View -->
                        <div id="gridView" class="row g-4">
                            @foreach($suppliers as $supplier)
                            <div class="col-md-6 col-xl-4 supplier-card" 
                                 data-name="{{ strtolower($supplier->name) }}" 
                                 data-items="{{ $supplier->inventory_items_count }}"
                                 data-created="{{ $supplier->created_at->format('Y-m-d') }}">
                                <div class="card h-100 border-0 shadow-sm card-hover">
                                    <div class="card-header bg-transparent border-0 pb-2">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">{{ $supplier->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    បានបង្កើត {{ $supplier->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                                {{ $supplier->inventory_items_count }} ទំនិញ
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body pt-0">
                                        @if($supplier->contact_person)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-person text-muted me-2"></i>
                                                <span class="small">{{ $supplier->contact_person }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($supplier->email)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-envelope text-muted me-2"></i>
                                                <a href="mailto:{{ $supplier->email }}" class="small text-decoration-none">
                                                    {{ $supplier->email }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($supplier->phone)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bi bi-telephone text-muted me-2"></i>
                                                <a href="tel:{{ $supplier->phone }}" class="small text-decoration-none">
                                                    {{ $supplier->phone }}
                                                </a>
                                            </div>
                                        @endif
                                        
                                        @if($supplier->address)
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="bi bi-geo-alt text-muted me-2 mt-1"></i>
                                                <span class="small text-muted">{{ Str::limit($supplier->address, 80) }}</span>
                                            </div>
                                        @endif
                                        
                                        @if(!$supplier->contact_person && !$supplier->email && !$supplier->phone && !$supplier->address)
                                            <div class="text-center py-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    មិនមានព័ត៌មានទំនាក់ទំនង
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="card-footer bg-transparent border-0 pt-0">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('suppliers.edit', $supplier) }}" 
                                               class="btn btn-outline-primary btn-sm flex-fill">
                                                <i class="bi bi-pencil me-1"></i> កែសម្រួល
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $supplier->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $supplier->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">លុបអ្នកផ្គត់ផ្គង់</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="text-center mb-3">
                                                            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                                        </div>
                                                        <p class="text-center">
                                                            តើអ្នកពិតជាចង់លុប <strong>{{ $supplier->name }}</strong> មែនទេ?
                                                        </p>
                                                        <div class="alert alert-warning">
                                                            <i class="bi bi-info-circle me-2"></i>
                                                            <small>
                                                                វានឹងលុបការភ្ជាប់អ្នកផ្គត់ផ្គង់ពីទំនិញក្នុងស្តុកផងដែរ។
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            បោះបង់
                                                        </button>
                                                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash me-1"></i> លុប
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- List View (Hidden by default) -->
                        <div id="listView" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ឈ្មោះអ្នកផ្គត់ផ្គង់</th>
                                            <th>អ្នកទំនាក់ទំនង</th>
                                            <th>ព័ត៌មានទំនាក់ទំនង</th>
                                            <th>ទំនិញ</th>
                                            <th>កាលបរិច្ឆេទបង្កើត</th>
                                            <th>សកម្មភាព</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($suppliers as $supplier)
                                        <tr class="supplier-row" 
                                            data-name="{{ strtolower($supplier->name) }}" 
                                            data-items="{{ $supplier->inventory_items_count }}"
                                            data-created="{{ $supplier->created_at->format('Y-m-d') }}">
                                            <td>
                                                <div class="fw-semibold">{{ $supplier->name }}</div>
                                            </td>
                                            <td>{{ $supplier->contact_person ?? '-' }}</td>
                                            <td>
                                                <div class="small">
                                                    @if($supplier->email)
                                                        <div><i class="bi bi-envelope me-1"></i> {{ $supplier->email }}</div>
                                                    @endif
                                                    @if($supplier->phone)
                                                        <div><i class="bi bi-telephone me-1"></i> {{ $supplier->phone }}</div>
                                                    @endif
                                                    @if(!$supplier->email && !$supplier->phone)
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    {{ $supplier->inventory_items_count }}
                                                </span>
                                            </td>
                                            <td>{{ $supplier->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('suppliers.edit', $supplier) }}" 
                                                       class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal{{ $supplier->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-muted mb-3">មិនទាន់មានអ្នកផ្គត់ផ្គង់នៅឡើយទេ</h4>
                            <p class="text-muted mb-4">ចាប់ផ្តើមដោយការបន្ថែមអ្នកផ្គត់ផ្គង់ដំបូងរបស់អ្នក។</p>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle me-2"></i> បន្ថែមអ្នកផ្គត់ផ្គង់ដំបូង
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<style>
/* Card Hover Effects */
.card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.12) !important;
}

/* Avatar Styling */
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Background Opacity */
.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* View Toggle Buttons */
.btn-group [data-view].active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

/* Table Styling */
.table th {
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Button Enhancements */
.btn {
    transition: all 0.2s ease-in-out;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Modal Enhancements */
.modal-content {
    border: none;
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
}

/* Khmer Font Support */
body {
    font-family: 'Noto Sans Khmer', 'Khmer OS', sans-serif;
}

/* Custom spacing for better Khmer text rendering */
.small {
    line-height: 1.6;
}

/* Filter animations */
.collapse {
    transition: height 0.35s ease;
}

/* Text colors */
.text-gray-800 {
    color: #374151 !important;
}

/* Hide elements when filtering */
.supplier-card.hidden,
.supplier-row.hidden {
    display: none !important;
}
</style>

<!-- JavaScript for Filtering and View Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View Toggle Functionality
    const viewButtons = document.querySelectorAll('[data-view]');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const view = this.getAttribute('data-view');
            
            // Update active button
            viewButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Toggle views
            if (view === 'grid') {
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
            } else {
                gridView.classList.add('d-none');
                listView.classList.remove('d-none');
            }
        });
    });
    
    // Search and Filter Functionality
    const searchInput = document.getElementById('searchSupplier');
    const itemCountFilter = document.getElementById('itemCountFilter');
    const dateFilter = document.getElementById('dateFilter');
    
    function filterSuppliers() {
        const searchTerm = searchInput.value.toLowerCase();
        const itemCountValue = itemCountFilter.value;
        const dateValue = dateFilter.value;
        
        const suppliers = document.querySelectorAll('.supplier-card, .supplier-row');
        
        suppliers.forEach(supplier => {
            let visible = true;
            
            // Search filter
            if (searchTerm && !supplier.getAttribute('data-name').includes(searchTerm)) {
                visible = false;
            }
            
            // Item count filter
            if (itemCountValue && visible) {
                const itemCount = parseInt(supplier.getAttribute('data-items'));
                switch (itemCountValue) {
                    case '0':
                        visible = itemCount === 0;
                        break;
                    case '1-5':
                        visible = itemCount >= 1 && itemCount <= 5;
                        break;
                    case '6-20':
                        visible = itemCount >= 6 && itemCount <= 20;
                        break;
                    case '21+':
                        visible = itemCount >= 21;
                        break;
                }
            }
            
            // Date filter
            if (dateValue && visible) {
                const createdDate = new Date(supplier.getAttribute('data-created'));
                const today = new Date('2025-06-17');
                
                switch (dateValue) {
                    case 'today':
                        visible = createdDate.toDateString() === today.toDateString();
                        break;
                    case 'week':
                        const weekAgo = new Date(today);
                        weekAgo.setDate(today.getDate() - 7);
                        visible = createdDate >= weekAgo;
                        break;
                    case 'month':
                        const monthAgo = new Date(today);
                        monthAgo.setMonth(today.getMonth() - 1);
                        visible = createdDate >= monthAgo;
                        break;
                }
            }
            
            // Show/hide supplier
            if (visible) {
                supplier.classList.remove('hidden');
            } else {
                supplier.classList.add('hidden');
            }
        });
        
        // Update visible count
        const visibleCount = document.querySelectorAll('.supplier-card:not(.hidden), .supplier-row:not(.hidden)').length;
        document.querySelector('.card-header small').textContent = `ការបង្ហាញ: ${visibleCount} អ្នកផ្គត់ផ្គង់`;
    }
    
    // Add event listeners
    searchInput.addEventListener('input', filterSuppliers);
    itemCountFilter.addEventListener('change', filterSuppliers);
    dateFilter.addEventListener('change', filterSuppliers);
    
    // Clear filters function
    window.clearFilters = function() {
        searchInput.value = '';
        itemCountFilter.value = '';
        dateFilter.value = '';
        filterSuppliers();
    };
});
</script>

@endsection