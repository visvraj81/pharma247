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

    .cke_notification_warning {
        display: none !important;
    }
</style>
<div class="card p-3">
    <h5>Add Slider</h5>
    <br>
    <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row mt-3">
            <div class="col-md-5">
                <label for="banner">Slider</label>
                <input type="file" name="banner" class="form-control image">
                <img class="imageshow" src="http://via.placeholder.com/700x500" width="100px" style="margin-top: 10px;">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" placeholder="Enter title">
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" placeholder="Enter description"></textarea>
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
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
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

        // Initialize CKEditor for the description field
        CKEDITOR.replace('description');
    });
</script>
@endsection
