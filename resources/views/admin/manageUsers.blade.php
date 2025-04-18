@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.manageUsers') }}">
                <label for="role" class="form-label">Filter by Role:</label>
                <select name="role" id="role" class="form-select" onchange="this.form.submit()">
                    <option value="">All Users</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Students</option>
                    <option value="finance" {{ request('role') == 'finance' ? 'selected' : '' }}>Finance Users</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #FF9933;">
                    <h5 class="mb-0">User List</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.addUser') }}" class="btn btn-primary mb-3">
                        <i class="fas fa-user-plus me-2"></i>Add User
                    </a>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Login ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->login_id }}</td>
                                    <td>
                                        @switch($user->role)
                                            @case('student')
                                                {{ optional($user->student)->first_name }} {{ optional($user->student)->last_name }}
                                                @break
                                            @case('finance')
                                                {{ optional($user->finance)->first_name }} {{ optional($user->finance)->last_name }}
                                                @break
                                            @case('admin')
                                                {{ optional($user->admin)->first_name }} {{ optional($user->admin)->last_name }}
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.editUser', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @if($user->is_active)
                                            <form action="{{ route('admin.deactivateUser', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.activateUser', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alert after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
</script>
@endpush