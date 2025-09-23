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
    <h5>Edit New Pharma Shop</h5>
    <br>

    <form action="{{route('pharma.update')}}" method="POST" enctype="multipart/form-data" id="agentForm">
        @csrf
      

        <div class="row">
            <div class="col-md-6">
                <label for="">Owner Name</label>
                @if(isset($editDetails['id']))
                <input type="hidden" name="id" value="{{$editDetails['id']}}" />
                @endif

                <input type="text" name="pharma_name" value="{{$editDetails['pharma_name']}}" class="form-control" placeholder="Enter Owner Name">
            </div>
            <div class="col-md-6">
                <label for="">Shop Name</label>
                <input type="text" name="pharma_short_name" value="{{$editDetails['pharma_short_name']}}" class="form-control" placeholder="Enter Shop Name">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Pharma Email</label>
                <input type="text" name="pharma_email" class="form-control" value="{{$editDetails['pharma_email']}}" placeholder="Enter Pharma Email">
            </div>
            <div class="col-md-6">
                <label for="">Pharma Phone</label>
                <input type="text" name="pharma_phone" class="form-control" value="{{$editDetails['pharma_phone_number']}}" placeholder="Enter Pharma Phone" maxlength="10" id="numericInput">
            </div>
        </div>
        <div class="row mt-3">


            <div class="col-md-6">
                <label for="">City</label>
                <input type="text" name="city" placeholder="Enter City" class="form-control" value="{{ $editDetails['city'] }}">
            </div>
            <div class="col-md-6">
                <label for="">Status</label>
                <select name="status" id="" class="form-control">
                    <option value="">Select Status</option>
                    <option value="0" {{ $editDetails['pharma_status'] == '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $editDetails['pharma_status'] == '1' ? 'selected' : '' }}>Active</option>
                    <option value="2" {{ $editDetails['pharma_status'] == '2' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Pharma Address</label>
                <input type="text" name="pharma_address" class="form-control" value="{{$editDetails['pharma_address']}}" placeholder="Enter Pharma Address">
            </div>
            <div class="col-md-6">
                <label for="">Agent</label>
                <select name="agent_id" class="form-control agent_id">
                    <option value="">Select Agent</option>
                    @if(isset($agent))
                    @foreach($agent as $list)
                    <option value="{{$list->id}}" {{ $editDetails['agent_id'] == $list->id ? 'selected' : '' }}>{{$list->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>
        <br>
        <div id="dataAgent">
            <?php
            $agentData  = \App\Models\ShopPlan::where('shop_id', $editDetails['shop_id'])->with('getagent')->get();

            ?>
            @if(isset($agentData))
            @foreach($agentData as $list)
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="">Plan</label>
                    <input type="text" placeholder="Enter Plan" class="form-control" value="{{$list->getagent->name}}" readonly>
                    <input type="hidden" name="plan[]" value="{{$list->plan}}" />
                </div>
                <div class="col-md-6">
                    <label for="">commission</label>
                    <input type="text" name="commission[]" placeholder="Enter Commission" value="{{$list->commission}}" class="form-control">
                </div>
            </div>
            @endforeach
            @endif

        </div>
       <div class="row mt-3">
            <div class="col-md-2">
                <label for="">User Referral Balance</label>
                <input type="text" id="referral_balance" name="referral_balance" class="form-control" value="{{$editDetails['referral_amount']}}" readonly>
            </div>
            <div class="col-md-1" style="margin-top: 27px;">
                <select id="operation" name="operation" class="form-control">
                    <option value="+">+</option>
                    <option value="-">-</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="">Enter Amount</label>
                <input type="text" id="amount" name="amount" class="form-control" placeholder="Enter Amount">
            </div>
           <div class="col-md-2" style="margin-top: 27px;">
                <label for=""></label>
                <span id="calculate" class="btn btn-primary">Calculate</span>
            </div>
           <div class="col-md-2">
                <label for="">Updated Balance</label>
                <input type="text" name="updated_balance" id="updated_balance" class="form-control" readonly>
            </div>
            <div class="col-md-2">
                <label for="">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="{{$editDetails['remark']}}" >
            </div>
        </div>
  
        <br>
        <h5>Admin Account Details</h5>
        <br>
        <div class="row">
            <div class="col-md-6">
                <label for="">Email</label>
                <input type="text" name="email" placeholder="Enter Email" value="{{$editDetails['email']}}" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="">Password</label>
                <input type="text" name="password" placeholder="Enter Password" class="form-control" value="{{ $editDetails['password'] }}">
            </div>
        </div>
        <br>
        <h5>Pharma Logo</h5>
        <br>
        <div class="row">
            <div class="col-md-3">
                <label for="">Dark Logo</label>
                <input type="file" name="dark_logo" class="form-control dark_logo">
                <img class="image1" src="{{$editDetails['dark_logo']}}" width="100px" style="margin-top: 10px;">
            </div>
            <div class="col-md-3">
                <label for="">Light Logo</label>
                <input type="file" name="light_logo" class="form-control light_logo">
                <img class="image2" src="{{$editDetails['light_logo']}}" width="100px" style="margin-top: 10px;">
            </div>
            <div class="col-md-3">
                <label for="">Small Dark Logo</label>
                <input type="file" name="small_dark_logo" class="form-control small_dark_logo">
                <img class="image3" src="{{$editDetails['small_dark_logo']}}" width="100px" style="margin-top: 10px;">
            </div>
            <div class="col-md-3">
                <label for="">Small Light Logo</label>
                <input type="file" name="small_light_logo" class="form-control small_light_logo">
                <img class="image4" src="{{$editDetails['small_light_logo']}}" width="100px" style="margin-top: 10px;">
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary ">Update</button>
        </div>
    </form>
</div>
@endsection

@section('js')
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
    $('.dark_logo').change(function() {
        var curElement = $('.image1');
        console.log(curElement);
        var reader = new FileReader();

        reader.onload = function(e) {
            curElement.attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });

    $('.light_logo').change(function() {
        var curElement = $('.image2');
        console.log(curElement);
        var reader = new FileReader();

        reader.onload = function(e) {
            curElement.attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });

    $('.small_dark_logo').change(function() {
        var curElement = $('.image3');
        console.log(curElement);
        var reader = new FileReader();

        reader.onload = function(e) {
            curElement.attr('src', e.target.result);
        };

        reader.readAsDataURL(this.files[0]);
    });

    $('.small_light_logo').change(function() {
        var curElement = $('.image4');
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
 <script>
        $(document).ready(function() {
            $('#calculate').click(function() {
                let currentBalance = parseFloat($('#referral_balance').val()) || 0;
                let enteredAmount = parseFloat($('#amount').val()) || 0;
                let operation = $('#operation').val();
                let newBalance = operation === '+' ? currentBalance + enteredAmount : currentBalance - enteredAmount;
                $('#updated_balance').val(newBalance.toFixed(2));
            });
        });
    </script>

@endsection