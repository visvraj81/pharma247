@extends('layouts.main')
@section('main')
<style>
    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        border-radius: 50%;
        background-color: #fff;
        border: 1px #ccc solid;
    }

    .container:hover input~.checkmark {
        background-color: white;
    }

    .container input:checked~.checkmark {
        background-color: #ffbc06;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .btn-primary.focus,
    .btn-primary:focus {
        color: #fff;
        background-color: #628a2f;
        border-color: #628a2f;
    }

    .container input:checked~.checkmark:after {
        display: block;
    }

    .container .checkmark:after {
        left: 8px;
        top: 3px;
        width: 7px;
        height: 13px;
        border: solid white;
        border-width: 0 2px 2px 0;
        -webkit-transform: rotate(35deg);
        -ms-transform: rotate(35deg);
        transform: rotate(35deg);
    }
</style>
<div class="card p-3">
    <h5>Add Role</h5>
    <br>
    <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data" id="roleForm">
        @csrf
        <div class="row mt-3">
            <div class="col-md-5">
                <label for="roleName">Name</label>
                <input type="text" id="roleName" name="name" class="form-control image" placeholder="Enter Name">
                <span id="nameError" style="color:red; display:none;">Please enter a role name.</span>
            </div>
        </div>
        @php
        $permissions = [
        'role' => ['role-create', 'role-delete', 'role-edit', 'role-list'],
        'user' => ['user-create', 'user-delete', 'user-edit', 'user-list'],
        'permissions' => ['permissions-create', 'permissions-delete', 'permissions-edit', 'permissions-list'],
        'lead' => ['lead-create', 'lead-list'],
        'shop' => ['pharma-create','pharma-delete','pharma-edit','pharma-list'],
        'banner' => ['banner-create','banner-delete','banner-edit','banner-list'],
        'subscription' => ['subscription-create','subscription-delete','subscription-edit','subscription-list'],
        'agent' => ['agent-create','agent-delete','agent-edit','agent-list'],
        'transction' => ['transction-create','transction-delete','transction-edit','transction-list'],
        'offlinerequest' => ['offlinerequest-create','offlinerequest-delete','offlinerequest-edit','offlinerequest-list'],
        'emailqueries' => ['emailqueries-create','emailqueries-delete','emailqueries-edit','emailqueries-list'],
        'superadmin' => ['superadmin-create','superadmin-delete','superadmin-edit','superadmin-list'],
        'support' => ['support-create','support-delete','support-edit','support-list'],
        'profile' => ['profile-edit'],
        'privacy-policy' => ['privacy-policy'],
        'reference'=>['reference'],
        'youtue-list'=>['youtue-list','youtue-create','youtue-delete','youtue-edit']
        ];
        @endphp
        <div class="card-body">
            <div class="form-group p-0">
                <label class="text-dark font">{{ __('Permissions') }}</label>
                <div class="row">
                    @foreach($permissions as $groupName => $permissionGroup)
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="section-title mt-0 text-dark font-weight-bold"> &nbsp;
                                    <label class="container">
                                        <input type="checkbox" name="{{ ucfirst($groupName) }}" class="role-checkbox" value="{{ ucfirst($groupName) }}">
                                        <span class="checkmark" style="margin-top: 7px;"></span>
                                    </label>
                                    <span class="custom-checkbox-description" style="margin-left: 30px; font-size: 14px;">{{ ucfirst($groupName) }}</span>
                                </div>
                            </div>
                            @foreach($permissionGroup as $permission)
                            <div class="col-md-12 col-sm-3">
                                <label class="custom-checkbox" style="margin-left: 20px;">
                                    <label class="container">
                                        <input type="checkbox" name="permission[]" class="permission-checkbox" value="{{ $permission }}">
                                        <span class="checkmark" style="margin-top: 7px;"></span>
                                    </label>
                                    <span class="custom-checkbox-description" style="margin-left: 30px;">{{ ucfirst($permission) }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <br>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>

@endsection
@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle change event for role checkboxes
        $('.role-checkbox').change(function() {
            // Get the permission checkboxes within the same parent
            var permissionCheckboxes = $(this).closest('.row').find('.permission-checkbox');
            // Check or uncheck permission checkboxes based on role checkbox state
            permissionCheckboxes.prop('checked', this.checked);
        });

        $('#roleForm').on('submit', function(e) {
            var roleName = $('#roleName').val().trim();
            if (roleName === '') {
                e.preventDefault();
                $('#nameError').show();
            } else {
                $('#nameError').hide();
            }
        });
    });

    $(document).on('change', '.role-checkbox-edit', function() {
        // Get the permission checkboxes within the same parent
        var permissionCheckboxes = $(this).closest('.row').find('.permission-checkbox-edit');
        // Check or uncheck permission checkboxes based on role checkbox state
        permissionCheckboxes.prop('checked', this.checked);
    });
</script>
@endsection