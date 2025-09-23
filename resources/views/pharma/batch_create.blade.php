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
            <form action="{{route('batch.add')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Batch Number</label>
                        <input type="text" class="form-control" name="batch_number" id="inputEmail4" placeholder="Enter Batch Number">
                    </div>
                    <input type="hidden" name="id" value="{{$id}}" />
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">QTY</label>
                        <input type="text" class="form-control" name="qty" id="inputEmail4" placeholder="Enter QTY">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Exp. Date	</label>
                        <input type="date" class="form-control" name="expiry_date" id="inputEmail4" placeholder="Enter Exp. Date">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">MRP	</label>
                        <input type="text" class="form-control" name="mrp" id="inputEmail4" placeholder="Enter MRP	">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">PTR</label>
                        <input type="text" class="form-control" name="ptr" id="inputEmail4" placeholder="Enter PTR">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Disc.</label>
                        <input type="text" class="form-control" name="discount" id="inputEmail4" placeholder="Enter Disc.">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">LP</label>
                        <input type="text" class="form-control" name="lp" id="inputEmail4" placeholder="Enter LP">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Margin%</label>
                        <input type="text" class="form-control" name="margin" id="inputEmail4" placeholder="Enter Margin">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" >Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection