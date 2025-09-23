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
            <form  action="{{route('purches.store')}}" method="POST" enctype="multipart/form-data" class="forms-sample">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Distributor</label>
                        <select  class="form-control" name="distributor_id">
                            @if(isset($distrubuter))
                                @foreach($distrubuter as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Bill No</label>
                        <input type="text" class="form-control" name="bill_no" placeholder="Enter Bill No">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="inputEmail4">Bill Date</label>
                        <input type="date" class="form-control" name="bill_date" placeholder="Enter Bill Date">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="inputPassword4">Due Date</label>
                        <input type="date" class="form-control" name="due_date" placeholder="Enter Due Date">
                    </div>
                </div>
                <!-- Add dynamic rows -->
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>HSN Code</th>
                                <th>Unit</th>
                                <th>Batch</th>
                                <th>Exp Date</th>
                                <th>MRP</th>
                                <th>Qty</th>
                                <th>FR Qty</th>
                                <th>PTR</th>
                                <th>D%</th>
                                <th>Disc</th>
                                <th>Base</th>
                                <th>GST</th>
                                <th>Amount</th>
                                <th>LP</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <a href="#" id="addRow" class="btn btn-primary">Add Row</a>
                <!-- Submit Button -->
                <br> <br>
                <strong>PTR Total : 150</strong><br>
                <strong>Total Discount : 150 </strong><br>
                <strong>GST  : 150</strong><br>
                <strong>CESS  : 150</strong><br>
                <strong>TCS  : 150</strong><br>
                <strong>Extra Charges  : 150</strong><br>
                <strong>Adjustment Amount  : 150</strong><br>
                <strong>Round off  : 150</strong><br>
                <strong>Net Amount  : 1500</strong><br>
                <input type="hidden" name="ptr_total" value="150" />
                <input type="hidden" name="ptr_discount" value="150"  />
                <input type="hidden" name="gst" value="150" />
                <input type="hidden" name="cess" value="150" />
                <input type="hidden" name="tcs" value="150" />
                <input type="hidden" name="extra_charge" value="150" />
                <input type="hidden" name="adjustment_amoount"value="150"  />
                <input type="hidden" name="round_off" value="150" />
                <input type="hidden" name="net_amount" value="1500" />
                <br> <br>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
         
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Function to add a new row
        $("#addRow").click(function() {
            var newRow = '<tr>' +
                '<td><input type="text" name="iteam[]" class="form-control itemName"></td>' +
                '<td><input type="text" name="hsn_code[]" class="form-control hsnCode"></td>' +
                '<td><input type="text" name="unit[]" class="form-control unit"></td>' +
                '<td><input type="text" name="batch[]" class="form-control batch"></td>' +
                '<td><input type="text" name="exp_date[]" class="form-control expDate"></td>' +
                '<td><input type="text" name="mrp[]" class="form-control mrp"></td>' +
                '<td><input type="text" name="qty[]" class="form-control qty"></td>' +
                '<td><input type="text" name="fr_qty[]" class="form-control fr qty"></td>' +
                '<td><input type="text" name="ptr[]" class="form-control ptr"></td>' +
                '<td><input type="text" name="d_percent[]" class="form-control d_percent"></td>' +
                '<td><input type="text" name="disc[]" class="form-control disc"></td>' +
                '<td><input type="text" name="base[]" class="form-control base"></td>' +
                '<td><input type="text" name="gst[]" class="form-control gst"></td>' +
                '<td><input type="text" name="amount[]" class="form-control amount"></td>' +
                '<td><input type="text" name="lp[]" class="form-control lp"></td>' +
                '<td><input type="text" name="location[]" class="form-control location"></td>' +
                '<td><a href="#" type="button" class="btn btn-danger deleteRow">Delete</a></td>' +
                '</tr>';
            $('#dataTable tbody').append(newRow);
        });

        // Function to delete a row
        $(document).on("click", ".deleteRow", function() {
            $(this).closest("tr").remove();
        });
    });
</script>
@endsection