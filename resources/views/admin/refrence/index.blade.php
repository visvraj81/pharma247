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
    <h5>Reference</h5>
    <form action="{{ route('refrence.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Image</label>
                <input type="file" name="image" class="form-control image">
                <img class="imageshow" src="{{ isset($settingData->image) ? asset('public/uploads/students/'.$settingData->image) :""   }}" width="100px" style="margin-top: 10px;">
            </div>
             <div class="col-md-6">
                <label for="">Reference Image</label>
                <input type="file" name="reference_image" class="form-control reference_image">
                <img class="imageshowreference_image" src="{{ isset($settingData->reference_image) ? asset('uploads/students/'.$settingData->reference_image) :""   }}" width="100px" style="margin-top: 10px;">
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
  
     $(document).ready(function() {
        $('#numericInput').on('keypress', function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            // Allow only numbers (48-57) and backspace (8)
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
                event.preventDefault();
            }
        });

        $('.reference_image').change(function() {
            var curElement = $('.imageshowreference_image');
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