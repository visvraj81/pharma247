@extends('layouts.main')
@section('main')
<style>
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
</style>
<div class="card p-3">
    <h5>Edit User</h5>
    <br>
    <form action="{{ route('superadmin.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <input type="hidden" name="id" value="{{ $editDetails['id'] }}">
                <label for="">Name</label>
                <input type="text" name="name" value="{{ $editDetails['name'] }}" class="form-control" placeholder="Please Enter Name">
            </div>
            <div class="col-md-6">
                <label for="">Email</label>
                <input type="email" name="email" value="{{ $editDetails['email'] }}" class="form-control" placeholder="Please Enter Email">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Phone Number</label>
                <input type="text" name="number" value="{{ $editDetails['phone_number'] }}" class="form-control" placeholder="Please Enter Phone Number" maxlength="10" id="numericInput">
            </div>

            <div class="col-md-6">
                <label for="">Password</label>
                <input type="text" name="password" value="{{ $editDetails['password'] }}" class="form-control" placeholder="Please Enter Password">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Status</label>
                <select name="status" id="" class="form-control">
                    <option value="">Select Status</option>
                    <option value="1" {{ $editDetails['admin_status'] == '1' ? 'selected' : '' }}>Enabled</option>
                    <option value="0" {{ $editDetails['admin_status'] == '0' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="">Image</label>
                <input type="file" name="image" class="form-control image">
                <img class="imageshow" src="{{ $editDetails['image'] }}" width="100px" style="margin-top: 10px;">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Phone Number</label>
                <input type="text" name="number" value="{{ $editDetails['phone_number'] }}" class="form-control" placeholder="Please Enter Phone Number" maxlength="10" id="numericInput">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <strong>Role:</strong>
                <select name="roles[]" id="" class="form-control select2">
                    @foreach ($roles as $roleId => $roleName)
                    <option value="{{ $roleId }}" {{ isset($userRole[$roleId]) ? 'selected' : '' }}>{{ $roleName }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>

@endsection
@section('js')

<script>
    $(document).ready(function() {
        $('#numericInput').on('keypress', function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            // Allow only numbers (48-57) and backspace (8)
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
                event.preventDefault();
            }
        });

        $('.image').change(function() {
            var curElement = $('.imageshow');
            console.log(curElement);
            var reader = new FileReader();

            reader.onload = function(e) {
                curElement.attr('src', e.target.result);
            };

            reader.readAsDataURL(this.files[0]);
        });
    });
</script>

@endsection