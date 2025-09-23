@extends('layouts.main')
@section('main')
<style>
    .error {
        color: red;
        font-size: 0.875em;
        margin-top: 0.25em;
    }

    input.error,
    select.error {
        border-color: red;
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
</style>
<div class="card p-3">
    <h5>Add New Pharma Shop</h5>
    <br>
    <form action="{{ route('pharma.store') }}" method="POST" enctype="multipart/form-data" id="agentForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="pharma_name">Owner Name</label>
                <input type="text" name="pharma_name" class="form-control" placeholder="Enter Owner Name" value="{{ old('pharma_name') }}">
            </div>
            <div class="col-md-6">
                <label for="pharma_short_name">Shop Name</label>
                <input type="text" name="pharma_short_name" class="form-control" placeholder="Enter Shop Name" value="{{ old('pharma_short_name') }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="pharma_email">Pharma Email</label>
                <input type="text" name="pharma_email" class="form-control" placeholder="Enter Pharma Email" value="{{ old('pharma_email') }}">
            </div>
            <div class="col-md-6">
                <label for="pharma_phone">Pharma Phone</label>
                <input type="text" name="pharma_phone" class="form-control" placeholder="Enter Pharma Phone" maxlength="10" id="numericInput" value="{{ old('pharma_phone') }}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="city">City</label>
                <input type="text" name="city" placeholder="Enter City" class="form-control" value="{{ old('city') }}">
            </div>
            <div class="col-md-6">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="0" {{ old('status') == "0" ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>Active</option>
                    <option value="2" {{ old('status') == "2" ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="pharma_address">Pharma Address</label>
                <input type="text" name="pharma_address" class="form-control" placeholder="Enter Pharma Address" value="{{ old('pharma_address') }}">
            </div>
            <div class="col-md-6">
                <label for="agent_id">Agent</label>
                <select name="agent_id" id="agent_id" class="form-control agent_id">
                    <option value="">Select Agent</option>
                    @if(isset($agent))
                    @foreach($agent as $list)
                    <option value="{{ $list->id }}" {{ old('agent_id') == $list->id ? 'selected' : '' }}>{{ $list->name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
        <br>
        <div id="dataAgent"></div>
        <br>
        <h5>Admin Account Details</h5>
        <br>
        <div class="row">
            <div class="col-md-6">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Enter Email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="col-md-6">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Enter Password" class="form-control">
            </div>
        </div>
        <br>
        <h5>Pharma Logo</h5>
        <br>
        <div class="row">
            <div class="col-md-3">
                <label for="dark_logo">Dark Logo</label>
                <input type="file" name="dark_logo" class="form-control dark_logo">
                <img class="image1" src="http://via.placeholder.com/700x500" width="100px" style="margin-top: 10px;">
                <label id="dark_logo-error" class="error" for="dark_logo" style="display:none;">Please upload the dark logo</label>
            </div>
            <div class="col-md-3">
                <label for="light_logo">Light Logo</label>
                <input type="file" name="light_logo" class="form-control light_logo">
                <img class="image2" src="http://via.placeholder.com/700x500" width="100px" style="margin-top: 10px;">
                <label id="light_logo-error" class="error" for="light_logo" style="display:none;">Please upload the light logo</label>
            </div>
            <div class="col-md-3">
                <label for="small_dark_logo">Small Dark Logo</label>
                <input type="file" name="small_dark_logo" class="form-control small_dark_logo">
                <img class="image3" src="http://via.placeholder.com/700x500" width="100px" style="margin-top: 10px;">
                <label id="small_dark_logo-error" class="error" for="small_dark_logo" style="display:none;">Please upload the small dark logo</label>
            </div>
            <div class="col-md-3">
                <label for="small_light_logo">Small Light Logo</label>
                <input type="file" name="small_light_logo" class="form-control small_light_logo">
                <img class="image4" src="http://via.placeholder.com/700x500" width="100px" style="margin-top: 10px;">
                <label id="small_light_logo-error" class="error" for="small_light_logo" style="display:none;">Please upload the small light logo</label>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
    $(document).on('change', '.agent_id', function() {

        var data = $(this).val();
        $.ajax({
            type: 'GET', //THIS NEEDS TO BE GET
            url: "{{ route('agent.plan') }}",
            data: {
                data: data,
            },
            success: function(data) {
                console.log(data.planData);
                $("#dataAgent").html(data.planData);
            },
            error: function() {
                console.log('Error occurred');
            }
        });
    });

    // Image preview logic
    function readURL(input, imgElement) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                imgElement.attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.dark_logo').change(function() {
        readURL(this, $('.image1'));
    });

    $('.light_logo').change(function() {
        readURL(this, $('.image2'));
    });

    $('.small_dark_logo').change(function() {
        readURL(this, $('.image3'));
    });

    $('.small_light_logo').change(function() {
        readURL(this, $('.image4'));
    });

    $(document).ready(function() {
        // Restrict input to digits by using a regular expression filter.
        $('#numericInput').on('keypress', function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
                event.preventDefault();
            }
        });

        // Custom email check method
        $.validator.addMethod("emailExists", function(value, element) {
            let exists = false;
            $.ajax({
                type: "POST",
                url: "{{ route('pharma.checkEmail') }}",
                data: {
                    email: value,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "json",
                async: false,
                success: function(response) {
                    exists = response.exists === false;
                }
            });
            return exists;
        }, "Email already exists");

        // Form validation rules
        $('#agentForm').validate({
            errorClass: 'error', // use your custom error class
            rules: {
                pharma_name: {
                    required: true,
                    minlength: 3
                },
                pharma_short_name: {
                    required: true,
                    minlength: 2
                },
                pharma_email: {
                    required: true,
                    email: true,
                    emailExists: true // Use the custom email check method
                },
                pharma_phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                city: {
                    required: true
                },
                status: {
                    required: true
                },
                pharma_address: {
                    required: true
                },
                agent_id: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                    emailExists: true // Use the custom email check method
                },
                password: {
                    required: true,
                    minlength: 6
                },
                dark_logo: {
                    required: true
                },
                light_logo: {
                    required: true
                },
                small_dark_logo: {
                    required: true
                },
                small_light_logo: {
                    required: true
                }
            },
            messages: {
                pharma_name: {
                    required: "Please enter the Owner Name",
                    minlength: "Pharma name must be at least 3 characters long"
                },
                pharma_short_name: {
                    required: "Please enter the Shop Name",
                    minlength: "Pharma short name must be at least 2 characters long"
                },
                pharma_email: {
                    required: "Please enter the pharma email",
                    email: "Please enter a valid email address"
                },
                pharma_phone: {
                    required: "Please enter the pharma phone number",
                    digits: "Please enter only digits",
                    minlength: "Pharma phone number must be 10 digits long",
                    maxlength: "Pharma phone number must be 10 digits long"
                },
                city: {
                    required: "Please enter the city"
                },
                status: {
                    required: "Please select the status"
                },
                pharma_address: {
                    required: "Please enter the pharma address"
                },
                agent_id: {
                    required: "Please select an agent"
                },
                email: {
                    required: "Please enter an email",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: "Please enter a password",
                    minlength: "Password must be at least 6 characters long"
                },
                dark_logo: {
                    required: "Please upload the dark logo"
                },
                light_logo: {
                    required: "Please upload the light logo"
                },
                small_dark_logo: {
                    required: "Please upload the small dark logo"
                },
                small_light_logo: {
                    required: "Please upload the small light logo"
                }
            },
            submitHandler: function(form) {
                $('button[type="submit"]').attr('disabled', 'disabled');
                $('#loadingSpinner').show();
                form.submit();
            }
        });
    });
</script>
@endsection