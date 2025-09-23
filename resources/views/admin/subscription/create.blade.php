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
    <h5>Create Subscription Plan</h5>
    <br>
    <form action="{{ route('subscription.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label for="">Name</label>
                <input type="text" name="plan_name" class="form-control" placeholder="Please Enter Name">
            </div>
            <div class="col-md-6">
                <label for="">Max Products</label>
                <input type="text" name="max_product" class="form-control" placeholder="Please Enter Max Products">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <label for="">Description</label>
                <input type="textarea" name="description" class="form-control">
            </div>
        </div>
       
        <br>
        <h5>Pricing Details</h5>
        <hr>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="">Annual Price</label>
                <input type="text" name="annual_price" class="form-control" placeholder="Please Enter Annual Price">
            </div>
            <div class="col-md-6">
                <label for="">Re Newal</label>
                <input type="text" name="re_newal" class="form-control" placeholder="Please Enter Re Newal">
            </div>
        </div>
        <br>
        <h5>Enabled Modules</h5>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <select name="enable_module[]" id="modules" class="form-control" multiple>
                    <option value="1 User">1 User</option>
                    <option value="1 GSTIN">1 GSTIN</option>
                    <option value="Unlimited Invoices">Unlimited Invoices</option>
                    <option value="Dashboard">Dashboard</option>
                    <option value="Item Master">Item Master</option>
                    <option value="Account Master">Account Master</option>
                    <option value="Inventory">Inventory</option>
                    <option value="Order List">Order List</option>
                    <option value="Expiry List">Expiry List</option>
                    <option value="Sales Bills">Sales Bills</option>
                    <option value="Sales Returns">Sales Returns</option>
                    <option value="Purchase Bills">Purchase Bills</option>
                    <option value="Purchase Returns">Purchase Returns</option>
                    <option value="Sales Register">Sales Register</option>
                    <option value="Purchase Register">Purchase Register</option>
                    <option value="GST Report">GST Report</option>
                    <option value="Chemist App">Chemist App</option>
                    <option value="Patient App">Patient App</option>
                    <option value="Drug/Group Master">Drug/Group Master</option>
                    <option value="3 Users">3 Users</option>
                    <option value="1 GSTIN">1 GSTIN</option>
                    <option value="Basic (all features)">Basic (all features)</option>
                    <option value="all reports">all reports</option>
                    <option value="stock adjustment">stock adjustment</option>
                    <option value="staff management">staff management</option>
                    <option value="purchase CSV upload">purchase CSV upload</option>
                    <option value="Refill Reminders">Refill Reminders</option>
                    <option value="barcode billing">barcode billing</option>
                    <option value="Unlimited Users">Unlimited Users</option>
                    <option value="1 GSTIN">1 GSTIN</option>
                    <option value="Basic and Advanced Features">Basic and Advanced Features</option>
                    <option value="Loyalty Points">Loyalty Points</option>
                    <option value="API integration">API integration</option>
                    <option value="bulk Entry">bulk Entry</option>
                    <option value="Cash Entry">Cash Entry</option>
                    <option value="CRM">CRM</option>
                    <option value="White Label">White Label</option>
                    <option value="Home Delivery">Home Delivery</option>
                    <option value="Patient App">Patient App</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="percentage" class="form-control" placeholder="Please Enter Percentage">
            </div>
        </div>
        <br>
        <h5>Features</h5>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div id="req_input">
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