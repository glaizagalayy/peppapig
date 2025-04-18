@extends('layouts.admin')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 list-unstyled">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">Add New User</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.storeUser') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="login_id" class="form-label">Login ID</label>
                            <input type="text" class="form-control" id="login_id" name="login_id" autocomplete="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required onchange="toggleFields()">
                                <option value="student">Student</option>
                                <option value="finance">Finance Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <!-- Student Fields -->
                        <div id="student-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="student_first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="student_first_name" name="first_name" autocomplete="given-name">
                            </div>
                            <div class="mb-3">
                                <label for="student_last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="student_last_name" name="last_name" autocomplete="family-name">
                            </div>
                            <div class="mb-3">
                                <label for="middle_initial" class="form-label">Middle Initial</label>
                                <input type="text" class="form-control" id="middle_initial" name="middle_initial">
                            </div>
                            <div class="mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix">
                            </div>
                            <div class="mb-3">
                                <label for="batch_year" class="form-label">Batch Year</label>
                                <input type="number" class="form-control" id="batch_year" name="batch_year">
                            </div>
                            <div class="mb-3">
                                <label for="group_num" class="form-label">Group Number</label>
                                <input type="number" class="form-control" id="group_num" name="group_num">
                            </div>
                            <div class="mb-3">
                                <label for="student_number" class="form-label">Student Number</label>
                                <input type="number" class="form-control" id="student_number" name="student_number">
                            </div>
                            <div class="mb-3">
                                <label for="center_training_code" class="form-label">Center Training Code</label>
                                <input type="text" class="form-control" id="center_training_code" name="center_training_code" maxlength="1">
                            </div>
                            <div class="mb-3">
                                <label for="region_code" class="form-label">Region Code</label>
                                <input type="number" class="form-control" id="region_code" name="region_code">
                            </div>
                        </div>

                        <!-- Admin/Finance Fields -->
                        <div id="admin-finance-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="admin_finance_first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="admin_finance_first_name" name="first_name" autocomplete="given-name">
                            </div>
                            <div class="mb-3">
                                <label for="admin_finance_last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="admin_finance_last_name" name="last_name" autocomplete="family-name">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" aria-label="Add new user">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFields() {
        const role = document.getElementById('role').value;
        const studentFields = document.getElementById('student-fields');
        const adminFinanceFields = document.getElementById('admin-finance-fields');

        // Define required student fields
        const requiredStudentFields = [
            'student_first_name',
            'student_last_name',
            'batch_year',
            'group_num',
            'student_number',
            'center_training_code',
            'region_code'
        ];

        if (role === 'student') {
            studentFields.style.display = 'block';
            adminFinanceFields.style.display = 'none';
            
            // Handle student fields
            studentFields.querySelectorAll('input').forEach(input => {
                input.disabled = false;
                if (requiredStudentFields.includes(input.id)) {
                    input.required = true;
                }
            });
            
            // Disable admin/finance fields
            adminFinanceFields.querySelectorAll('input').forEach(input => {
                input.disabled = true;
                input.required = false;
                input.value = ''; // Clear values
            });
        } else {
            studentFields.style.display = 'none';
            adminFinanceFields.style.display = 'block';
            
            // Disable and clear student fields
            studentFields.querySelectorAll('input').forEach(input => {
                input.disabled = true;
                input.required = false;
                input.value = ''; // Clear values
            });
            
            // Enable admin/finance fields
            adminFinanceFields.querySelectorAll('input').forEach(input => {
                input.disabled = false;
                input.required = true;
            });
        }
    }

    // Call toggleFields on page load and role change
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
        
        // Add error class to invalid fields
        const invalidFields = document.querySelectorAll('.is-invalid');
        invalidFields.forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    });

    // Auto-hide alerts after 30 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            if (alert) {
                new bootstrap.Alert(alert).close();
            }
        });
    }, 30000);
</script>
<script src="{{ asset('js/adminSidebar.js') }}"></script>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 30 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            if (alert) {
                new bootstrap.Alert(alert).close();
            }
        });
    }, 30000);

    // Call toggleFields on page load to set initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleFields();
    });
</script>
@endpush