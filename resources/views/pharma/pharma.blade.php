@extends('pharma.layout.main')
@section('main')
@if(Session::has('error'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
@endif
@if(Session::has('success'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
@endif

<!-- <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <a href="{{route('iteam.add')}}" class="btn btn-primary">Add Item</a>
            <br>
            <br>
            <form action="" method="get">
                @csrf
                <div class="row">
                    <div class="form-group col-md-7">
                        <div class="form-group">
                            <select name="search" class="js-example-basic-single w-100">
                                <option value="">Select Item</option>
                                @if(isset($iteamAdd))
                                @foreach($iteamAdd as $list)
                                <option value="{{$list->id}}">{{$list->iteam_name}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </form>
            @if(isset($iteamAddList))

            <a href="{{route('iteam.edit',$iteamAddList->id)}}">
                <div class="col-lg-9 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Item Name </th>
                                            <th>Min.</th>
                                            <th>Max.</th>
                                            <th>Stock</th>
                                            <th>Loc.</th>
                                            <th>Disc.</th>
                                            <th>Total PTR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@if(isset($iteamAddList->iteam_name)) {{$iteamAddList->iteam_name}} @endif</td>
                                            <td>@if(isset($iteamAddList->minimum)) {{$iteamAddList->minimum}} @endif</td>
                                            <td>@if(isset($iteamAddList->maximum)) {{$iteamAddList->maximum}} @endif</td>
                                            <td>@if(isset($iteamAddList->stock)) {{$iteamAddList->stock}} @else 0 @endif</td>
                                            <td>@if(isset($iteamAddList->loaction)) {{$iteamAddList->loaction}} @endif</td>
                                            <td> @if(isset($iteamAddList->default_disc)) {{$iteamAddList->default_disc}} @endif</td>
                                            <td>@if(isset($iteamAddList->total_ptr)) {{$iteamAddList->total_ptr}} @else 0.0 @endif</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endif
        </div>
    </div>
</div> -->

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
                <h4 class="card-title">Iteam Master</h4>
                <form action="{{route('iteam.create')}}" method="POST" enctype="multipart/form-data" class="forms-sample">
                @csrf
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Iteam Name</label>
                            <input type="text" class="form-control" name="iteam_name" placeholder="Enter Iteam Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">unit</label>
                            <input type="text" class="form-control" name="unit" placeholder="Enter unit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Pack</label>
                            <input type="text" class="form-control" name="pack" placeholder="Enter Pack">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Company</label>
                            <select name="pahrma"  class="form-control">
                                  @if(isset($pahrma))
                                  @foreach($pahrma as $list)
                                  <option value="{{$list->id}}">{{$list->pharma_name}}</option>
                                  @endforeach
                                  @endif
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Suplier</label>
                            <select name="distrubuter"  class="form-control">
                            @if(isset($distributer))
                            @foreach($distributer as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                            @endforeach
                            @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Drug Group</label>
                            <input type="text" class="form-control" name="drug_group" placeholder="Enter Drug Group">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Item Category</label>
                            <select class="form-control" name="item_category_id">
                                @if(isset($iteamCatgeory))
                                    @foreach($iteamCatgeory as $category)
                                    <option value="{{$category->id}}">{{$category->category_name}}</option>
                                    @endforeach
                                    @endif
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Item Type</label>
                            <select class="form-control" name="item_type">
                               <option value="Hospital Bed">Hospital Bed</option>
                               <option value="Syringes">Syringes</option>
                               <option value="Wheelchairs">Wheelchairs</option>
                               <option value="Medical device">Medical device</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">GST</label>
                            <input type="text" class="form-control" name="gst" placeholder="GST">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Loaction</label>
                            <input type="text" class="form-control" name="location" id="inputEmail4" placeholder="Enter Loaction">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Schedule</label><br>
                            <label class="toggle-switch">
                                <input type="checkbox" name="schedule" checked="">
                                <span class="toggle-slider round"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Add Text Not Applicable</label><br>
                            <label class="toggle-switch">
                                <input type="checkbox" name="tax_not_applied" checked="">
                                <span class="toggle-slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Tax</label>
                            <input type="text" class="form-control" name="tax" placeholder="Tax">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Barcode</label>
                            <input type="text" class="form-control" name="barcode" id="inputEmail4" placeholder="Enter Barcode">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Mini mum</label>
                            <input type="text" class="form-control" name="minimum" id="inputEmail4" placeholder="Enter Mini Mum">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Maximum</label>
                            <input type="text" class="form-control" name="maximum" id="inputEmail4" placeholder="Enter Maxi Mum">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Discount</label>
                            <input type="text" class="form-control" name="discount" id="inputEmail4" placeholder="Enter Discount">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Margin</label>
                            <input type="text" class="form-control" name="margin" id="inputEmail4" placeholder="Enter Margin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">HSN Code</label>
                            <input type="text" class="form-control" name="discount" id="inputEmail4" placeholder="Enter Discount">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Message</label>
                            <input type="text" class="form-control" name="margin" id="inputEmail4" placeholder="Enter Margin">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Front Photo</label>
                            <input type="file" class="form-control" name="front_photo" id="inputEmail4" placeholder="Enter Front Photo">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Backside</label>
                            <input type="file" class="form-control" name="backside" id="inputEmail4" placeholder="Enter Backside">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">MRP Photo</label>
                            <input type="file" class="form-control" name="mrp_photo" id="inputEmail4" placeholder="Enter MRP Photo">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                </form>
            </div>
        </div>
</div>
@endsection