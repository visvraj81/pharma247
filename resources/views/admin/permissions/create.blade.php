@extends('layouts.main')

@section('main')
<div class="card p-3">
    <h5>Add Permissions</h5>
    <br>
    <form id="permissionsForm" action="{{ route('permissions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mt-3">
            <div class="col-md-5">
                <label for="permissions">Permissions</label>
                <input type="text" id="permissions" name="permissions" class="form-control" placeholder="Module_Permissions_Name">
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
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
$(document).ready(function() {
    $("#permissionsForm").validate({
        rules: {
            permissions: {
                required: true,
                // minlength: 3
            }
        },
        messages: {
            permissions: {
                required: "Please enter a permission name",
                minlength: "Permission name must be at least 3 characters long"
            }
        },
        errorElement: "span",
        errorClass: "text-danger",
        highlight: function(element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        }
    });
});
</script>
@endsection
