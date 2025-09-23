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
            <div class="row">
                <div class="form-group col-md-1">
                    @if(isset($editDetails['image']))
                    <img src="{{$editDetails['image']}}" width="100" />
                    @endif
                </div>
                <div class="form-group col-md-3">
                    <label><strong> Item Name : </strong> @if(isset($editDetails['iteam_name'])) {{$editDetails['iteam_name']}} @endif</label>
                </div>
                <div class="form-group col-md-5">
                    <label> <strong> Stock :</strong> @if(isset($editDetails['iteam_name'])) {{$editDetails['iteam_name']}} @endif</label> &nbsp;&nbsp;&nbsp;
                    <label><strong> Default Disc : </strong>@if(isset($editDetails['default_disc'])) {{$editDetails['default_disc']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label> <strong>Location :</strong> @if(isset($editDetails['loaction'])) {{$editDetails['loaction']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label> <strong>HSN Code : </strong>@if(isset($editDetails['hsn_code'])) {{$editDetails['hsn_code']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label> <strong>Min. Qty. :</strong> @if(isset($editDetails['minimum'])) {{$editDetails['minimum']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label><strong> Max. Qty. : </strong>@if(isset($editDetails['maximum'])) {{$editDetails['maximum']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label> <strong>GST. :</strong> @if(isset($editDetails['gst'])) {{$editDetails['gst']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label><strong> CESS. :</strong> @if(isset($editDetails['cess'])) {{$editDetails['cess']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label><strong> GTIN. : </strong>@if(isset($editDetails['gtin'])) {{$editDetails['gtin']}} @endif</label>&nbsp;&nbsp; &nbsp;
                    <label><strong> Item Category. : </strong>@if(isset($editDetails['item_category_id'])) {{$editDetails['item_category_id']}} @endif</label>&nbsp;&nbsp;&nbsp;

                </div>
                <div class="form-group col-md-2">
                    <button class="btn btn-primary" id="open" data-id="{{$editDetails['id']}}" data-target=".bd-example-modal-lg">Edit</button>
                </div>
                <div class="modal fade bd-example-modal-lg" tabindex="-1" id="exampleModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-3">
                            <form action="{{route('item.update')}}" method="POST">
                                @csrf
                                <h4>Edit Item</h4>
                                <div class="row">
                                    <input type="hidden" name="id" value="{{$editDetails['id']}}" />
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Location</label>
                                        <input type="text" class="form-control" value="{{$editDetails['loaction']}}" name="location" id="inputEmail4" placeholder="Enter Location">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Default Disc.(%)</label>
                                        <input type="text" class="form-control" value="{{$editDetails['default_disc']}}" name="default_disc" id="inputEmail4" placeholder="Enter Default Disc.(%)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Min. Stock</label>
                                        <input type="text" class="form-control" name="minimum" value="{{$editDetails['minimum']}}" id="inputEmail4" placeholder="Enter Min. Stock">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Max. Stock</label>
                                        <input type="text" class="form-control" name="maximum" value="{{$editDetails['maximum']}}" id="inputEmail4" placeholder="Enter Max. Stock">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">GST(%)</label>
                                        <input type="text" class="form-control" name="gst" value="{{$editDetails['gst']}}" id="inputEmail4" placeholder="Enter GST(%)">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Accept Online Order</label><br>
                                        <label class="toggle-switch">
                                            <input type="checkbox" name="accept_online_order" @if($editDetails['accept_online_order']=='on' ) checked @endif>
                                            <span class="toggle-slider round"></span>
                                        </label>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Item Category</label>
                                        <select name="item_category_id" class="form-control" value="{{$editDetails['item_category_id']}}" placeholder="Enter Item Category">
                                            @if(isset($iteamCatgeory))
                                            @foreach($iteamCatgeory as $list)
                                            @if($list->id == $editDetails['item_category_ids'])
                                            <option value="{{$list->id}}" selected>{{$list->category_name}}</option>
                                            @else
                                            <option value="{{$list->id}}">{{$list->category_name}}</option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">CESS(%)</label>
                                        <input type="text" class="form-control" name="cess" value="{{$editDetails['cess']}}" id="inputEmail4" placeholder="Enter CESS(%)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">HSN Code</label>
                                        <input type="text" class="form-control" name="hsn_code" value="{{$editDetails['hsn_code']}}" id="inputEmail4" placeholder="Enter HSN Code">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Manage tag(s)</label>
                                        <input type="text" class="form-control" name="manage_type" value="{{$editDetails['manage_type']}}" id="inputEmail4" placeholder="Enter Manage tag(s)">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Item alias</label>
                                        <input type="text" class="form-control" name="item_alias" value="{{$editDetails['item_alias']}}" id="inputEmail4" placeholder="Enter Item alias">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Morning Dose</label>
                                        <input type="text" class="form-control" value="{{$editDetails['morning_dose']}}" name="morning_dose" id="inputEmail4" placeholder="Enter Morning Dose">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Afternoon Dose</label>
                                        <input type="text" class="form-control" name="afternoon_dose" value="{{$editDetails['afternoon_dose']}}" id="inputEmail4" placeholder="Enter Afternoon Dose">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputPassword4">Evening Dose</label>
                                        <input type="text" class="form-control" name="evening_dose" value="{{$editDetails['evening_dose']}}" id="inputEmail4" placeholder="Enter Evening Dose">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="inputEmail4">Nigte Dose</label>
                                        <input type="text" class="form-control" name="nigte_dose" value="{{$editDetails['nigte_dose']}}" id="inputEmail4" placeholder="Enter Nigte Dose">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<a href="{{route('batch.create',$editDetails['id'])}}"><button class="btn btn-primary mb-3">Add Batch</button></a>
<div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Exp. Date</th>
                            <th>MRP</th>
                            <th>Disc</th>
                            <th>LP</th>
                            <th>Margin</th>
                            <th>PTR</th>
                            <th>Total by MRP</th>
                            <th>Total by PTR</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($detailsList))
                        @foreach($detailsList as $list )
                        <tr>
                            <td>{{$list['batch_number']}}</td>
                            <td>{{$list['qty']}}</td>
                            <td>{{$list['expiry_date']}}</td>
                            <td>{{$list['mrp']}}</td>
                            <td>{{$list['discount']}}</td>
                            <td>{{$list['lp']}}</td>
                            <td>{{$list['margin']}}</td>
                            <td>{{$list['margin']}}</td>
                            <td>{{$list['total_mrp']}}</td>
                            <td>{{$list['total_ptr']}}</td>
                            <td>
                                <a href="{{route('batch.delete',$list['id'])}}"><button class="btn btn-primary">Delete</button></a>
                                <button class="btn btn-primary" data-id="{{$list['discount']}}" data-total="{{$list['id']}}" data-qty="{{$list['qty']}}" id="editModel" data-toggle="modal" data-target="#editModel">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modelData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Batch</h5>
            </div>
            <form action="{{route('update.batch')}}" method="POST">
                @csrf
            <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Quantity</label>
                            <input type="hidden" name="id" value="" id="dataId" />
                            <input type="text" class="form-control" name="quantity" value="" id="qty" placeholder="Enter Quantity">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Discount</label>
                            <input type="text" class="form-control"  name="discount" value="" id="disocuntData" placeholder="Enter Discount">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="data-logo" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '#open', function() {
            var data = $("#open").data('id');
            $('#exampleModal').modal('show');
        });

    });

    $(document).ready(function() {
        $(document).on('click', '#editModel', function() {

            var ids = $(this).data('total');
            
            var discount = $(this).data('id');
            var qty = $(this).data('qty');
            var diacount = $("#disocuntData").val(discount);
            var qty = $("#qty").val(qty);

            $("#dataId").val(ids);

            $('#modelData').modal('show');
        });
    });

    $(document).ready(function() {
        $(document).on('click', '#data-logo', function() {
            $('#modelData').modal('hide');
        });

    });

    $(document).ready(function() {
        $(document).on('click', '#test', function() {
            $('#exampleModal').modal('hide');
        });

    });
</script>
@endsection