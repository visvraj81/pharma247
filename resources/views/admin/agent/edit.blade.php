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
    <h5>Update Agent</h5>
    <br>
    <form action="{{route('agent.update')}}" method="POST" enctype="multipart/form-data" id="agentForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="">Name</label>
                <input type="hidden" name="id" value="{{$detailsGet['id']}}" />
                <input type="text" name="name" placeholder="Enter Name" value="{{$detailsGet['name']}}" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="Enter Email" value="{{$detailsGet['email']}}" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Password</label>
                <input type="text" name="password" placeholder="Enter Password" class="form-control" value="{{$detailsGet['password']}}">

            </div>
            <div class="col-md-6">
                <label for="">Phone Number</label>
                <input type="text" name="phone_number" placeholder="Enter Phone Number" value="{{$detailsGet['phone_number']}}" class="form-control" maxlength="10" id="numericInput">
            </div>
        </div>
        <br>
        <?php
        $subscription = \App\Models\SubscriptionPlan::get();
        ?>
        @if(isset($subscription))
        @foreach($subscription as $list)
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Plan</label>
                <input type="text" placeholder="Enter Plan" class="form-control" value="{{$list->name}}" readonly>
                <input type="hidden" name="plan[]" value="{{$list->id}}" />
            </div>

            @php $commissionDisplayed = false; @endphp

            @if(isset($detailsGet['plan']))
            @foreach($detailsGet['plan'] as $detail)
            @if(isset($detail['plan_name']) && $detail['plan_name'] == $list->id)
            <div class="col-md-6">
                <label for="">Commission</label>
                <input type="text" name="commission[]" placeholder="Enter Commission" value="{{$detail['commission'] ?? ''}}" class="form-control">
            </div>
            @php $commissionDisplayed = true; @endphp
            @endif
            @endforeach
            @endif

            @if(!$commissionDisplayed)
            <div class="col-md-6">
                <label for="">Commission</label>
                <input type="text" name="commission[]" placeholder="Enter Commission" value="" class="form-control">
            </div>
            @endif
        </div>
        @endforeach
        @endif

        <br>
        <div class="row">
            <div class="col-md-3">
                <label for="">Image</label>
                <input type="file" name="image" class="form-control dark_logo">
                <img class="image1" src="{{$detailsGet['image']}}" width="100px" style="margin-top: 10px;">
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary ">Update Change</button>
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
    $('#agentForm').on('submit', function() {
        $('button[type="submit"]').attr('disabled', 'disabled');
        $('#loadingSpinner').show();
    });
    $(document).ready(function() {
        $('#numericInput').on('keypress', function(event) {
            var charCode = (event.which) ? event.which : event.keyCode;
            // Allow only numbers (48-57) and backspace (8)
            if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 8) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection