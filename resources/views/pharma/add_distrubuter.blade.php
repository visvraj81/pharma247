@extends('pharma.layout.main')
@section('main')
@if(Session::has('error'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
@endif
@if(Session::has('success'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
@endif

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <form action="{{route('distributor.store')}}" method="POST" enctype="multipart/form-data" class="forms-sample">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">GST Number</label>
                        <input type="text" class="form-control" name="gst_number" placeholder="Enter GST Number">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Distributor Name</label>
                        <input type="text" class="form-control" name="distributor_name" placeholder="Enter Distributor Name">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter GST Number">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Mobile No</label>
                        <input type="text" class="form-control" name="mobile_no" placeholder="Enter unit">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Enter Phone">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Whatsapp</label>
                        <input type="text" class="form-control" name="whatsapp" placeholder="Enter Whatsapp">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Address</label>
                        <input type="text" class="form-control" name="address" placeholder="Enter Address">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Area</label>
                        <input type="text" class="form-control" name="area" placeholder="Enter Area">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Pincode</label>
                        <input type="text" class="form-control" name="pincode" placeholder="Enter Pincode">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" placeholder="Enter Bank Name">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Account No</label>
                        <input type="text" class="form-control" name="account_no" placeholder="Enter Account No">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Ifsc Code</label>
                        <input type="text" class="form-control" name="ifsc_code" placeholder="Enter Ifsc Code">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Food Licence No</label>
                        <input type="text" class="form-control" name="food_licence_no" placeholder="Enter Food Licence No">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Distributor Durg Distributor</label>
                        <input type="text" class="form-control" name="distributor_durg_distributor" placeholder="Enter Distributor Durg Distributor">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Payment Due Days</label>
                        <input type="text" class="form-control" name="payment_due_days" placeholder="Enter Payment Due Days">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection