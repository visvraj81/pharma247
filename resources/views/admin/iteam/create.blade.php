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
    <h5>Add Item Bulk</h5>
    <br>
    <form action="{{route('iteam.bluk.store')}}" method="POST" enctype="multipart/form-data" id="agentForm">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <label for="">Add Item Bulk</label>
                <input type="file" name="iteam_data" placeholder="Enter Name" class="form-control">
                 <a href="{{ asset('public/ItemSample_Data (4).csv') }}" download>Download CSV</a>

            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary ">Save Change</button>
        </div>
    </form>
</div>
@endsection
@section('js')
<script>
    $('.dark_logo').change(function() {
        var curElement = $('.image1');
        console.log(curElement);
        var reader = new FileReader();

        reader.onload = function(e) {
            curElement.attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });
</script>
<script>
    $(document).ready(function() {
        $('#numericInput').on('keypress', function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            // Allow only numbers (48-57) and backspace (8)
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
                event.preventDefault();
            }
        });
    });

    $('#agentForm').on('submit', function() {
        $('button[type="submit"]').attr('disabled', 'disabled');
        $('#loadingSpinner').show();
    });
</script>
@endsection