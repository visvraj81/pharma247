@extends('layouts.main')
@section('main')
<style>
    .error {
        color: red;
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
    <h5>Add New Lead</h5>
    <br>
    <form action="{{route('lead.store')}}" method="POST" id="leadForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="">Pharma Name</label>
                <?php
                $shopData  =  App\Models\PharmaShop::where('pharma_status', '1')->get();
                ?>
                @if(isset($shopData))
                <select name="pharma_name" class="form-control">
                    @foreach($shopData as $list)
                    <option value="{{$list->id}}">{{$list->pharma_name}}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <!--<div class="col-md-6">-->
            <!--    <label for="">Submitted By</label>-->
            <!--    <input type="text" name="submitted_by" class="form-control" placeholder="Please Enter Submitted By">-->
            <!--</div>-->
            <div class="col-md-6">
                <label for=""> Reason</label>
                <input type="text" name="reason" class="form-control" placeholder="Please Enter Reason">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for=""> Subscription Plan</label>
                <?php
                $subcrptionData  =  App\Models\SubscriptionPlan::get();
                ?>
                @if(isset($subcrptionData))
                <select name="subscription_plan" class="form-control">
                    @foreach($subcrptionData as $list)
                    <option value="{{$list->id}}">{{$list->name}}</option>
                    @endforeach
                </select>
                @endif
            </div>
            <div class="col-md-6">
                <label for="">Plan Type</label>
                <input type="text" name="plan_type" class="form-control" placeholder="Please Enter Plan Type">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Payment Method</label>
                <input type="text" name="payment_method" class="form-control" placeholder="Please Enter Payment Method">
            </div>
            <!-- <div class="col-md-6">-->
            <!--    <label for="">	Submitted On</label>-->
            <!--    <input type="text" name="submitted_on" class="form-control" placeholder="Please Enter Submitted On">-->
            <!--</div>-->
            <div class="col-md-6">
                <label for="">Status</label>
                <select name="status" class="form-control">
                    <option value="2">Rejected</option>
                    <option value="0"> In Progress</option>
                    <option value="1">Complete</option>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>
    $(document).ready(function() {
        $('#leadForm').validate({
            rules: {
                pharma_name: {
                    required: true,
                    // minlength: 3
                },
                // submitted_by: {
                //     required: true,
                //     minlength: 3
                // },
                subscription_plan: {
                    // required: true,
                },
                plan_type: {
                    required: true,
                    minlength: 3
                },
                payment_method: {
                    required: true,
                    // minlength: 3
                },
                // submitted_on: {
                //     required: true,
                //     date: true
                // },
                status: {
                    required: true,
                },
                reason: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                pharma_name: {
                    required: "Please enter the Pharma Name",
                    minlength: "Pharma Name must be at least 3 characters long"
                },
                // submitted_by: {
                //     required: "Please enter who submitted the lead",
                //     minlength: "Submitted By must be at least 3 characters long"
                // },
                subscription_plan: {
                    required: "Please enter the Subscription Plan",
                    minlength: "Subscription Plan must be at least 3 characters long"
                },
                plan_type: {
                    required: "Please enter the Plan Type",
                    minlength: "Plan Type must be at least 3 characters long"
                },
                payment_method: {
                    required: "Please enter the Payment Method",
                    minlength: "Payment Method must be at least 3 characters long"
                },
                submitted_on: {
                    required: "Please enter the date submitted",
                    date: "Please enter a valid date"
                },
                status: {
                    required: "Please enter the status",
                    minlength: "Status must be at least 3 characters long"
                },
                reason: {
                    required: "Please enter the reason",
                    minlength: "Reason must be at least 3 characters long"
                }
            }
        });
    });
</script>
@endsection