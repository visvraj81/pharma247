@extends('layouts.main')
@section('main')
<div class="card p-3">
    <h5>Add New Pharma Shop</h5>
    <br>
    <form action="{{route('pharma.subscription.update')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="">Subscription Plan</label>
                <input type="hidden" name="id" value="{{$dataId}}" />
                <select name="plan" id="" class="form-control">
                    <option value="">Select Plan</option>
                    @if(isset($subscrptionPlan))
                    @foreach($subscrptionPlan as $list)
                    <option value="{{$list->id}}" {{$editDetails['subscription_plan'] == $list->id ? 'selected' : ''}}>{{$list->name}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-6">
                <label for="">Plan Type</label>
                <select name="plan_type" id="plan_type" class="form-control">
                    <option value="">Select Plan</option>
                    <option value="Yearly" {{ $editDetails['plan_type'] == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="Monthly" {{ $editDetails['plan_type'] == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Payment Mode</label>
                <select name="payment_mode" id="" class="form-control">
                    <option value="">Select Payment Mode</option>
                    <option value="Cash" {{ $editDetails['payment_mode'] == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Stripe" {{ $editDetails['payment_mode'] == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="">Amount</label>
                <input type="text" name="amount" class="form-control" placeholder="Amount" value="{{$editDetails['amount']}}">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Payment Date</label>
                <input type="date" name="payment_date" class="form-control" id="payment_date" value="{{$editDetails['payment_date']}}">
            </div>
            <div class="col-md-6">
                <label for="">License Will Expires On</label>
                <input type="date" name="expire_date" class="form-control" readonly id="expire_Date" value="{{$editDetails['license_will_expire_on']}}">
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
    $(document).on('change', '#payment_date', function() {
        var planType = $("#plan_type").val();
        var payment_date = $("#payment_date").val();
        if (planType == 'Yearly') {
            var currentDate = new Date(payment_date);

            // Adding one year to the current date
            currentDate.setFullYear(currentDate.getFullYear() + 1);

            // Formatting the next year's date
            var nextYearDate = currentDate.toISOString().split('T')[0];

            $("#expire_Date").val(nextYearDate);
        } else {
            var currentDate = new Date(payment_date);

            // Adding one year to the current date
            currentDate.setMonth(currentDate.getMonth() + 1);

            // Formatting the next year's date
            var nextYearDate = currentDate.toISOString().split('T')[0];

            $("#expire_Date").val(nextYearDate);
        }
    });

    $(document).on('change', '#plan_type', function() {
        var planType = $("#plan_type").val();
        var payment_date = $("#payment_date").val();
        if (planType == 'Yearly') {
            var currentDate = new Date(payment_date);

            // Adding one year to the current date
            currentDate.setFullYear(currentDate.getFullYear() + 1);

            // Formatting the next year's date
            var nextYearDate = currentDate.toISOString().split('T')[0];

            $("#expire_Date").val(nextYearDate);
        } else {
            var currentDate = new Date(payment_date);

            // Adding one year to the current date
            currentDate.setMonth(currentDate.getMonth() + 1);

            // Formatting the next year's date
            var nextYearDate = currentDate.toISOString().split('T')[0];

            $("#expire_Date").val(nextYearDate);
        }
    });
</script>

@endsection