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
    <h5>Edit Subscription Plan</h5>
    <br>
    <form action="{{ route('subscription.update') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                @if(isset($editDetails['id']))
                <input type="hidden" name="id" value="{{$editDetails['id']}}" />
                @endif
                <label for="">Name</label>
                <input type="text" name="plan_name" value="{{$editDetails['name']}}" class="form-control"
                    placeholder="Please Enter Name">
            </div>
            <div class="col-md-6">
                <label for="">Max Products</label>
                <input type="text" name="max_product" value="{{$editDetails['max_product']}}" class="form-control"
                    placeholder="Please Enter Max Products">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Description</label>
                <input type="textarea" name="description" value="{{$editDetails['description']}}" class="form-control">
            </div>
        </div>
        <br>
        <h5>Pricing Details</h5>
        <hr>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Annual Price</label>
                <input type="text" name="annual_price" class="form-control" value="{{$editDetails['annual_price']}}" placeholder="Please Enter Annual Price">
            </div>
            <div class="col-md-6">
                <label for="">Re Newal</label>
                <input type="text" name="re_newal" class="form-control" value="{{$editDetails['re_newal']}}" placeholder="Please Enter Re Newal">
            </div>
        </div>
        <br>
        <h5>Enabled Modules</h5>
        <hr>
        <?php
