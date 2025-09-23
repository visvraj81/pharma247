@extends('layouts.main')
@section('main')
<div class="card p-3">
    <h5>Payment Method</h5>
    <br>
    <form action="{{ route('payment.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="">Name</label>
                <input type="hidden" name="id" value="{{$paymentMethod->id}}" />
                <input type="text" name="payment_name" value="{{$paymentMethod->payment_method}}" class="form-control" placeholder="Please Enter Name">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Image</label>
                <input type="file" name="payment_image" class="form-control image">
                <img class="imageshow" src="{{asset('payment_image/'.$paymentMethod->icon)}}" width="100px" style="margin-top: 10px;">
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