$enableModule = explode(',', $editDetails['enable_modules']);
?>
        <div class="row">
            <div class="col-md-6">
                <select name="enable_module[]" id="modules" class="form-control" multiple>
                    <option value="1 User"<?= in_array('1 User', $enableModule) ? 'selected' : '' ?>>1 User</option>
                    <option value="1 GSTIN"<?= in_array('1 GSTIN', $enableModule) ? 'selected' : '' ?>>1 GSTIN</option>
                    <option value="Unlimited Invoices"<?= in_array('Unlimited Invoices', $enableModule) ? 'selected' : '' ?>>Unlimited Invoices</option>
                    <option value="Dashboard"<?= in_array('Dashboard', $enableModule) ? 'selected' : '' ?>>Dashboard</option>
                    <option value="Item Master"<?= in_array('Item Master', $enableModule) ? 'selected' : '' ?>>Item Master</option>
                    <option value="Account Master"<?= in_array('Account Master', $enableModule) ? 'selected' : '' ?>>Account Master</option>
                    <option value="Inventory"<?= in_array('Inventory', $enableModule) ? 'selected' : '' ?>>Inventory</option>
                    <option value="Order List"<?= in_array('Order List', $enableModule) ? 'selected' : '' ?>>Order List</option>
                    <option value="Expiry List"<?= in_array('Expiry List', $enableModule) ? 'selected' : '' ?>>Expiry List</option>
                    <option value="Sales Bills"<?= in_array('Sales Bills', $enableModule) ? 'selected' : '' ?>>Sales Bills</option>
                    <option value="Sales Returns"<?= in_array('Sales Returns', $enableModule) ? 'selected' : '' ?>>Sales Returns</option>
                    <option value="Purchase Bills"<?= in_array('Purchase Bills', $enableModule) ? 'selected' : '' ?>>Purchase Bills</option>
                    <option value="Purchase Returns"<?= in_array('Purchase Returns', $enableModule) ? 'selected' : '' ?>>Purchase Returns</option>
                    <option value="Sales Register"<?= in_array('Sales Register', $enableModule) ? 'selected' : '' ?>>Sales Register</option>
                    <option value="Purchase Register"<?= in_array('Purchase Register', $enableModule) ? 'selected' : '' ?>>Purchase Register</option>
                    <option value="GST Report"<?= in_array('GST Report', $enableModule) ? 'selected' : '' ?>>GST Report</option>
                    <option value="Chemist App"<?= in_array('Chemist App', $enableModule) ? 'selected' : '' ?>>Chemist App</option>
                    <option value="Patient App"<?= in_array('Patient App', $enableModule) ? 'selected' : '' ?>>Patient App</option>
                    <option value="Drug/Group Master"<?= in_array('Drug/Group Master', $enableModule) ? 'selected' : '' ?>>Drug/Group Master</option>
                    <option value="3 Users"<?= in_array('3 Users', $enableModule) ? 'selected' : '' ?>>3 Users</option>
                    <option value="1 GSTIN"<?= in_array('1 GSTIN', $enableModule) ? 'selected' : '' ?>>1 GSTIN</option>
                    <option value="Basic (all features)"<?= in_array('Basic (all features)', $enableModule) ? 'selected' : '' ?>>Basic (all features)</option>
                    <option value="all reports"<?= in_array('all reports', $enableModule) ? 'selected' : '' ?>>all reports</option>
                    <option value="stock adjustment"<?= in_array('stock adjustment', $enableModule) ? 'selected' : '' ?>>stock adjustment</option>
                    <option value="staff management"<?= in_array('taff management', $enableModule) ? 'selected' : '' ?>>staff management</option>
                    <option value="purchase CSV upload"<?= in_array('purchase CSV upload', $enableModule) ? 'selected' : '' ?>>purchase CSV upload</option>
                    <option value="Refill Reminders"<?= in_array('Refill Reminders', $enableModule) ? 'selected' : '' ?>>Refill Reminders</option>
                    <option value="barcode billing"<?= in_array('barcode billing', $enableModule) ? 'selected' : '' ?>>barcode billing</option>
                    <option value="Unlimited Users"<?= in_array('Unlimited Users', $enableModule) ? 'selected' : '' ?>>Unlimited Users</option>
                    <option value="Basic and Advanced Features"<?= in_array('Basic and Advanced Features', $enableModule) ? 'selected' : '' ?>>Basic and Advanced Features</option>
                    <option value="Loyalty Points"<?= in_array('Loyalty Points', $enableModule) ? 'selected' : '' ?>>Loyalty Points</option>
                    <option value="API integration"<?= in_array('API integration', $enableModule) ? 'selected' : '' ?>>API integration</option>
                    <option value="bulk Entry"<?= in_array('bulk Entry', $enableModule) ? 'selected' : '' ?>>bulk Entry</option>
                    <option value="Cash Entry"<?= in_array('Cash Entry', $enableModule) ? 'selected' : '' ?>>Cash Entry</option>
                    <option value="CRM"<?= in_array('CRM', $enableModule) ? 'selected' : '' ?>>CRM</option>
                    <option value="White Label"<?= in_array('White Label', $enableModule) ? 'selected' : '' ?>>White Label</option>
                    <option value="Home Delivery"<?= in_array('Home Delivery', $enableModule) ? 'selected' : '' ?>>Home Delivery</option>
                </select>
            </div>
             <div class="col-md-6">
                <input type="text" name="percentage" class="form-control" value="{{ $editDetails['percentage'] }}" placeholder="Please Enter Percentage">
            </div>
        </div>


        <br>
        <h5>Features</h5>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div id="req_input">
                    @if(isset($editDetails['featuer']))
                    @foreach($editDetails['featuer'] as $feature_list)
                    <div class="d-flex">
                        <input type="text" name="features[]" id="values" value="{{ $feature_list['features_name'] }}"
                            class="form-control">
                        <a class="btn btn-danger ml-2" href="{{ route('plan_feature_delete',$feature_list['id']) }}"><i
                                class="fa fa-minus"></i></a>
                    </div>
                    <br>
                    @endforeach
                    @endif
                    <div class="d-flex">
                        <input type="text" name="features[]" class="form-control">
                        <button type="button" name="add" id="addmore" class="btn btn-primary ml-2"><i
                                class="fa fa-plus"></i></button>
                    </div>
                </div>
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
$(document).ready(function() {
    $("#addmore").click(function() {
        $("#req_input").append(
            '<div class="required_inp d-flex mt-1"><input type="text" name="features[]" id="values" class="form-control"><button class="btn btn-danger inputRemove ml-2"><i class="fa fa-minus"></i></button></div>'
            );
    });
    $('body').on('click', '.inputRemove', function() {
        $(this).parent('div.required_inp').remove()
    });

    $('#modules').select2();
});
</script>
@endsection